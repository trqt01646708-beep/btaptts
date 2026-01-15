<?php

return [
    'vnp_TmnCode' => env('VNPAY_TMN_CODE', 'ZD0LS8VQ'),
    'vnp_HashSecret' => env('VNPAY_HASH_SECRET', '1OI5NJGWSKBJ8QDS1K3H0W9DXXE0TJ2C'),
    'vnp_Url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_ReturnUrl' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8000/thanh-toan/vnpay-return'),
    'vnp_apiUrl' => env('VNPAY_API_URL', 'http://sandbox.vnpayment.vn/merchant_webapi/merchant.html'),
];
