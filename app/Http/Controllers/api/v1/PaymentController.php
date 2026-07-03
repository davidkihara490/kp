<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\Payment;
use App\Services\MpesaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MpesaService $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Process M-PESA payment for a parcel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required|exists:parcels,parcel_id',
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|string',
            'payment_method' => 'required|in:mpesa,cash,card,bank_transfer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the parcel
        $parcel = Parcel::where('parcel_id', $request->parcel_id)->firstOrFail();

        // Check if already paid
        if ($parcel->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This parcel has already been paid for.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Generate transaction ID
            $transactionId = 'TRX' . date('Ymd') . strtoupper(substr(uniqid(), -6));

            // Try to process M-PESA payment
            $accountReference = $parcel->parcel_id;
            $transactionDesc = 'Payment for parcel No:' . $parcel->parcel_id;

            $result = $this->mpesaService->stkPush(
                $request->phone,
                $request->amount,
                $accountReference,
                $transactionDesc,
                $parcel->id,
                Auth::guard('customer')->user()?->id ?? null
            );

            if ($result['success']) {

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'STK Push sent! Please check your phone and enter your M-Pesa PIN to complete payment.',
                    'checkout_request_id' => $result['checkout_request_id'] ?? null,
                    'transaction_id' => $transactionId,
                    'payment_status' => 'pending',
                    'receipt_number' => $result['transaction_id'] ?? null,
                ]);
            } else {
                // Payment initiation failed
                // $payment->update([
                //     'payment_status' => 'failed',
                //     'failure_reason' => $result['message'] ?? 'Payment initiation failed'
                // ]);

                DB::commit();

                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Payment initiation failed. Please try again.',
                    'payment_status' => 'failed',
                ], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'parcel_id' => $request->parcel_id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your payment. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status and update if completed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Check payment status for a parcel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required|exists:parcels,parcel_id',
            'checkout_request_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $parcel = Parcel::where('parcel_id', $request->parcel_id)->firstOrFail();

            // Query the status from M-PESA
            $status = $this->mpesaService->checkStkStatus($request->checkout_request_id);

            // Check if we have a result_code (matching Livewire logic)
            if (isset($status['result_code'])) {
                $resultCode = $status['result_code'];

                switch ($resultCode) {
                    case 0:
                        // Payment successful
                        $payment = $parcel->payments()->latest()->first();

                        if ($payment) {
                            $payment->update([
                                'payment_status' => 'paid',
                                'mpesa_receipt' => $status['receipt_number'] ?? null,
                            ]);
                        }

                        $parcel->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                        ]);

                        // Add tracking status
                        $parcel->addTracking(
                            'payment_received',
                            auth()->id() ?? null,
                            'Payment received via M-PESA. Receipt: ' . ($status['receipt_number'] ?? 'N/A')
                        );

                        return response()->json([
                            'success' => true,
                            'result_code' => 0,
                            'message' => $status['user_message'] ?? 'Payment completed successfully!',
                            'payment_status' => 'paid',
                            'receipt_number' => $status['receipt_number'] ?? null,
                        ]);

                    case 1032:
                        // Transaction cancelled by user
                        return response()->json([
                            'success' => false,
                            'result_code' => 1032,
                            'message' => $status['user_message'] ?? 'Transaction cancelled. You did not enter your M-PESA PIN.',
                            'payment_status' => 'cancelled',
                        ]);

                    case 1037:
                        // Payment timeout
                        return response()->json([
                            'success' => false,
                            'result_code' => 1037,
                            'message' => $status['user_message'] ?? 'Payment timeout. You took too long to enter your PIN. Please try again.',
                            'payment_status' => 'timeout',
                        ]);

                    case 1:
                        // Insufficient funds
                        $payment = $parcel->payments()->latest()->first();
                        if ($payment) {
                            $payment->update([
                                'payment_status' => 'failed',
                                'failure_reason' => 'Insufficient funds'
                            ]);
                        }
                        return response()->json([
                            'success' => false,
                            'result_code' => 1,
                            'message' => $status['user_message'] ?? 'Insufficient funds in your M-Pesa account. Please ensure you have enough balance and try again.',
                            'payment_status' => 'failed',
                        ]);

                    case 1019:
                        // Wrong PIN
                        $payment = $parcel->payments()->latest()->first();
                        if ($payment) {
                            $payment->update([
                                'payment_status' => 'failed',
                                'failure_reason' => 'Wrong PIN entered'
                            ]);
                        }
                        return response()->json([
                            'success' => false,
                            'result_code' => 1019,
                            'message' => $status['user_message'] ?? 'Wrong PIN entered. Please check your M-PESA PIN and try again.',
                            'payment_status' => 'failed',
                        ]);

                    case 1036:
                    case 2001:
                    case 1031:
                    case 1026:
                        // Various payment failures
                        $payment = $parcel->payments()->latest()->first();
                        if ($payment) {
                            $payment->update([
                                'payment_status' => 'failed',
                                'failure_reason' => 'Payment failed with code: ' . $resultCode
                            ]);
                        }
                        return response()->json([
                            'success' => false,
                            'result_code' => $resultCode,
                            'message' => $status['user_message'] ?? 'Payment failed. Please try again or use a different payment method.',
                            'payment_status' => 'failed',
                        ]);

                    default:
                        // Unknown result code - still pending or unknown
                        return response()->json([
                            'success' => false,
                            'result_code' => $resultCode,
                            'message' => $status['user_message'] ?? 'Payment status unknown. Please check transaction history or contact support.',
                            'payment_status' => 'pending',
                        ]);
                }
            } else {
                // No result_code - still pending
                return response()->json([
                    'success' => false,
                    'message' => $status['message'] ?? 'Payment still pending',
                    'payment_status' => 'pending',
                ]);
            }
        } catch (Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage(), [
                'parcel_id' => $request->parcel_id,
                'checkout_request_id' => $request->checkout_request_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking payment status.',
                'payment_status' => 'unknown',
            ], 500);
        }
    }
    /**
     * Simulate M-PESA payment (for testing purposes).
     *
     * @param  string  $phone
     * @param  float  $amount
     * @param  string  $transactionId
     * @return array
     */
    private function simulateMpesaPayment($phone, $amount, $transactionId)
    {
        // Simulate successful payment
        // In production, replace with actual M-PESA API call using:
        // - Safaricom Daraja API
        // - Or a third-party service like PesaPal, Cellulant, etc.

        $success = true; // Simulate success
        $receiptNumber = 'MP' . date('Ymd') . strtoupper(substr(uniqid(), -6));

        return [
            'success' => $success,
            'receipt_number' => $receiptNumber,
            'message' => 'Payment processed successfully'
        ];
    }

    /**
     * Get payment status for a parcel.
     *
     * @param  string  $parcelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentStatus($parcelId)
    {
        try {
            $parcel = Parcel::where('parcel_id', $parcelId)->firstOrFail();
            $payment = $parcel->payments()->latest()->first();

            return response()->json([
                'success' => true,
                'payment_status' => $parcel->payment_status,
                'amount' => $parcel->total_amount,
                'paid_at' => $parcel->paid_at,
                'payment_method' => $parcel->payment_method,
                'transaction_id' => $payment->transaction_id ?? null,
                'mpesa_receipt' => $payment->mpesa_receipt ?? null,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Parcel not found'
            ], 404);
        }
    }

    /**
     * Generate receipt for a parcel.
     *
     * @param  string  $parcelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateReceipt($parcelId)
    {
        try {
            $parcel = Parcel::with(['senderTown', 'receiverTown', 'payments'])
                ->where('parcel_id', $parcelId)
                ->firstOrFail();

            $payment = $parcel->payments()->latest()->first();

            return response()->json([
                'success' => true,
                'receipt' => [
                    'parcel_id' => $parcel->parcel_id,
                    'sender_name' => $parcel->sender_name,
                    'sender_phone' => $parcel->sender_phone,
                    'receiver_name' => $parcel->receiver_name,
                    'receiver_phone' => $parcel->receiver_phone,
                    'from_town' => $parcel->senderTown?->name ?? 'N/A',
                    'to_town' => $parcel->receiverTown?->name ?? 'N/A',
                    'parcel_type' => $parcel->parcel_type,
                    'weight' => $parcel->weight,
                    'amount' => $parcel->total_amount,
                    'payment_method' => $payment->payment_method ?? 'N/A',
                    'transaction_id' => $payment->transaction_id ?? 'N/A',
                    'mpesa_receipt' => $payment->mpesa_receipt ?? 'N/A',
                    'payment_date' => $payment->created_at ? $payment->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                    'status' => $parcel->payment_status,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Receipt generation failed'
            ], 404);
        }
    }
}
