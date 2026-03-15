<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to ABA PayWay...</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; background: #f8fafc; }
        .loader { text-align: center; }
        .spinner { border: 4px solid rgba(0,0,0,0.1); width: 36px; height: 36px; border-radius: 50%; border-left-color: #0f172a; animation: spin 1s linear infinite; margin: 0 auto 1rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body onload="document.getElementById('aba-form').submit();">
    <div class="loader">
        <div class="spinner"></div>
        <p>Redirecting you to secure payment gateway...</p>
        
        <form action="{{ $apiUrl }}" method="POST" id="aba-form" style="display: none;">
            <input type="hidden" name="hash" value="{{ $hash }}">
            <input type="hidden" name="tran_id" value="{{ $tranId }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="firstname" value="{{ $firstName }}">
            <input type="hidden" name="lastname" value="{{ $lastName }}">
            <input type="hidden" name="phone" value="{{ $phone }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="items" value="{{ $items }}">
            <input type="hidden" name="return_url" value="{{ $returnUrl }}">
            <input type="hidden" name="cancel_url" value="{{ $cancelUrl }}">
            <input type="hidden" name="continue_success_url" value="{{ $continueSuccessUrl }}">
            <input type="hidden" name="req_time" value="{{ $reqTime }}">
            <input type="hidden" name="merchant_id" value="{{ $merchantId }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="payment_option" value="{{ $paymentOption }}">
        </form>
    </div>
</body>
</html>
