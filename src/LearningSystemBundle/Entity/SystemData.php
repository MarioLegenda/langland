<?php

namespace LearningSystemBundle\Entity;

class SystemData
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $systemHeadId
     */
    private $systemHeadId;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function getSystemHeadId(): int
    {
        return $this->systemHeadId;
    }
    /**
     * @param int $systemHeadId
     * @return SystemData
     */
    public function setSystemHeadId(int $systemHeadId): SystemData
    {
        $this->systemHeadId = $systemHeadId;
    }
}