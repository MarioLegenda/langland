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
     * @return int|null
     */
    public function getWordNumber(): ?int;
    /**
     * @param int $wordLevel
     * @return RuleDataInterface
     */
    public function setWordLevel(int $wordLevel): RuleDataInterface;
    /**
     * @return int|null
     */
    public function getWordLevel(): ?int;
}