<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;

class VnpayService
{
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;

    public function __construct()
    {
        $this->tmnCode = env('VNP_TMN_CODE');
        $this->hashSecret = env('VNP_HASH_SECRET');
        $this->url = env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->returnUrl = env('VNP_RETURN_URL');
    }

    public function createPayment(Booking $booking)
    {
        // Tạo transaction reference từ booking ID
        $vnp_TxnRef = 'BOOKING' . $booking->id . '_' . time();
        $vnp_Amount = $booking->total_amount * 100;
        $vnp_OrderInfo = "Thanh toán tour #{$booking->id}";
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = now()->format('YmdHis');
        
        // Tạo payment record pending
        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'vnpay',
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'transaction_code' => $vnp_TxnRef,
        ]);

        // Chuẩn bị dữ liệu theo đúng format VNPay
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $this->returnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Sắp xếp các tham số theo thứ tự alphabet
        ksort($inputData);
        
        // Tạo query string (không encode các ký tự đặc biệt)
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo chữ ký theo đúng chuẩn VNPay
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        $vnp_Url = $this->url . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return redirect()->away($vnp_Url);
    }

    public function handleReturn($request)
    {
        // Kiểm tra chữ ký trước khi xử lý
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        
        // Kiểm tra chữ ký
        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == "00") {
                // Extract booking ID from transaction reference
                $txnRef = $request->vnp_TxnRef;
                if (preg_match('/BOOKING(\d+)_/', $txnRef, $matches)) {
                    $bookingId = (int) $matches[1];
                    $booking = Booking::find($bookingId);
                    
                    if ($booking) {
                        // Tìm payment đã tạo trước đó
                        $payment = Payment::where('transaction_code', $txnRef)->first();
                        
                        if ($payment) {
                            $payment->update([
                                'status' => 'completed',
                                'transaction_code' => $request->vnp_TransactionNo,
                                'payment_date' => now(),
                                'raw_response' => json_encode($request->all()),
                            ]);
                        } else {
                            // Nếu không tìm thấy, tạo mới
                            $payment = Payment::create([
                                'booking_id' => $booking->id,
                                'payment_method' => 'vnpay',
                                'amount' => $booking->total_amount,
                                'status' => 'completed',
                                'transaction_code' => $request->vnp_TransactionNo,
                                'payment_date' => now(),
                                'raw_response' => json_encode($request->all()),
                            ]);
                        }
                        
                        $booking->update(['status' => 'paid']);
                        
                        // Send notification
                        $notificationService = new \App\Services\NotificationService();
                        $notificationService->notifyPaymentSuccess($payment);
                    }
                }
                // Truyền đầy đủ data từ VNPay response
                $resultData = array_merge($request->all(), ['message' => 'Thanh toán thành công']);
                return view('payment.result', [
                    'success' => true, 
                    'method' => 'VNPay', 
                    'data' => $resultData,
                    'booking' => $booking ?? null
                ]);
            } else {
                $resultData = array_merge($request->all(), ['message' => $request->vnp_ResponseCode . ' - ' . ($request->vnp_ResponseMessage ?? 'Thanh toán thất bại')]);
                return view('payment.result', [
                    'success' => false, 
                    'method' => 'VNPay', 
                    'data' => $resultData,
                    'booking' => null
                ]);
            }
        } else {
            return view('payment.result', [
                'success' => false, 
                'method' => 'VNPay', 
                'data' => ['message' => 'Chữ ký không hợp lệ'],
                'booking' => null
            ]);
        }
    }
}
