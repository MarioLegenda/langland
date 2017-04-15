<?php

namespace API\SharedDataBundle\Repository;

class ResultResolver
{
    /**
     * @var string $status
     */
    private $status;
    /**
     * @var array $data
     */
    private $data;
    /**
     * ResultResolver constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->status = (empty($data)) ? Status::FAILURE : Status::SUCCESS;

        $this->data = $data;
    }
    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }
    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }
}