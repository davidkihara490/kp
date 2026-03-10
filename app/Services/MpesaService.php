<?php
// app/Services/MpesaService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\MpesaTransaction;
use App\Models\Payment;
use App\Models\Parcel;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class MpesaService
{
    private $consumerKey;
    private $consumerSecret;
    private $shortcode;
    private $passkey;
    private $callbackUrl;
    private $environment;

    public function __construct()
    {
        // Load configuration
        $this->consumerKey = config('services.mpesa.consumer_key');
        $this->consumerSecret = config('services.mpesa.consumer_secret');
        $this->shortcode = config('services.mpesa.shortcode');
        $this->passkey = config('services.mpesa.passkey');
        $this->callbackUrl = config('services.mpesa.callback_url');
        $this->environment = config('services.mpesa.environment', 'sandbox');

        Log::info('MpesaService initialized', [
            'environment' => $this->environment,
            'shortcode' => $this->shortcode,
            'callback_url' => $this->callbackUrl
        ]);
    }

    /**
     * Generate access token for M-Pesa API
     */
    public function generateAccessToken()
    {
        $url = $this->getBaseUrl() . '/oauth/v1/generate?grant_type=client_credentials';

        Log::info('Generating M-Pesa access token', ['url' => $url]);

        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->timeout(30)
                ->retry(3, 100)
                ->get($url);

            Log::info('M-Pesa token response received', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Access token generated successfully');
                return $data['access_token'] ?? null;
            }

            Log::error('Failed to generate access token', [
                'response' => $response->body(),
                'status' => $response->status()
            ]);

            return null;
        } catch (Exception $e) {
            Log::error('Exception generating access token', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Generate password for STK Push
     */
    private function generatePassword($timestamp)
    {
        $data = $this->shortcode . $this->passkey . $timestamp;
        $password = base64_encode($data);
        
        Log::debug('Generated STK password', [
            'shortcode' => $this->shortcode,
            'timestamp' => $timestamp
        ]);
        
        return $password;
    }

    /**
     * Send STK Push request to M-Pesa
     */
    public function stkPush($phone, $amount, $accountReference, $transactionDesc, $parcelId = null, $userId = null)
    {
        Log::info('=== Starting STK Push Process ===', [
            'phone' => $phone,
            'amount' => $amount,
            'account_reference' => $accountReference,
            'parcel_id' => $parcelId,
            'user_id' => $userId
        ]);

        try {
            // Step 1: Generate access token
            Log::info('Step 1: Generating access token');
            $accessToken = $this->generateAccessToken();

            if (!$accessToken) {
                throw new Exception('Failed to generate access token');
            }
            Log::info('Access token generated successfully');

            // Step 2: Format phone number
            Log::info('Step 2: Formatting phone number', ['original' => $phone]);
            $phone = $this->formatPhoneNumber($phone);
            Log::info('Phone number formatted', ['formatted' => $phone]);

            // Step 3: Generate timestamp and password
            Log::info('Step 3: Generating timestamp and password');
            $timestamp = date('YmdHis');
            $password = $this->generatePassword($timestamp);

            // Step 4: Prepare STK Push payload
            Log::info('Step 4: Preparing STK Push payload');
            $payload = [
                'BusinessShortCode' => (int) $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) $amount,
                'PartyA' => (int) $phone,
                'PartyB' => (int) $this->shortcode,
                'PhoneNumber' => (int) $phone,
                'CallBackURL' => $this->callbackUrl,
                'AccountReference' => $accountReference,
                'TransactionDesc' => $transactionDesc,
            ];

            Log::info('STK Push payload prepared', ['payload' => $payload]);

            // Step 5: Send STK Push request
            Log::info('Step 5: Sending STK Push request to M-Pesa');
            $url = $this->getBaseUrl() . '/mpesa/stkpush/v1/processrequest';

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post($url, $payload);

            Log::info('STK Push response received', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            $responseData = $response->json();

            // Step 6: Create M-Pesa transaction record
            Log::info('Step 6: Creating M-Pesa transaction record');
            $transaction = MpesaTransaction::create([
                'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                'response_code' => $responseData['ResponseCode'] ?? null,
                'response_description' => $responseData['ResponseDescription'] ?? null,
                'customer_message' => $responseData['CustomerMessage'] ?? null,
                'phone_number' => $phone,
                'amount' => $amount,
                'account_reference' => $accountReference,
                'transaction_desc' => $transactionDesc,
                'status' => MpesaTransaction::STATUS_PENDING,
                'expires_at' => Carbon::now()->addMinutes(10),
                'request_data' => $payload,
                'raw_response' => $responseData,
                'parcel_id' => $parcelId,  // Link to parcel if provided
                'user_id' => $userId,       // Link to user if provided
            ]);

            Log::info('M-Pesa transaction created', [
                'transaction_id' => $transaction->id,
                'checkout_request_id' => $transaction->checkout_request_id,
                'status' => $transaction->status
            ]);

            // Step 7: Check if request was successful
            if (isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                Log::info('STK Push initiated successfully', [
                    'transaction_id' => $transaction->id,
                    'checkout_request_id' => $responseData['CheckoutRequestID']
                ]);

                // If we have parcel ID, create a pending payment record
                if ($parcelId) {
                    Log::info('Creating pending payment record for parcel', ['parcel_id' => $parcelId]);
                    
                    $payment = Payment::create([
                        'reference_number' => null, // Will be updated when M-Pesa completes
                        'parcel_id' => $parcelId,
                        'amount' => $amount,
                        'payment_method' => 'mpesa',
                        'payment_date' => now(),
                        'status' => 'pending',
                        'phone' => $phone,
                        'notes' => 'M-Pesa payment initiated. Awaiting confirmation.',
                        'paid_by' => $userId,
                        'mpesa_transaction_id' => $transaction->id,
                    ]);

                    Log::info('Pending payment record created', [
                        'payment_id' => $payment->id,
                        'parcel_id' => $parcelId
                    ]);
                }

                return [
                    'success' => true,
                    'message' => $responseData['CustomerMessage'] ?? 'STK Push sent successfully',
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'merchant_request_id' => $responseData['MerchantRequestID'],
                    'transaction_id' => $transaction->id,
                ];
            } else {
                // Handle immediate failure
                $errorMessage = $responseData['errorMessage'] ?? $responseData['ResponseDescription'] ?? 'Failed to send STK Push';
                
                Log::error('STK Push failed', [
                    'transaction_id' => $transaction->id,
                    'error' => $errorMessage
                ]);

                $transaction->update([
                    'result_code' => $responseData['errorCode'] ?? null,
                    'result_description' => $errorMessage,
                    'status' => MpesaTransaction::STATUS_FAILED,
                    'error_message' => $errorMessage,
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'error_code' => $responseData['errorCode'] ?? $responseData['ResponseCode'] ?? 'UNKNOWN',
                ];
            }
        } catch (Exception $e) {
            Log::error('STK Push Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'System error: ' . $e->getMessage(),
                'error_code' => 'SYSTEM_ERROR',
            ];
        } finally {
            Log::info('=== STK Push Process Completed ===');
        }
    }

    /**
     * Handle callback from M-Pesa and create/update Payment record
     */
    public function handleCallback($callbackData)
    {
        Log::info('====== M-PESA CALLBACK RECEIVED ======');
        Log::info('Full callback data', ['callback' => $callbackData]);

        DB::beginTransaction();

        try {
            // Step 1: Validate callback structure
            Log::info('Step 1: Validating callback structure');
            if (!isset($callbackData['Body']['stkCallback'])) {
                Log::error('Invalid callback structure - missing stkCallback');
                DB::rollBack();
                return false;
            }

            $stkCallback = $callbackData['Body']['stkCallback'];
            
            Log::info('STK Callback extracted', [
                'CheckoutRequestID' => $stkCallback['CheckoutRequestID'] ?? null,
                'ResultCode' => $stkCallback['ResultCode'] ?? null,
                'ResultDesc' => $stkCallback['ResultDesc'] ?? null
            ]);

            // Step 2: Find the transaction
            Log::info('Step 2: Finding transaction');
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            
            if (!$checkoutRequestId) {
                Log::error('Missing CheckoutRequestID in callback');
                DB::rollBack();
                return false;
            }

            $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

            if (!$transaction) {
                Log::error('Transaction not found', ['checkout_request_id' => $checkoutRequestId]);
                DB::rollBack();
                return false;
            }

            Log::info('Transaction found', [
                'transaction_id' => $transaction->id,
                'current_status' => $transaction->status,
                'parcel_id' => $transaction->parcel_id
            ]);

            // Step 3: Extract callback details
            Log::info('Step 3: Extracting callback details');
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;
            $callbackMetadata = $stkCallback['CallbackMetadata']['Item'] ?? null;

            // Step 4: Determine status
            Log::info('Step 4: Determining payment status');
            $status = $this->determineStatusFromResultCode($resultCode);
            
            Log::info('Status determined', [
                'resultCode' => $resultCode,
                'status' => $status
            ]);

            // Step 5: Prepare update data
            Log::info('Step 5: Preparing transaction update data');
            $updateData = [
                'result_code' => $resultCode,
                'result_description' => $resultDesc,
                'status' => $status,
                'callback_data' => $callbackData,
                'completed_at' => $status === MpesaTransaction::STATUS_COMPLETED ? Carbon::now() : null,
            ];

            // Step 6: Extract payment details if successful
            $mpesaReceiptNumber = null;
            $amountPaid = null;
            $payerPhone = null;
            $transactionDate = null;

            if ($resultCode === 0 && $callbackMetadata) {
                Log::info('Step 6: Extracting payment details from metadata');
                
                foreach ($callbackMetadata as $item) {
                    Log::debug('Processing metadata item', $item);
                    
                    switch ($item['Name']) {
                        case 'Amount':
                            $amountPaid = $item['Value'];
                            $updateData['amount_paid'] = $amountPaid;
                            Log::info('Amount extracted', ['amount' => $amountPaid]);
                            break;
                        case 'MpesaReceiptNumber':
                            $mpesaReceiptNumber = $item['Value'];
                            $updateData['mpesa_receipt_number'] = $mpesaReceiptNumber;
                            Log::info('Receipt number extracted', ['receipt' => $mpesaReceiptNumber]);
                            break;
                        case 'TransactionDate':
                            $transactionDate = $item['Value'];
                            $updateData['transaction_date'] = $transactionDate;
                            Log::info('Transaction date extracted', ['date' => $transactionDate]);
                            break;
                        case 'PhoneNumber':
                            $payerPhone = $item['Value'];
                            $updateData['payer_phone'] = $payerPhone;
                            Log::info('Phone number extracted', ['phone' => $payerPhone]);
                            break;
                    }
                }
            }

            // Step 7: Update transaction
            Log::info('Step 7: Updating transaction record');
            $transaction->update($updateData);
            Log::info('Transaction updated', ['new_status' => $transaction->status]);

            // Step 8: Handle payment record based on transaction status
            Log::info('Step 8: Handling payment record');

            if ($transaction->parcel_id) {
                // Find existing payment record
                $payment = Payment::where('mpesa_transaction_id', $transaction->id)->first();

                if ($payment) {
                    Log::info('Found existing payment record', ['payment_id' => $payment->id]);
                    
                    if ($status === MpesaTransaction::STATUS_COMPLETED) {
                        // Update payment as completed
                        $payment->update([
                            'reference_number' => $mpesaReceiptNumber,
                            'status' => 'completed',
                            'payment_date' => now(),
                            'notes' => 'M-Pesa payment completed. Receipt: ' . $mpesaReceiptNumber,
                        ]);
                        
                        Log::info('Payment completed', [
                            'payment_id' => $payment->id,
                            'receipt' => $mpesaReceiptNumber
                        ]);

                        // Update parcel payment status
                        $this->updateParcelPaymentStatus($transaction->parcel_id);
                        
                    } elseif (in_array($status, [MpesaTransaction::STATUS_FAILED, MpesaTransaction::STATUS_CANCELLED, MpesaTransaction::STATUS_EXPIRED])) {
                        // Update payment as failed
                        $payment->update([
                            'status' => 'failed',
                            'notes' => 'M-Pesa payment failed: ' . $resultDesc,
                        ]);
                        
                        Log::info('Payment failed', [
                            'payment_id' => $payment->id,
                            'reason' => $resultDesc
                        ]);
                    }
                } else {
                    Log::warning('No payment record found for transaction', [
                        'transaction_id' => $transaction->id,
                        'parcel_id' => $transaction->parcel_id
                    ]);
                    
                    // Create payment record if it doesn't exist and transaction is completed
                    if ($status === MpesaTransaction::STATUS_COMPLETED && $transaction->parcel_id) {
                        Log::info('Creating payment record from callback');
                        
                        $payment = Payment::create([
                            'reference_number' => $mpesaReceiptNumber,
                            'parcel_id' => $transaction->parcel_id,
                            'amount' => $amountPaid ?? $transaction->amount,
                            'payment_method' => 'mpesa',
                            'payment_date' => now(),
                            'status' => 'completed',
                            'phone' => $payerPhone ?? $transaction->phone_number,
                            'notes' => 'M-Pesa payment completed via callback. Receipt: ' . $mpesaReceiptNumber,
                            'paid_by' => $transaction->user_id,
                            'mpesa_transaction_id' => $transaction->id,
                        ]);
                        
                        Log::info('Payment record created from callback', [
                            'payment_id' => $payment->id
                        ]);

                        // Update parcel payment status
                        $this->updateParcelPaymentStatus($transaction->parcel_id);
                    }
                }
            }

            DB::commit();
            
            Log::info('Callback processed successfully', [
                'transaction_id' => $transaction->id,
                'status' => $status,
                'receipt' => $mpesaReceiptNumber
            ]);
            
            Log::info('====== M-PESA CALLBACK PROCESSING COMPLETED ======');
            
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Callback processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            Log::info('====== M-PESA CALLBACK PROCESSING FAILED ======');
            
            return false;
        }
    }

    /**
     * Update parcel payment status based on total payments
     */
    private function updateParcelPaymentStatus($parcelId)
    {
        Log::info('Updating parcel payment status', ['parcel_id' => $parcelId]);

        try {
            $parcel = Parcel::find($parcelId);
            
            if (!$parcel) {
                Log::warning('Parcel not found', ['parcel_id' => $parcelId]);
                return;
            }

            $totalPaid = Payment::where('parcel_id', $parcelId)
                ->where('status', 'completed')
                ->sum('amount');

            Log::info('Payment totals', [
                'total_paid' => $totalPaid,
                'parcel_amount' => $parcel->total_amount
            ]);

            if ($totalPaid >= $parcel->total_amount) {
                $parcel->payment_status = 'paid';
                Log::info('Parcel marked as fully paid');
            } elseif ($totalPaid > 0) {
                $parcel->payment_status = 'partially_paid';
                Log::info('Parcel marked as partially paid');
            } else {
                $parcel->payment_status = 'pending';
                Log::info('Parcel marked as pending');
            }

            $parcel->save();

            Log::info('Parcel payment status updated', [
                'parcel_id' => $parcelId,
                'new_status' => $parcel->payment_status
            ]);

        } catch (Exception $e) {
            Log::error('Failed to update parcel payment status', [
                'parcel_id' => $parcelId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check STK Push status
     */
    public function checkStkStatus($checkoutRequestId)
    {
        Log::info('Checking STK status', ['checkout_request_id' => $checkoutRequestId]);

        try {
            $accessToken = $this->generateAccessToken();

            if (!$accessToken) {
                throw new Exception('Failed to generate access token');
            }

            $timestamp = date('YmdHis');
            $password = $this->generatePassword($timestamp);

            $payload = [
                'BusinessShortCode' => (int) $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ];

            $url = $this->getBaseUrl() . '/mpesa/stkpushquery/v1/query';

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            $responseData = $response->json();

            Log::info('STK status response', [
                'checkout_request_id' => $checkoutRequestId,
                'result_code' => $responseData['ResultCode'] ?? null,
                'result_desc' => $responseData['ResultDesc'] ?? null
            ]);

            return [
                'success' => isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0',
                'result_code' => $responseData['ResultCode'] ?? null,
                'result_desc' => $responseData['ResultDesc'] ?? null,
                'response' => $responseData,
            ];
        } catch (Exception $e) {
            Log::error('STK status check failed', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $checkoutRequestId
            ]);
            
            return [
                'success' => false,
                'message' => 'System error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Determine status from M-Pesa result code
     */
    private function determineStatusFromResultCode($resultCode)
    {
        $resultCode = (int) $resultCode;
        
        Log::debug('Determining status from result code', ['result_code' => $resultCode]);

        switch ($resultCode) {
            case 0:
                return MpesaTransaction::STATUS_COMPLETED;
            case 1032:
                return MpesaTransaction::STATUS_CANCELLED;
            case 1037:
                return MpesaTransaction::STATUS_EXPIRED;
            case 1:      // Insufficient funds
            case 1036:   // Transaction failed
            case 2001:   // Rejected
                return MpesaTransaction::STATUS_FAILED;
            default:
                return MpesaTransaction::STATUS_FAILED;
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX format
     */
    private function formatPhoneNumber($phone)
    {
        Log::debug('Formatting phone number', ['original' => $phone]);

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert to 254 format
        if (substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) == '254') {
            // Already in correct format
        } elseif (substr($phone, 0, 3) == '254') {
            // Already in correct format
        } else {
            $phone = '254' . $phone;
        }

        // Validate length (should be 12 digits: 254 + 9 digits)
        if (strlen($phone) !== 12) {
            Log::error('Invalid phone number format', [
                'formatted' => $phone,
                'length' => strlen($phone)
            ]);
            throw new Exception('Invalid phone number format after formatting');
        }

        Log::debug('Phone number formatted successfully', ['formatted' => $phone]);
        return $phone;
    }

    /**
     * Get base URL based on environment
     */
    private function getBaseUrl()
    {
        $url = $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
            
        Log::debug('Base URL determined', ['environment' => $this->environment, 'url' => $url]);
        
        return $url;
    }

    /**
     * Cleanup expired transactions
     */
    public function cleanupExpiredTransactions()
    {
        Log::info('Starting cleanup of expired transactions');

        try {
            $expiredCount = MpesaTransaction::where('status', MpesaTransaction::STATUS_PENDING)
                ->where('expires_at', '<', Carbon::now())
                ->update([
                    'status' => MpesaTransaction::STATUS_EXPIRED,
                    'result_description' => 'STK Push expired',
                    'completed_at' => Carbon::now(),
                ]);

            Log::info('Expired transactions cleaned up', ['count' => $expiredCount]);

            // Update related payments
            $expiredTransactions = MpesaTransaction::where('status', MpesaTransaction::STATUS_EXPIRED)
                ->whereHas('payment')
                ->get();

            foreach ($expiredTransactions as $transaction) {
                if ($transaction->payment) {
                    $transaction->payment->update([
                        'status' => 'failed',
                        'notes' => 'M-Pesa payment expired'
                    ]);
                    
                    Log::info('Related payment marked as failed', [
                        'payment_id' => $transaction->payment->id,
                        'transaction_id' => $transaction->id
                    ]);
                }
            }

            return $expiredCount;
        } catch (Exception $e) {
            Log::error('Failed to cleanup expired transactions', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}