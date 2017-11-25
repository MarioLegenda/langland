<?php

namespace Library\Exception;

class HttpResponseStatus implements StatusInterface
{
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var array $data
     */
    private $data = [];
    /**
     * @var string $message
     */
    private $message;
    /**
     * @var array $additionalData
     */
    private $additionalData = [];
    /**
     * HttpResponseStatus constructor.
     * @param int $statusCode
     * @param array $data
     * @param string|null $message
     * @param array|null $additionalData
     */
    public function __construct
    (
        int $statusCode,
        array $data = [],
        string $message = null,
        array $additionalData = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->message = $message;
        $this->additionalData = $additionalData;
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * @return null|array
     */
    public function getData(): ?array
    {
        return $this->data;
    }
    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    /**
     * @return null|array
     */
    public function getAdditionalData(): ?array
    {
        return $this->additionalData;
    }
}