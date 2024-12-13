<?php

namespace UddoktaPay\LaravelSDK\Requests;

use UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException;

class CheckoutRequest
{
    private array $data = [];

    private array $metadata = [];

    public function setFullName(string $fullName): self
    {
        $this->data['full_name'] = $fullName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->data['email'] = $email;

        return $this;
    }

    public function setAmount($amount): self
    {
        $this->data['amount'] = $amount;

        return $this;
    }

    public function setRedirectUrl(string $url): self
    {
        $this->data['redirect_url'] = $url;

        return $this;
    }

    public function setCancelUrl(string $url): self
    {
        $this->data['cancel_url'] = $url;

        return $this;
    }

    public function setWebhookUrl(string $url): self
    {
        $this->data['webhook_url'] = $url;

        return $this;
    }

    public function addMetadata(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;

        return $this;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Build the request data array
     *
     * @throws UddoktaPayException
     */
    public function build(): array
    {
        $this->data['return_type'] = 'get';

        // Add metadata if exists
        if (! empty($this->metadata)) {
            $this->data['metadata'] = $this->metadata;
        }

        // Required fields validation
        $requiredFields = ['full_name', 'email', 'amount', 'metadata', 'redirect_url', 'cancel_url'];
        foreach ($requiredFields as $field) {
            if (! isset($this->data[$field])) {
                throw new UddoktaPayException("Missing required field: {$field}");
            }
        }

        return $this->data;
    }

    /**
     * Static constructor for fluent usage
     */
    public static function make(): self
    {
        return new self;
    }
}
