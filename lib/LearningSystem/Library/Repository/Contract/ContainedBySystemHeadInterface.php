<?php

namespace LearningSystem\Library\Repository\Contract;

interface ContainedBySystemHeadInterface
{
    /**
     * @param int $systemHeadId
     * @return ContainedBySystemHeadInterface
     */
    public function setSystemHeadId(int $systemHeadId): ContainedBySystemHeadInterface;
    /**
     * @return int
     */
    public function getSystemHeadId(): int;
}