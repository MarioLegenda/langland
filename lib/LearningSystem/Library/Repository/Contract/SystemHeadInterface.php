<?php

namespace LearningSystem\Library\Repository\Contract;

interface SystemHeadInterface
{
    /**
     * @param int $id
     * @return SystemHeadInterface
     */
    public function setId(int $id): SystemHeadInterface;
    /**
     * @return int
     */
    public function getId(): int;
}