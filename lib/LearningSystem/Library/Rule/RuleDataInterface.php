<?php

namespace LearningSystem\Library\Rule;

interface RuleDataInterface
{
    /**
     * @param int $wordNumber
     * @return RuleDataInterface
     */
    public function setWordNumber(int $wordNumber): RuleDataInterface;
    /**
     * @return int
     */
    public function getWordNumber(): int;
}