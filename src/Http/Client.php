<?php

namespace UddoktaPay\LaravelSDK\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use UddoktaPay\LaravelSDK\Concerns\Normalize;
use UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException;
use UddoktaPay\LaravelSDK\Requests\CheckoutRequest;
use UddoktaPay\LaravelSDK\Requests\RefundRequest;
use UddoktaPay\LaravelSDK\Responses\CheckoutResponse;
use UddoktaPay\LaravelSDK\Responses\RefundResponse;
use UddoktaPay\LaravelSDK\Responses\VerifyResponse;

class Client
{
    use Normalize;

    private PendingRequest $client;

    private string $apiKey;

    private string $apiURL;

    public function __construct(string $apiKey, string $apiURL)
    {
        $this->apiKey = $apiKey;
        $this->apiURL = $this->normalizeURL($apiURL);

        $this->client = Http::baseUrl($this->apiURL)
            ->withHeaders([
                'RT-UDDOKTAPAY-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(30)
            ->acceptJson();
    }

    /**
     * Initialize payment checkout
     *
     * @param  CheckoutRequest  $request  Request Data
     * @return CheckoutResponse Response data
     *
     * @throws UddoktaPayException
     */
    public function createPayment(CheckoutRequest $request): CheckoutResponse
    {
        try {
            $response = $this->client->post('/api/checkout-v2', $request->build());

            if (! $response->successful()) {
                throw new UddoktaPayException('UddoktaPay API Error: '.$response->body());
            }

            return new CheckoutResponse($response->json());
        } catch (\Exception $e) {
            throw new UddoktaPayException('UddoktaPay Error: '.$e->getMessage());
        }
    }

    /**
     * Initialize global payment checkout
     *
     * @param  CheckoutRequest  $request  Request Data
     * @return CheckoutResponse Response data
     *
     * @throws UddoktaPayException
     */
    public function createGlobalPayment(CheckoutRequest $request): CheckoutResponse
    {
        try {
            $response = $this->client->post('/api/checkout-v2/global', $request->build());

            if (! $response->successful()) {
                throw new UddoktaPayException('UddoktaPay API Error: '.$response->body());
            }

            return new CheckoutResponse($response->json());
        } catch (\Exception $e) {
            throw new UddoktaPayException('UddoktaPay Error: '.$e->getMessage());
        }
    }

    /**
     * Verify payment status
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request
     * @return VerifyResponse Response data
     *
     * @throws UddoktaPayException
     */
    public function verifyPayment(Request $request): VerifyResponse
    {
        try {
            if (! $request->has('invoice_id')) {
                throw new UddoktaPayException('Missing Invoice ID in request.');
            }

            $response = $this->client->post('/api/verify-payment', [
                'invoice_id' => $request->invoice_id,
            ]);

            if (! $response->successful()) {
                throw new UddoktaPayException('UddoktaPay Verification Error: '.$response->body());
            }

            return new VerifyResponse($response->json());
        } catch (\Exception $e) {
            throw new UddoktaPayException('UddoktaPay Verification Error: '.$e->getMessage());
        }
    }

    /**
     * Validate IPN request
     *
     * @param  \Illuminate\Http\Request  $request  The incoming webhook request
     * @return VerifyResponse Response data
     *
     * @throws UddoktaPayException If validation fails
     */
    public function validateIPN(Request $request): VerifyResponse
    {
        try {
            // Check if the required headers are present
            if (! $request->hasHeader('RT-UDDOKTAPAY-API-KEY')) {
                throw new UddoktaPayException('Missing UddoktaPay API Key in headers');
            }

            // Validate API key
            $receivedApiKey = $request->header('RT-UDDOKTAPAY-API-KEY');
            if ($receivedApiKey !== $this->apiKey) {
                throw new UddoktaPayException('Invalid API Key');
            }

            // Get the raw post data
            $rawPost = $request->getContent();
            if (empty($rawPost)) {
                throw new UddoktaPayException('Empty POST data');
            }

            // Decode JSON payload
            $ipnData = json_decode($rawPost, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new UddoktaPayException('Invalid JSON data: '.json_last_error_msg());
            }

            // Validate status
            if (! in_array($ipnData['status'], ['COMPLETED', 'PENDING', 'FAILED'])) {
                throw new UddoktaPayException('Invalid payment status');
            }

            return $this->verifyPayment($ipnData['invoice_id']);
        } catch (\Exception $e) {
            throw new UddoktaPayException('IPN Validation Error: '.$e->getMessage());
        }
    }

    /**
     * Refund Payment
     *
     * @param  RefundRequest  $request  Request Data
     * @return RefundResponse Response data
     *
     * @throws UddoktaPayException
     */
    public function refundPayment(RefundRequest $request): RefundResponse
    {
        try {
            $response = $this->client->post('/api/refund-payment', $request->build());

            if (! $response->successful()) {
                throw new UddoktaPayException('UddoktaPay Refund Error: '.$response->body());
            }

            return new RefundResponse($response->json());
        } catch (\Exception $e) {
            throw new UddoktaPayException('UddoktaPay Refund Error: '.$e->getMessage());
        }
    }
}
