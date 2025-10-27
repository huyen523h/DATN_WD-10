@extends('layouts.app')

@section('title', 'Test Invoice API')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>🧪 Test Invoice API</h4>
                </div>
                <div class="card-body">
                    <!-- Login Form -->
                    <div id="login-section" class="mb-4">
                        <h5>1. Đăng nhập để lấy token</h5>
                        <form id="login-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="admin@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" value="password">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        </form>
                        <div id="token-display" class="mt-3" style="display: none;">
                            <strong>Token:</strong> <code id="token-value"></code>
                        </div>
                    </div>

                    <!-- Test Buttons -->
                    <div id="test-section" style="display: none;">
                        <h5>2. Test các chức năng</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Booking ID</label>
                                    <input type="number" class="form-control" id="booking-id" value="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" value="2500000">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex">
                            <button class="btn btn-info" onclick="testGetInvoices()">📋 Lấy danh sách hóa đơn</button>
                            <button class="btn btn-success" onclick="testCreateInvoice()">➕ Tạo hóa đơn</button>
                            <button class="btn btn-warning" onclick="testGeneratePDF()">📄 Tạo PDF</button>
                            <button class="btn btn-danger" onclick="testDownloadPDF()">⬇️ Tải PDF</button>
                        </div>
                    </div>

                    <!-- Results -->
                    <div id="results" class="mt-4">
                        <h5>3. Kết quả</h5>
                        <div id="response-display" class="bg-light p-3 rounded" style="min-height: 200px;">
                            <em>Kết quả sẽ hiển thị ở đây...</em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let authToken = '';

// Login form
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            authToken = data.data.token;
            document.getElementById('token-value').textContent = authToken;
            document.getElementById('token-display').style.display = 'block';
            document.getElementById('test-section').style.display = 'block';
            showResult('✅ Đăng nhập thành công!', data);
        } else {
            showResult('❌ Đăng nhập thất bại: ' + data.message, data);
        }
    } catch (error) {
        showResult('❌ Lỗi: ' + error.message, error);
    }
});

// Test functions
async function testGetInvoices() {
    try {
        const response = await fetch('/api/invoices', {
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        showResult('📋 Danh sách hóa đơn:', data);
    } catch (error) {
        showResult('❌ Lỗi lấy danh sách hóa đơn: ' + error.message, error);
    }
}

async function testCreateInvoice() {
    const bookingId = document.getElementById('booking-id').value;
    const amount = document.getElementById('amount').value;
    
    try {
        const response = await fetch('/api/invoices', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                booking_id: bookingId,
                amount: amount
            })
        });
        
        const data = await response.json();
        showResult('➕ Tạo hóa đơn:', data);
    } catch (error) {
        showResult('❌ Lỗi tạo hóa đơn: ' + error.message, error);
    }
}

async function testGeneratePDF() {
    const bookingId = document.getElementById('booking-id').value;
    
    try {
        const response = await fetch(`/api/invoices/booking/${bookingId}/pdf`, {
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        showResult('📄 Tạo PDF:', data);
        
        if (data.success && data.data.download_url) {
            // Mở PDF trong tab mới
            window.open(data.data.download_url, '_blank');
        }
    } catch (error) {
        showResult('❌ Lỗi tạo PDF: ' + error.message, error);
    }
}

async function testDownloadPDF() {
    const bookingId = document.getElementById('booking-id').value;
    
    try {
        const response = await fetch(`/api/invoices/booking/${bookingId}/download`, {
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Accept': 'application/pdf'
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `invoice_${bookingId}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            showResult('⬇️ Tải PDF thành công!', { message: 'File PDF đã được tải xuống' });
        } else {
            const data = await response.json();
            showResult('❌ Lỗi tải PDF:', data);
        }
    } catch (error) {
        showResult('❌ Lỗi tải PDF: ' + error.message, error);
    }
}

function showResult(title, data) {
    const display = document.getElementById('response-display');
    display.innerHTML = `
        <h6>${title}</h6>
        <pre class="mt-2">${JSON.stringify(data, null, 2)}</pre>
    `;
}
</script>
@endsection
