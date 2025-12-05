<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VnpayService
{
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;

    public function __construct()
    {
        $this->tmnCode = trim(config('services.vnpay.tmn_code') ?? env('VNP_TMN_CODE') ?? '');
        $this->hashSecret = trim(config('services.vnpay.hash_secret') ?? env('VNP_HASH_SECRET') ?? '');
        $this->url = config('services.vnpay.url') ?? env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->returnUrl = config('services.vnpay.return_url') ?? env('VNP_RETURN_URL', url('/payment/vnpay_return'));
        
        if (empty($this->tmnCode) || empty($this->hashSecret)) {
            throw new \Exception('VNPay configuration is missing. Please check VNP_TMN_CODE and VNP_HASH_SECRET in .env file.');
        }
        
        // Đảm bảo hash secret không có khoảng trắng thừa
        $this->hashSecret = trim($this->hashSecret);
        $this->tmnCode = trim($this->tmnCode);
        
        // Validate hash secret length (thường là 32 ký tự)
        if (strlen($this->hashSecret) < 20) {
            Log::warning('VNPay HashSecret seems too short', [
                'length' => strlen($this->hashSecret),
                'preview' => substr($this->hashSecret, 0, 10) . '...'
            ]);
        }
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPayment(Booking $booking)
    {
        $vnp_TxnRef = 'BOOKING' . $booking->id . '_' . time();
        $vnp_Amount = (string)((int)($booking->total_amount * 100));
        $vnp_OrderInfo = "Thanhtoantour" . $booking->id;
        $vnp_IpAddr = request()->ip() ?: '127.0.0.1';
        if ($vnp_IpAddr == '::1') {
            $vnp_IpAddr = '127.0.0.1';
        }

        $returnUrl = trim($this->returnUrl);
        if (empty($returnUrl)) {
            $returnUrl = url('/payment/vnpay_return');
        }
        if (!filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            $returnUrl = url($returnUrl);
        }
        // Đảm bảo Return URL là absolute URL và không có ký tự đặc biệt
        $returnUrl = rtrim($returnUrl, '/');
        
        // Loại bỏ mọi ký tự đặc biệt có thể gây lỗi
        $returnUrl = str_replace(["\r", "\n", "\t"], '', $returnUrl);

        // Tạo hoặc cập nhật payment record
        $payment = Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
                'payment_method' => 'vnpay',
                'status' => 'pending'
            ],
            [
                'amount' => $booking->total_amount,
                'transaction_code' => $vnp_TxnRef,
            ]
        );

        // Chuẩn bị dữ liệu theo chuẩn VNPay 2.1.0
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $returnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Đảm bảo tất cả tham số bắt buộc có mặt (theo chuẩn VNPay 2.1.0)
        $required = [
            'vnp_Version',
            'vnp_Command', 
            'vnp_TmnCode',
            'vnp_Amount',
            'vnp_CurrCode',
            'vnp_TxnRef',
            'vnp_OrderInfo',
            'vnp_OrderType',
            'vnp_Locale',
            'vnp_ReturnUrl',
            'vnp_CreateDate',
            'vnp_IpAddr'
        ];
        
        // Loại bỏ tham số rỗng (giữ lại tham số bắt buộc)
        $inputData = array_filter($inputData, function($value, $key) use ($required) {
            return in_array($key, $required) || ($value !== null && $value !== '');
        }, ARRAY_FILTER_USE_BOTH);

        // Đảm bảo tất cả tham số bắt buộc đều có mặt
        foreach ($required as $reqKey) {
            if (!isset($inputData[$reqKey]) || $inputData[$reqKey] === '') {
                throw new \Exception("VNPay required parameter missing: {$reqKey}");
            }
        }

        // Sắp xếp theo alphabet (bắt buộc)
        ksort($inputData);

        // Tạo hashdata và query string theo code demo chính thức của VNPay
        // QUAN TRỌNG: Theo code demo VNPay, hashdata cũng phải urlencode key và value
        $hashdata = "";
        $query = "";
        $i = 0;
        
        foreach ($inputData as $key => $value) {
            // Đảm bảo value là string và clean
            $value = trim((string)$value);
            // Loại bỏ ký tự đặc biệt có thể gây lỗi
            $value = str_replace(["\r", "\n", "\t"], '', $value);
            
            // Hashdata: ENCODE cả key và value (theo code demo chính thức VNPay)
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            
            // Query string: cũng encode
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        // Tạo chữ ký HMAC SHA512 (theo chuẩn VNPay 2.1.0)
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
        
        // Log để debug
        Log::info('VNPay Payment Creation', [
            'input_data' => $inputData,
            'input_data_keys' => array_keys($inputData),
            'hashdata' => $hashdata,
            'hashdata_length' => strlen($hashdata),
            'hash_secret_length' => strlen($this->hashSecret),
            'hash_secret_preview' => substr($this->hashSecret, 0, 10) . '...',
            'secure_hash' => $vnpSecureHash,
            'secure_hash_length' => strlen($vnpSecureHash),
            'tmn_code' => $this->tmnCode,
            'return_url' => $returnUrl,
            'query_preview' => substr($query, 0, 200),
            'full_query' => $query,
        ]);

        // Tạo URL thanh toán (theo code demo VNPay)
        $vnp_Url = $this->url . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return $vnp_Url;
    }

    /**
     * Xử lý callback từ VNPay
     */
    public function handleReturn(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash ?? '';
        
        if (empty($vnp_SecureHash)) {
            return [
                'success' => false,
                'message' => 'Thiếu chữ ký xác thực từ VNPay',
                'data' => $request->all()
            ];
        }

        // Lấy các tham số vnp_* (trừ vnp_SecureHash)
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'vnp_') === 0 && $key !== 'vnp_SecureHash' && $value !== null && $value !== '') {
                $inputData[$key] = (string)$value;
            }
        }

        // Sắp xếp theo alphabet
        ksort($inputData);
        
        // Tạo hashdata (theo code demo chính thức VNPay - ENCODE cả key và value)
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            $value = (string)$value;
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        // Verify chữ ký
        $secureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            Log::warning('VNPay signature mismatch', [
                'received' => $vnp_SecureHash,
                'calculated' => $secureHash,
                'hashdata' => $hashdata
            ]);
            return [
                'success' => false,
                'message' => 'Chữ ký không hợp lệ',
                'data' => $request->all()
            ];
        }

        $vnp_ResponseCode = $request->vnp_ResponseCode ?? '';
        $vnp_TxnRef = $request->vnp_TxnRef ?? '';
        $vnp_TransactionNo = $request->vnp_TransactionNo ?? null;

        // Tìm payment
        $payment = Payment::where('transaction_code', $vnp_TxnRef)->first();

        if ($vnp_ResponseCode == "00") {
            // Thanh toán thành công
            $booking = null;
            if ($payment && $payment->status !== 'completed') {
                    $payment->update([
                        'status' => 'completed',
                        'transaction_code' => $vnp_TransactionNo ?? $vnp_TxnRef,
                        'payment_date' => now(),
                        'raw_response' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                    ]);

                    $payment->booking->update(['status' => 'paid']);
                $booking = $payment->booking;
            } elseif ($payment) {
                $booking = $payment->booking;
            }

            return [
                'success' => true,
                'message' => 'Thanh toán thành công',
                'data' => [
                    'orderId' => $vnp_TxnRef,
                    'transactionNo' => $vnp_TransactionNo,
                    'amount' => isset($request->vnp_Amount) ? (int)$request->vnp_Amount / 100 : 0,
                    'responseCode' => $vnp_ResponseCode,
                    'message' => $request->vnp_ResponseMessage ?? 'Thanh toán thành công'
                ],
                'booking' => $booking
            ];
        } else {
            // Thanh toán thất bại - Giữ booking ở trạng thái PENDING và gia hạn thêm 15 phút
            $booking = null;
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'raw_response' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                ]);
                $booking = $payment->booking;
                
                // Giữ booking ở trạng thái PENDING và gia hạn thêm 15 phút để thanh toán lại
                if ($booking && $booking->status === 'pending') {
                    $booking->update([
                        'expires_at' => now()->addMinutes(15)
                    ]);
                }
            }

            $responseMessages = [
                '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
                '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking',
                '10' => 'Xác thực thông tin thẻ/tài khoản không đúng. Quá 3 lần',
                '11' => 'Đã hết hạn chờ thanh toán. Vui lòng thực hiện lại giao dịch.',
                '12' => 'Thẻ/Tài khoản bị khóa.',
                '13' => 'Nhập sai mật khẩu xác thực giao dịch (OTP). Quá 3 lần',
                '24' => 'Khách hàng hủy giao dịch hoặc hết thời gian chờ thanh toán.',
                '51' => 'Tài khoản không đủ số dư để thực hiện giao dịch.',
                '65' => 'Tài khoản đã vượt quá hạn mức giao dịch trong ngày.',
                '75' => 'Ngân hàng thanh toán đang bảo trì.',
                '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định.',
            ];

            $message = $responseMessages[$vnp_ResponseCode] ?? ($request->vnp_ResponseMessage ?? 'Thanh toán thất bại');

            // Lấy amount từ request (vnp_Amount đã được nhân 100, cần chia lại)
            $amount = 0;
            if (isset($request->vnp_Amount) && $request->vnp_Amount !== '') {
                $amount = (int)$request->vnp_Amount / 100;
            } elseif ($payment && $payment->booking) {
                // Nếu không có từ request, lấy từ booking
                $amount = $payment->booking->total_amount;
            }

            return [
                'success' => false,
                'message' => $message,
                'data' => [
                    'orderId' => $vnp_TxnRef,
                    'vnp_TxnRef' => $vnp_TxnRef,
                    'amount' => $amount,
                    'responseCode' => $vnp_ResponseCode,
                    'vnp_ResponseCode' => $vnp_ResponseCode,
                    'responseMessage' => $request->vnp_ResponseMessage ?? $message,
                    'vnp_ResponseMessage' => $request->vnp_ResponseMessage ?? $message,
                    'vnp_Amount' => $request->vnp_Amount ?? null,
                ],
                'booking' => $booking
            ];
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay
     */
    public function handleIpn(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash ?? '';
        
        if (empty($vnp_SecureHash)) {
            return response()->json(['RspCode' => '97', 'Message' => 'Missing SecureHash'], 400);
        }

        // Lấy các tham số vnp_* (trừ vnp_SecureHash)
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'vnp_') === 0 && $key !== 'vnp_SecureHash' && $value !== null && $value !== '') {
                $inputData[$key] = (string)$value;
            }
        }

        // Sắp xếp theo alphabet
        ksort($inputData);
        
        // Tạo hashdata (theo code demo chính thức VNPay - ENCODE cả key và value)
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            $value = (string)$value;
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        // Verify chữ ký
        $secureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            Log::warning('VNPay IPN signature mismatch', [
                'received' => $vnp_SecureHash,
                'calculated' => $secureHash
            ]);
            return response()->json(['RspCode' => '97', 'Message' => 'Checksum failed'], 400);
        }

        $vnp_ResponseCode = $request->vnp_ResponseCode ?? '';
        $vnp_TxnRef = $request->vnp_TxnRef ?? '';

        // Tìm payment
        $payment = Payment::where('transaction_code', $vnp_TxnRef)->first();

        if (!$payment) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found'], 404);
        }

        if ($vnp_ResponseCode == "00") {
            // Thanh toán thành công
            if ($payment->status == 'pending') {
                $payment->update([
                    'status' => 'completed',
                    'transaction_code' => $request->vnp_TransactionNo ?? $vnp_TxnRef,
                    'payment_date' => now(),
                    'raw_response' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                ]);

                $payment->booking->update(['status' => 'paid']);

                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            } else {
                return response()->json(['RspCode' => '00', 'Message' => 'Order already confirmed']);
            }
        } else {
            // Thanh toán thất bại
            if ($payment->status == 'pending') {
                $payment->update([
                    'status' => 'failed',
                    'raw_response' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                ]);
            }

            return response()->json(['RspCode' => '00', 'Message' => 'Transaction failed']);
        }
    }
}
