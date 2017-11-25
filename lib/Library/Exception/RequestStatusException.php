<?php

namespace Library\Exception;

use Throwable;

class RequestStatusException extends BaseStatusException
{
    /**
     * RequestStatusException constructor.
     * @param string $message
     * @param StatusInterface $status
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        StatusInterface $status,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->status = $status;
    }
    /**
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface
    {
        return $this->status;
    }
}