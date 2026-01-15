# Hướng dẫn Test VNPay Sandbox

## ✅ Đã cấu hình

### 1. Thông tin VNPay Sandbox

-   **Terminal ID (vnp_TmnCode)**: YG2ME2IM
-   **Secret Key (vnp_HashSecret)**: 4N44RDK1R66J4QNCR9XUU8DEL476X8UD
-   **VNPay URL**: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
-   **Return URL**: http://127.0.0.1:8000/thanh-toan/vnpay-return

### 2. Files đã tạo/cập nhật

✓ `config/vnpay.php` - File config VNPay
✓ `.env` - Thêm thông tin VNPay
✓ `app/Http/Controllers/CheckoutController.php` - Thêm methods:

-   `createVnpayPayment()` - Tạo link thanh toán
-   `vnpayReturn()` - Xử lý callback từ VNPay
    ✓ `routes/web.php` - Thêm route vnpay-return
    ✓ `resources/views/frontend/checkout/index.blade.php` - Thêm option VNPay
    ✓ Database migration - Thêm trường vnpay_transaction_id và paid_at

## 🧪 Cách Test

### Bước 1: Thêm sản phẩm vào giỏ hàng

1. Truy cập: http://127.0.0.1:8000/san-pham
2. Click "Thêm vào giỏ hàng" một vài sản phẩm

### Bước 2: Thanh toán

1. Vào giỏ hàng: http://127.0.0.1:8000/gio-hang
2. Click "Thanh toán"
3. Điền thông tin khách hàng
4. Chọn **"Thanh toán qua VNPay"**
5. Click "Đặt hàng"

### Bước 3: Thanh toán trên VNPay Sandbox

Bạn sẽ được chuyển đến trang VNPay sandbox. Chọn phương thức:

#### Option 1: Thẻ ATM nội địa

-   **Ngân hàng**: NCB
-   **Số thẻ**: 9704198526191432198
-   **Tên chủ thẻ**: NGUYEN VAN A
-   **Ngày phát hành**: 07/15
-   **Mật khẩu OTP**: 123456

#### Option 2: Thẻ tín dụng quốc tế

-   **Số thẻ**: 9704060000000000018
-   **Tên chủ thẻ**: NGUYEN VAN A
-   **Ngày hết hạn**: 03/07
-   **CVV**: 123

#### Option 3: Quét mã QR

-   Chọn "Thanh toán bằng QR Code"
-   Quét mã QR bằng app ngân hàng (test)

### Bước 4: Xác nhận thanh toán

1. Nhập mật khẩu OTP: **123456**
2. Click "Tiếp tục"
3. Bạn sẽ được redirect về: http://127.0.0.1:8000/thanh-toan/vnpay-return
4. Nếu thành công → chuyển đến trang "Đặt hàng thành công"

## 📋 Kiểm tra kết quả

### Trong Database

```sql
SELECT
    order_number,
    payment_method,
    payment_status,
    vnpay_transaction_id,
    paid_at,
    total
FROM orders
ORDER BY created_at DESC
LIMIT 5;
```

**Kết quả mong đợi:**

-   `payment_method`: vnpay
-   `payment_status`: paid (nếu thanh toán thành công)
-   `vnpay_transaction_id`: có giá trị (mã giao dịch VNPay)
-   `paid_at`: timestamp thanh toán

### Flow xử lý

```
1. User chọn VNPay → store() method
2. Tạo đơn hàng → payment_status = 'unpaid'
3. Tạo link VNPay → createVnpayPayment()
4. Redirect đến VNPay sandbox
5. User nhập thông tin thẻ
6. VNPay callback → vnpayReturn()
7. Verify checksum
8. Cập nhật payment_status = 'paid'
9. Lưu vnpay_transaction_id
10. Redirect đến trang success
```

## 🔍 Debug

### Xem log lỗi (nếu có)

```bash
tail -f storage/logs/laravel.log
```

### Test tham số VNPay

Kiểm tra URL được tạo có đúng format:

```
https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?
vnp_Version=2.1.0&
vnp_TmnCode=YG2ME2IM&
vnp_Amount=500000000&  (= 5,000,000đ * 100)
vnp_Command=pay&
vnp_CreateDate=20260115123045&
vnp_CurrCode=VND&
vnp_IpAddr=127.0.0.1&
vnp_Locale=vn&
vnp_OrderInfo=Thanh%20to%C3%A1n%20%C4%91%C6%A1n%20h%C3%A0ng%20DH...&
vnp_OrderType=billpayment&
vnp_ReturnUrl=http%3A%2F%2F127.0.0.1%3A8000%2Fthanh-toan%2Fvnpay-return&
vnp_TxnRef=DH20260115ABC123&
vnp_SecureHash=...
```

### Lỗi thường gặp

**1. Checksum không hợp lệ**

-   Kiểm tra `vnp_HashSecret` trong `.env`
-   Verify thứ tự sort parameters

**2. Return URL không được gọi**

-   Kiểm tra route `checkout.vnpay.return` có tồn tại
-   Verify `VNPAY_RETURN_URL` trong `.env`

**3. Order không được cập nhật**

-   Check database có trường `vnpay_transaction_id` và `paid_at` chưa
-   Chạy lại migration nếu cần: `php artisan migrate`

## 📞 VNPay Sandbox Support

-   Portal: https://sandbox.vnpayment.vn/
-   Docs: https://sandbox.vnpayment.vn/apis/docs/

## ✨ Tính năng đã hoàn thiện

-   ✅ Tích hợp VNPay sandbox
-   ✅ Hỗ trợ ATM/Visa/MasterCard/QR
-   ✅ Verify checksum bảo mật
-   ✅ Cập nhật trạng thái đơn hàng tự động
-   ✅ Lưu mã giao dịch VNPay
-   ✅ Gửi email xác nhận sau khi thanh toán thành công

## 🚀 Lưu ý khi deploy Production

1. Đổi từ sandbox sang production URL
2. Lấy Terminal ID và Secret Key thật từ VNPay
3. Cập nhật Return URL với domain thật
4. Enable HTTPS (VNPay yêu cầu)
5. Kiểm tra whitelist IP với VNPay
