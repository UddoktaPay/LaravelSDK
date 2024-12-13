<?php

namespace UddoktaPay\LaravelSDK;

use Illuminate\Http\Request;
use UddoktaPay\LaravelSDK\Http\Client;
use UddoktaPay\LaravelSDK\Requests\CheckoutRequest;
use UddoktaPay\LaravelSDK\Requests\RefundRequest;
use UddoktaPay\LaravelSDK\Responses\CheckoutResponse;
use UddoktaPay\LaravelSDK\Responses\RefundResponse;
use UddoktaPay\LaravelSDK\Responses\VerifyResponse;

class UddoktaPay
{
    private Client $client;

    public static function make(string $apiKey, string $apiURL): self
    {
        $instance = new self;
        $instance->client = new Client($apiKey, $apiURL);

        return $instance;
    }

    public function checkout(CheckoutRequest $request): CheckoutResponse
    {
        return $this->client->createPayment($request);
    }

    public function verify(string $invoiceId): VerifyResponse
    {
        return $this->client->verifyPayment($invoiceId);
    }

    public function ipn(Request $request): VerifyResponse
    {
        return $this->client->validateIPN($request);
    }

    public function refund(RefundRequest $request): RefundResponse
    {
        return $this->client->refundPayment($request);
    }
}
