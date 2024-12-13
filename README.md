# UddoktaPay Laravel SDK

![UddoktaPay Logo](https://uddoktapay.com/assets/images/logo.png)

The UddoktaPay Laravel SDK allows you to seamlessly integrate the UddoktaPay payment gateway into your Laravel applications.

---

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
  - [Initializing the SDK](#initializing-the-sdk)
  - [Initializing a Payment](#initializing-a-payment)
  - [Verifying a Payment](#verifying-a-payment)
  - [Handling IPN Notifications](#handling-ipn-notifications-optional)
  - [Processing Refunds](#processing-refunds-optional)
- [Routes](#routes)
- [Notes](#notes)

---

## Installation

Install the UddoktaPay Laravel SDK using Composer:

```bash
composer require uddoktapay/laravel-sdk
```

---

## Usage

### Initializing the SDK

Add your UddoktaPay API credentials to the `.env` file:

```env
UDDOKTAPAY_API_KEY=your_api_key
UDDOKTAPAY_API_URL=https://sandbox.uddoktapay.com/api/checkout-v2
```

Use the following code to initialize the SDK:

```php
use UddoktaPay\LaravelSDK\UddoktaPay;

$uddoktapay = UddoktaPay::make(env('UDDOKTAPAY_API_KEY'), env('UDDOKTAPAY_API_URL'));
```

---

### Initializing a Payment

To initiate a payment:

```php
use UddoktaPay\LaravelSDK\Requests\CheckoutRequest;

try {
    $checkoutRequest = CheckoutRequest::make()
        ->setFullName('John Doe')
        ->setEmail('john@doe.com')
        ->setAmount('10')
        ->addMetadata('order_id', '12345')
        ->setRedirectUrl(route('uddoktapay.verify'))
        ->setCancelUrl(route('uddoktapay.cancel'))
        ->setWebhookUrl(route('uddoktapay.ipn'));

    $response = $uddoktapay->checkout($checkoutRequest);

    if ($response->failed()) {
        dd($response->message());
    }

    return redirect($response->paymentURL());
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("Initialization Error: " . $e->getMessage());
}
```

---

### Verifying a Payment

After the payment is complete, verify it using the `VerifyResponse` class to understand the structure and available methods for processing the response:

```php
try {
    $response = $uddoktapay->verify($request);

    if ($response->success()) {
        // Handle successful status
        dd($response->toArray()); // Handle success
    } elseif ($response->pending()) {
        // Handle pending status
    } elseif ($response->failed()) {
        // Handle failure
    }
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("Verification Error: " . $e->getMessage());
}
```

---

### Handling IPN Notifications (Optional)

To handle Instant Payment Notifications (IPN):

```php
try {
    $response = $uddoktapay->ipn($request);

    if ($response->success()) {
        // Handle successful IPN
    } elseif ($response->pending()) {
        // Handle pending IPN
    } elseif ($response->failed()) {
        // Handle failed IPN
    }
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("IPN Error: " . $e->getMessage());
}
```

---

### Processing Refunds (Optional)

To process a refund:

```php
use UddoktaPay\LaravelSDK\Requests\RefundRequest;

try {
    $refundRequest = RefundRequest::make()
        ->setAmount('10')
        ->setTransactionId('12345')
        ->setPaymentMethod('bkash')
        ->setProductName('Sample Product')
        ->setReason('Customer Request');

    $response = $uddoktapay->refund($refundRequest);

    if ($response->success()) {
        // Handle refund success
    } elseif ($response->failed()) {
        // Handle refund failure
    }
} catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
    dd("Refund Error: " . $e->getMessage());
}
```

---

## Routes

Add the following routes to your `web.php` file:

```php
use App\Http\Controllers\UddoktaPayController;

Route::get('/checkout', [UddoktaPayController::class, 'checkout'])->name('uddoktapay.checkout');
Route::get('/verify', [UddoktaPayController::class, 'verify'])->name('uddoktapay.verify');
Route::get('/cancel', [UddoktaPayController::class, 'cancel'])->name('uddoktapay.cancel');
Route::post('/ipn', [UddoktaPayController::class, 'ipn'])->name('uddoktapay.ipn');
Route::post('/refund', [UddoktaPayController::class, 'refund'])->name('uddoktapay.refund');
```

---

## Notes

- Replace placeholders like `your_api_key` with actual credentials.
- Use appropriate routes for success, cancel, and IPN handling.
- Always wrap SDK calls with `try-catch` to handle errors effectively.

---

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).