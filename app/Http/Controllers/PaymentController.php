<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Không áp dụng auth cho các callback/notify
        $this->middleware('auth')->except([
            'momoNotify',
            'momoReturn',
            'vnpayCallback',
        ]);
    }

    // Xử lý chọn phương thức thanh toán
    public function processPayment(Request $request, $bookingId)
    {
        $method = $request->query('method', 'momo');

        if ($method === 'vnpay') {
            return $this->vnpayPayment($bookingId);
        }

        return $this->momoPayment($bookingId);
    }

    // ================== MoMo ==================
    public function momoPayment($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $endpoint = env('MOMO_ENDPOINT');
        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey = env('MOMO_ACCESS_KEY');
        $secretKey = env('MOMO_SECRET_KEY');
        $redirectUrl = env('MOMO_RETURN_URL');
        $ipnUrl = env('MOMO_NOTIFY_URL');

        $orderId = $partnerCode . time();
        $requestId = time() . '';
        $orderInfo = (string)$booking->id;
        $amount = (int)$booking->total_amount;
        $requestType = "captureWallet";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "MoMo Sandbox",
            'storeId' => "Tour365",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => '',
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        $jsonResult = json_decode($result, true);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'momo',
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'transaction_code' => $orderId,
            'payment_date' => now(),
        ]);

        if (!empty($jsonResult['payUrl'])) {
            return redirect()->away($jsonResult['payUrl']);
        }

        Log::error('MoMo API Error:', $jsonResult);
        return back()->with('error', 'Không thể khởi tạo thanh toán MoMo.');
    }

    public function momoReturn(Request $request)
    {
        Log::info('MoMo Return:', $request->all());

        if ($request->resultCode == 0) {
            Payment::where('transaction_code', $request->orderId)->update(['status' => 'completed']);
            Booking::where('id', $request->orderInfo)->update(['status' => 'completed']);

            return redirect()->route('bookings.show', $request->orderInfo)
                ->with('success', 'Thanh toán MoMo thành công!');
        }

        return redirect()->route('bookings.index')->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy.');
    }

    public function momoNotify(Request $request)
    {
        Log::info('MoMo Notify:', $request->all());

        if ($request->resultCode == 0) {
            Payment::where('transaction_code', $request->orderId)->update(['status' => 'completed']);
            Booking::where('id', $request->orderInfo)->update(['status' => 'completed']);
        }

        return response('success', 200);
    }

    // ================== VNPAY ==================
    public function vnpayPayment($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $vnp_TmnCode = env('VNPAY_TMN_CODE', 'VNPAYDEMO');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'SECRETKEYVNPAY');
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('payment.vnpay.callback');

        $vnp_TxnRef = time();
        $vnp_OrderInfo = (string)$booking->id;
        $vnp_Amount = $booking->total_amount * 100;
        $vnp_Locale = "vn";

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = http_build_query($inputData);
        $hashdata = urldecode(http_build_query($inputData));
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'vnpay',
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'transaction_code' => $vnp_TxnRef,
            'payment_date' => now(),
        ]);

        return redirect()->away($vnp_Url);
    }

    public function vnpayCallback(Request $request)
    {
        Log::info('VNPAY Callback:', $request->all());

        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $vnp_OrderInfo = $request->get('vnp_OrderInfo');

        $payment = Payment::where('transaction_code', $vnp_TxnRef)->first();

        if (!$payment) {
            return redirect()->route('bookings.index')->with('error', 'Không tìm thấy giao dịch!');
        }

        if ($vnp_ResponseCode == "00") {
            $payment->update([
                'status' => 'completed',
                'raw_response' => json_encode($request->all()),
            ]);
            Booking::where('id', $vnp_OrderInfo)->update(['status' => 'completed']);

            return redirect()->route('bookings.show', $vnp_OrderInfo)
                ->with('success', 'Thanh toán VNPAY thành công!');
        } else {
            $payment->update([
                'status' => 'failed',
                'raw_response' => json_encode($request->all()),
            ]);

            return redirect()->route('bookings.show', $vnp_OrderInfo)
                ->with('error', 'Thanh toán VNPAY thất bại!');
        }
    }
}
