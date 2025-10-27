<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;

use function Illuminate\Log\log;

class CheckoutController extends Controller
{
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function momo_payment(Request $request, $id)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $booking = Booking::findOrFail($id);
        $amount = (int) $booking->total_amount;
        //Tạo orderId unique
        $orderId = 'BOOKING' . $booking->id . '_' . time();
        //Tạo bản ghi payment pending
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'momo',
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'transaction_code' => $orderId, // dùng tạm để map callback
        ]);
        $redirectUrl = env('MOMO_REDIRECT_URL');
        $ipnUrl = $ipnUrl = env('MOMO_IPN_URL');
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";
        //$extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
        // dd('Amount gửi đi MoMo:', $amount);
        // dd($jsonResult);

        //Just a example, please check more in there
        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        } else {
            return response()->json([
                'error' => 'MoMo payment failed',
                'response' => $jsonResult
            ]);
        }
    }
    public function momo_return(Request $request)
    {
        FacadesLog::info('MoMo RETURN hit', $request->all());
        $data = $request->all();

        // Kiểm tra mã kết quả
        if (isset($data['resultCode']) && $data['resultCode'] == 0) {
            $orderId = $data['orderId'];
            //Tìm payment theo transaction_code (orderId unique lúc tạo)
            $payment = Payment::where('transaction_code', $orderId)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_code' => $data['transId'] ?? $orderId,
                    'payment_date' => now(),
                    'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                ]);
                //Cập nhật trạng thái booking
                $payment->booking()->update(['status' => 'completed']);


                $data['message'] = 'Thanh toán thành công';
            } else {
                $data['message'] = 'Thanh toán thất bại';
            }

            return view('payment.result', ['data' => $data]);
        }
    }
    public function momo_ipn(Request $request)
    {
        FacadesLog::info('MoMo IPN hit', $request->all());
        $data = $request->all();
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        // Chuẩn bị chuỗi để kiểm tra chữ ký
        $rawHash = "accessKey=" . $data['accessKey'] .
            "&amount=" . $data['amount'] .
            "&extraData=" . $data['extraData'] .
            "&message=" . $data['message'] .
            "&orderId=" . $data['orderId'] .
            "&orderInfo=" . $data['orderInfo'] .
            "&orderType=" . $data['orderType'] .
            "&partnerCode=" . $data['partnerCode'] .
            "&payType=" . $data['payType'] .
            "&requestId=" . $data['requestId'] .
            "&responseTime=" . $data['responseTime'] .
            "&resultCode=" . $data['resultCode'] .
            "&transId=" . $data['transId'];

        $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);
        //Kiểm tra chữ ký và mã kết quả
        if ($partnerSignature == $data['signature'] && $data['resultCode'] == 0) {
            $orderId = $data['orderId']; // ID booking
            // Tìm payment đã tạo trước đó
            $payment = Payment::where('transaction_code', $orderId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_code' => $data['transId'],
                    'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'payment_date' => now(),
                ]);

                // Update booking
                $payment->booking()->update(['status' => 'completed']);
            } else {
                // Nếu vì lý do nào đó chưa có payment pending, tạo mới
                Payment::create([
                    'booking_id' => $this->extractBookingIdFromOrderId($orderId),
                    'payment_method' => 'momo',
                    'amount' => $data['amount'],
                    'status' => 'completed',
                    'transaction_code' => $data['transId'],
                    'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'payment_date' => now(),
                ]);
            }

            return response()->json(['message' => 'Confirm Success', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Confirm Failed', 'status' => 400]);
        }
    }
    private function extractBookingIdFromOrderId($orderId)
    {
        if (preg_match('/BOOKING(\d+)_/', $orderId, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }
}
