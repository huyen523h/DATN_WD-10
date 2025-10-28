<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class MomoService
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $returnUrl;
    private $notifyUrl;

    public function __construct()
    {
        $this->endpoint = env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->partnerCode = env('MOMO_PARTNER_CODE');
        $this->accessKey = env('MOMO_ACCESS_KEY');
        $this->secretKey = env('MOMO_SECRET_KEY');
        $this->returnUrl = env('MOMO_RETURN_URL');
        $this->notifyUrl = env('MOMO_NOTIFY_URL');
    }

    public function createPayment(Booking $booking)
    {
        $orderId = time();
        $amount = $booking->total_amount;
        $orderInfo = "Thanh toán đơn đặt tour #{$booking->id}";
        $requestId = time();
        $extraData = "";

        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$this->notifyUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$this->returnUrl}&requestId={$requestId}&requestType=captureWallet";
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
            'signature' => $signature,
        ];

        $response = Http::post($this->endpoint, $data);
        $result = $response->json();

        if (isset($result['payUrl'])) {
            return redirect()->away($result['payUrl']);
        }

        return back()->with('error', 'Không thể khởi tạo thanh toán MoMo.');
    }

    public function handleReturn($request)
    {
        if ($request->resultCode == 0) {
            $booking = Booking::find($request->orderId);
            if ($booking) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'momo',
                    'amount' => $booking->total_amount,
                    'status' => 'success',
                    'transaction_code' => $request->transId,
                    'payment_date' => now(),
                    'raw_response' => json_encode($request->all()),
                ]);
                $booking->update(['status' => 'paid']);
            }
            return view('payments.result', ['success' => true, 'method' => 'MoMo']);
        }

        return view('payments.result', ['success' => false, 'method' => 'MoMo']);
    }
}
