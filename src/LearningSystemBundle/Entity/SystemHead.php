<?php

namespace LearningSystemBundle\Entity;

use LearningSystem\Library\Repository\Contract\SystemHeadInterface;

class SystemHead implements SystemHeadInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @inheritdoc
     */
    public function setId(int $id): SystemHeadInterface
    {
        $this->id = $id;
    }
}