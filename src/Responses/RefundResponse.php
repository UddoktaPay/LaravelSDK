<?php

namespace UddoktaPay\LaravelSDK\Responses;

class RefundResponse
{
    private array $response = [];

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function status(): bool
    {
        return $this->response['status'];
    }

    public function success(): bool
    {
        return $this->status() === true;
    }

    public function failed(): bool
    {
        return $this->status() === false;
    }

    public function message(): ?string
    {
        return $this->response['message'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
