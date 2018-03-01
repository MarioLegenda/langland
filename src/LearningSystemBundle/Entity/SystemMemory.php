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
}