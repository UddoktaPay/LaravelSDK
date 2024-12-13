<?php

namespace UddoktaPay\LaravelSDK\Concerns;

use UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException;

trait Normalize
{
    /**
     * Normalize URL to get base URL without API path
     */
    public function build(string $url): string
    {
        // Parse the URL
        $parsedUrl = parse_url($url);

        if (! isset($parsedUrl['scheme']) || ! isset($parsedUrl['host'])) {
            throw new UddoktaPayException('Invalid URL format');
        }

        // Build base URL with scheme and host
        $baseUrl = $parsedUrl['scheme'].'://'.$parsedUrl['host'];

        // Add port if it exists
        if (isset($parsedUrl['port'])) {
            $baseUrl .= ':'.$parsedUrl['port'];
        }

        return $baseUrl;
    }
}
