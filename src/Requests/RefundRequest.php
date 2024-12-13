<?php

namespace UddoktaPay\LaravelSDK\Requests;

use UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException;

class RefundRequest
{
    private array $data = [];

    public function setTransactionId(string $transactionId): self
    {
        $this->data['transaction_id'] = $transactionId;

        return $this;
    }

    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->data['payment_method'] = $paymentMethod;

        return $this;
    }

    public function setAmount($amount): self
    {
        $this->data['amount'] = $amount;

        return $this;
    }

    public function setProductName(string $productName): self
    {
        $this->data['product_name'] = $productName;

        return $this;
    }

    public function setReason(string $reason): self
    {
        $this->data['reason'] = $reason;

        return $this;
    }

    /**
     * Build the request data array
     *
     * @throws UddoktaPayException
     */
    public function build(): array
    {
        // Required fields validation
        $requiredFields = ['transaction_id', 'payment_method', 'amount', 'product_name', 'reason'];
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
