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
        $vnp_TxnRef = time();
        $vnp_Amount = $booking->total_amount * 100;
        $vnp_OrderInfo = "Thanh toán tour #{$booking->id}";
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $this->returnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = urldecode($query);

        $vnp_Url = $this->url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect()->away($vnp_Url);
    }

    public function handleReturn($request)
    {
        if ($request->vnp_ResponseCode == "00") {
            $booking = Booking::find($request->vnp_TxnRef);
            if ($booking) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'vnpay',
                    'amount' => $booking->total_amount,
                    'status' => 'success',
                    'transaction_code' => $request->vnp_TransactionNo,
                    'payment_date' => now(),
                    'raw_response' => json_encode($request->all()),
                ]);
                $booking->update(['status' => 'paid']);
            }
            return view('payments.result', ['success' => true, 'method' => 'VNPay']);
        }

        return view('payments.result', ['success' => false, 'method' => 'VNPay']);
    }
}
