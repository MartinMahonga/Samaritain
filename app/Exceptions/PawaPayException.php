<?php

namespace App\Exceptions;

use Exception;

class PawaPayException extends Exception
{
    /**
     * The HTTP status code from the pawaPay response, if any.
     */
    protected ?int $statusCode;

    /**
     * The raw response body from pawaPay, if any.
     */
    protected ?string $responseBody;

    /**
     * Create a new pawaPay exception.
     */
    public function __construct(
        string $message = '',
        ?int $statusCode = null,
        ?string $responseBody = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);

        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    /**
     * Get the HTTP status code from pawaPay, if available.
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * Get the raw response body from pawaPay, if available.
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}
