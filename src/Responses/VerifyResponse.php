<?php

namespace UddoktaPay\LaravelSDK\Responses;

class VerifyResponse
{
    private array $response = [];

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function status(): ?string
    {
        return strtolower($this->response['status']) ?? null;
    }

    public function success(): bool
    {
        return $this->status() === 'completed';
    }

    public function pending(): bool
    {
        return $this->status() === 'pending';
    }

    public function failed(): bool
    {
        return $this->status() === 'error';
    }

    public function fullName(): ?string
    {
        return $this->response['full_name'] ?? null;
    }

    public function email(): ?string
    {
        return $this->response['email'] ?? null;
    }

    public function amount(): ?string
    {
        return (string) $this->response['amount'] ?? null;
    }

    public function fee(): ?string
    {
        return (string) $this->response['fee'] ?? null;
    }

    public function chargedAmount(): ?string
    {
        return (string) $this->response['charged_amount'] ?? null;
    }

    public function invoiceId(): ?string
    {
        return $this->response['invoice_id'] ?? null;
    }

    public function metadata(): array
    {
        return $this->response['metadata'] ?? [];
    }

    public function paymentMethod(): ?string
    {
        return $this->response['payment_method'] ?? null;
    }

    public function senderNumber(): ?string
    {
        return $this->response['sender_number'] ?? null;
    }

    public function transactionId(): ?string
    {
        return $this->response['transaction_id'] ?? null;
    }

    public function date(): ?string
    {
        return $this->response['date'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
