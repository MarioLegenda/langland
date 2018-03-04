<?php

namespace LearningSystemBundle\Entity;

class SystemMemory
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $systemNeuronId
     */
    private $systemNeuronId;
    /**
     * @var int $externalDataCorrelationId
     */
    private $externalDataCorrelationId;
    /**
     * @var string $data
     */
    private $data;
    /**
     * @param int $id
     * @return SystemMemory
     */
    public function setId(int $id): SystemMemory
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function getSystemNeuronId(): int
    {
        return $this->systemNeuronId;
    }
    /**
     * @param int $systemNeuronId
     * @return SystemMemory
     */
    public function setSystemNeuronId(int $systemNeuronId): SystemMemory
    {
        $this->systemNeuronId = $systemNeuronId;
    }
    /**
     * @return int
     */
    public function getExternalDataCorrelationId(): int
    {
        return $this->externalDataCorrelationId;
    }
    /**
     * @param int $externalDataCorrelationId
     */
    public function setExternalDataCorrelationId(int $externalDataCorrelationId): void
    {
        $this->externalDataCorrelationId = $externalDataCorrelationId;
    }
    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }
}