<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;

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
        // Chặn guide truy cập route thanh toán
        if (auth()->user()->isGuide()) {
            abort(403, 'Hướng dẫn viên không thể thanh toán đặt tour. Vui lòng sử dụng trang quản lý lịch khởi hành.');
        }

        $booking = Booking::findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if ($booking->user_id != auth()->id()) {
            return back()->with('error', 'Bạn không có quyền thanh toán đơn hàng này.');
        }
        
        // Kiểm tra điều kiện thanh toán
        $canPayResult = $booking->canPay();
        if (!$canPayResult['can_pay']) {
            return back()->with('error', $canPayResult['message']);
        }
        
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
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
        $ipnUrl = env('MOMO_IPN_URL');
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
            // Thanh toán thành công
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
                //Cập nhật trạng thái booking thành 'paid' (đã thanh toán)
                $payment->booking()->update(['status' => 'paid']);

                // Send notification
                $notificationService = new NotificationService();
                $notificationService->notifyPaymentSuccess($payment);
                $data['message'] = 'Thanh toán thành công';
                $booking = $payment->booking;
            } else {
                $data['message'] = 'Không tìm thấy thông tin thanh toán';
            }

            return view('payment.result', ['data' => $data, 'booking' => $booking ?? null, 'method' => 'MoMo']);
        } else {
            // Thanh toán thất bại
            $orderId = $data['orderId'] ?? null;
            if ($orderId) {
                $payment = Payment::where('transaction_code', $orderId)->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                        'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    ]);

                    // Send notification
                    $notificationService = new NotificationService();
                    $notificationService->notifyPaymentFailed($payment, $data['message'] ?? 'Thanh toán không thành công');
                }
            }
            
            $data['message'] = $data['message'] ?? 'Thanh toán thất bại';
        }

        return view('payment.result', ['data' => $data, 'method' => 'MoMo']);
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

                // Update booking status thành 'paid' (đã thanh toán)
                $payment->booking()->update(['status' => 'paid']);

                // Send notification
                $notificationService = new NotificationService();
                $notificationService->notifyPaymentSuccess($payment);
            } else {
                // Nếu vì lý do nào đó chưa có payment pending, tạo mới
                $bookingId = $this->extractBookingIdFromOrderId($orderId);
                $payment = Payment::create([
                    'booking_id' => $bookingId,
                    'payment_method' => 'momo',
                    'amount' => $data['amount'],
                    'status' => 'completed',
                    'transaction_code' => $data['transId'],
                    'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'payment_date' => now(),
                ]);

                // Cập nhật trạng thái booking thành 'paid' (đã thanh toán)
                if ($bookingId) {
                    $booking = Booking::find($bookingId);
                    if ($booking) {
                        $booking->update(['status' => 'paid']);
                    }
                }

                // Send notification
                $notificationService = new NotificationService();
                $notificationService->notifyPaymentSuccess($payment);
            }

            return response()->json(['message' => 'Confirm Success', 'status' => 200]);
        } else {
            // Thanh toán thất bại
            $orderId = $data['orderId'] ?? null;
            if ($orderId) {
                $payment = Payment::where('transaction_code', $orderId)->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'failed',
                        'raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    ]);

                    // Send notification
                    $notificationService = new NotificationService();
                    $notificationService->notifyPaymentFailed($payment, $data['message'] ?? 'Thanh toán không thành công');
                }
            }

            return response()->json(['message' => 'Confirm Failed', 'status' => 400]);
        }
    }
    private function extractBookingIdFromOrderId($orderId)
    {
        if (preg_match('/BOOKING(\d+)_/', $orderId, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Xử lý thanh toán VNPay
     */
    public function vnpay_payment(Request $request, $id)
    {
        try {
            // Chặn guide truy cập route thanh toán
            if (auth()->user()->isGuide()) {
                abort(403, 'Hướng dẫn viên không thể thanh toán đặt tour. Vui lòng sử dụng trang quản lý lịch khởi hành.');
            }

            // Đảm bảo không có output trước khi xử lý
            if (ob_get_level()) {
                ob_clean();
            }
            
            FacadesLog::info('VNPay payment request', [
                'booking_id' => $id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            $booking = Booking::findOrFail($id);
            
            // Kiểm tra booking có thuộc về user hiện tại không
            if ($booking->user_id != auth()->id()) {
                FacadesLog::warning('Unauthorized VNPay payment attempt', [
                    'booking_id' => $id,
                    'booking_user_id' => $booking->user_id,
                    'current_user_id' => auth()->id()
                ]);
                return back()->with('error', 'Bạn không có quyền thanh toán đơn hàng này.');
            }
            
            // Kiểm tra điều kiện thanh toán
            $canPayResult = $booking->canPay();
            if (!$canPayResult['can_pay']) {
                FacadesLog::warning('VNPay payment - Payment not allowed', [
                    'booking_id' => $id,
                    'reason' => $canPayResult['message']
                ]);
                return back()->with('error', $canPayResult['message']);
            }
            
            $vnpayService = new VnpayService();
            $paymentUrl = $vnpayService->createPayment($booking);
            
            // Đảm bảo URL không chứa ký tự xuống dòng hoặc khoảng trắng thừa
            $paymentUrl = trim($paymentUrl);
            $paymentUrl = str_replace(["\r", "\n", "\t"], '', $paymentUrl);
            
            // Validate URL format
            if (!filter_var($paymentUrl, FILTER_VALIDATE_URL)) {
                throw new \Exception('URL thanh toán VNPay không hợp lệ: ' . substr($paymentUrl, 0, 100));
            }
            
            FacadesLog::info('VNPay payment URL created', [
                'booking_id' => $id,
                'payment_url_length' => strlen($paymentUrl),
                'payment_url_preview' => substr($paymentUrl, 0, 100) . '...'
            ]);
            
            // Đảm bảo redirect hoạt động - thử cả 2 cách
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => $paymentUrl
                ]);
            }
            
            // Redirect đến URL VNPay bên ngoài - đảm bảo không có output trước
            return redirect()->away($paymentUrl);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            FacadesLog::error('VNPay payment - Booking not found', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Không tìm thấy đơn đặt tour.');
        } catch (\Exception $e) {
            FacadesLog::error('VNPay payment error', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Có lỗi xảy ra khi tạo giao dịch VNPay: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý callback từ VNPay sau khi thanh toán
     */
    public function vnpay_return(Request $request)
    {
        try {
            FacadesLog::info('VNPay return callback', $request->all());
            
            // Kiểm tra nếu không có dữ liệu từ VNPay
            if (!$request->has('vnp_ResponseCode') && !$request->has('vnp_TxnRef')) {
                FacadesLog::warning('VNPay return - Missing required parameters', $request->all());
                return view('payment.result', [
                    'data' => $request->all(),
                    'success' => false,
                    'message' => 'Không nhận được thông tin từ VNPay. Vui lòng kiểm tra lại trạng thái đơn hàng.',
                    'method' => 'VNPay'
                ]);
            }
            
            $vnpayService = new VnpayService();
            $result = $vnpayService->handleReturn($request);
            
            // Đảm bảo có dữ liệu để hiển thị
            $data = $result['data'] ?? [];
            // Nếu không có data từ result, lấy từ request
            if (empty($data) && $request->has('vnp_TxnRef')) {
                // Tính amount từ vnp_Amount (đã nhân 100, cần chia lại)
                $amount = 0;
                if (isset($request->vnp_Amount) && $request->vnp_Amount !== '') {
                    $amount = (int)$request->vnp_Amount / 100;
                }
                
                $data = [
                    'orderId' => $request->vnp_TxnRef,
                    'vnp_TxnRef' => $request->vnp_TxnRef,
                    'amount' => $amount,
                    'vnp_ResponseCode' => $request->vnp_ResponseCode ?? null,
                    'vnp_TransactionNo' => $request->vnp_TransactionNo ?? null,
                    'vnp_Amount' => $request->vnp_Amount ?? null,
                    'vnp_ResponseMessage' => $request->vnp_ResponseMessage ?? null,
                ];
            }
            
            // Lấy booking từ result hoặc tìm từ transaction
            $booking = $result['booking'] ?? null;
            if (!$booking && isset($data['orderId'])) {
                // Tìm booking từ transaction code
                $payment = Payment::where('transaction_code', $data['orderId'])->first();
                if ($payment) {
                    $booking = $payment->booking;
                }
            }
            
            // Nếu không có amount trong data, lấy từ booking
            if (!isset($data['amount']) || $data['amount'] == 0) {
                if ($booking) {
                    $data['amount'] = $booking->total_amount;
                } elseif (isset($data['vnp_Amount']) && $data['vnp_Amount'] !== '') {
                    $data['amount'] = (int)$data['vnp_Amount'] / 100;
                }
            }
            
            return view('payment.result', [
                'data' => $data,
                'success' => $result['success'] ?? false,
                'message' => $result['message'] ?? ($request->vnp_ResponseMessage ?? 'Không có thông tin'),
                'method' => 'VNPay',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            FacadesLog::error('VNPay return error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            // Vẫn hiển thị trang result với thông tin có sẵn
            return view('payment.result', [
                'data' => $request->all(),
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý kết quả thanh toán: ' . $e->getMessage(),
                'method' => 'VNPay'
            ]);
        }
    }

    /**
     * Xử lý IPN từ VNPay
     */
    public function vnpay_ipn(Request $request)
    {
        try {
            $vnpayService = new VnpayService();
            return $vnpayService->handleIpn($request);
        } catch (\Exception $e) {
            FacadesLog::error('VNPay IPN error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error'], 500);
        }
    }
}
