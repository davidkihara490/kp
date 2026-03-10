<?php

namespace App\Http\Controllers\api\v1\Mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Handle M-Pesa STK Push callback
     */
    public function stkCallback(Request $request)
    {

        return '1111111111111111111111111111111111111111';

        
        Log::info('=== M-PESA STK CALLBACK RECEIVED ===');
        Log::info('Headers:', $request->headers->all());
        Log::info('Body:', $request->all());
        Log::info('IP: ' . $request->ip());
        Log::info('====================================');

        // Get the callback data from request
        $callbackData = $request->all();
        
        // Validate the callback has required data
        if (empty($callbackData) || !isset($callbackData['Body']['stkCallback'])) {
            Log::error('Invalid callback data received', $callbackData);
            
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Invalid callback data'
            ]);
        }

        try {
            // Process the callback
            $success = $this->mpesaService->handleCallback($callbackData);
            
            if ($success) {
                Log::info('Callback processed successfully');
                
                // Return success response to M-Pesa
                return response()->json([
                    'ResultCode' => 0,
                    'ResultDesc' => 'Success'
                ]);
            } else {
                Log::error('Failed to process callback');
                
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Failed to process'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Callback processing exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $callbackData
            ]);
            
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Internal server error'
            ]);
        }
    }

    /**
     * Handle C2B (PayBill/Till) callback
     */
    public function c2bCallback(Request $request)
    {
        Log::info('M-Pesa C2B Callback Received', $request->all());
        
        // Process C2B payment confirmation
        // Similar to STK but different data structure
        
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }

    /**
     * Handle B2C (Business to Customer) callback
     */
    public function b2cCallback(Request $request)
    {
        Log::info('M-Pesa B2C Callback Received', $request->all());
        
        // Process B2C payment confirmation
        
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }

    /**
     * Test callback endpoint (for development)
     */
    public function testCallback(Request $request)
    {
        // Sample callback data for testing
        $sampleCallbacks = [
            'success' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_123456789012',
                        'CheckoutRequestID' => 'ws_CO_300120241234567890',
                        'ResultCode' => 0,
                        'ResultDesc' => 'The service request is processed successfully.',
                        'CallbackMetadata' => [
                            'Item' => [
                                ['Name' => 'Amount', 'Value' => 500],
                                ['Name' => 'MpesaReceiptNumber', 'Value' => 'NCB123456789'],
                                ['Name' => 'Balance', 'Value' => 15000],
                                ['Name' => 'TransactionDate', 'Value' => '20240130123456'],
                                ['Name' => 'PhoneNumber', 'Value' => '254712345678'],
                            ]
                        ]
                    ]
                ]
            ],
            'cancelled' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_987654321098',
                        'CheckoutRequestID' => 'ws_CO_300120249876543210',
                        'ResultCode' => 1032,
                        'ResultDesc' => 'Request cancelled by user.',
                    ]
                ]
            ],
            'failed' => [
                'Body' => [
                    'stkCallback' => [
                        'MerchantRequestID' => 'AG_20240130_555555555555',
                        'CheckoutRequestID' => 'ws_CO_300120245555555555',
                        'ResultCode' => 1,
                        'ResultDesc' => 'The balance is insufficient for the transaction',
                    ]
                ]
            ]
        ];

        $type = $request->input('type', 'success');
        $sampleData = $sampleCallbacks[$type] ?? $sampleCallbacks['success'];
        
        // Process the sample callback
        $success = $this->mpesaService->handleCallback($sampleData);
        
        return response()->json([
            'success' => $success,
            'message' => 'Test callback processed',
            'type' => $type,
            'data' => $sampleData,
        ]);
    }
}

// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 0,
//       "ResultDesc": "The service request is processed successfully.",
//       "CallbackMetadata": {
//         "Item": [
//           {
//             "Name": "Amount",
//             "Value": 1
//           },
//           {
//             "Name": "MpesaReceiptNumber",
//             "Value": "NCB123456789"
//           },
//           {
//             "Name": "Balance"
//           },
//           {
//             "Name": "TransactionDate",
//             "Value": 20240130123456
//           },
//           {
//             "Name": "PhoneNumber",
//             "Value": 254712345678
//           }
//         ]
//       }
//     }
//   }
// }

// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 1032,
//       "ResultDesc": "Request cancelled by user."
//     }
//   }
// }


// {
//   "Body": {
//     "stkCallback": {
//       "MerchantRequestID": "29115-34620561-1",
//       "CheckoutRequestID": "ws_CO_300120241234567890",
//       "ResultCode": 1,
//       "ResultDesc": "The balance is insufficient for the transaction."
//     }
//   }
// }