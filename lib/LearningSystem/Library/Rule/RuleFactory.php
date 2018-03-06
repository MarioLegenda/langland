<?php

namespace LearningSystem\Library\Rule;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;

class RuleFactory
{
    /**
     * @var RuleFactory $instance
     */
    private static $instance;
    /**
     * @return RuleFactory
     */
    private static function instance(): RuleFactory
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * @param string $ruleType
     * @param array $metadata
     * @return RuleDataInterface
     */
    public static function create(string $ruleType, array $metadata): RuleDataInterface
    {
        if ($ruleType === BasicGameType::getName()) {
            return RuleFactory::instance()->createBasicRuleFromMetadata($metadata);
        }
    }
    /**
     * @param array $metadata
     * @return RuleDataInterface
     */
    private function createBasicRuleFromMetadata(array $metadata): RuleDataInterface
    {
        $speakingLanguagesType = $metadata['speaking_languages'];
        $wordNumber = 20;
        $wordLevel = 1;

        // rules go here

        $rule = Rule::createRule([
            'word_number' => $wordNumber,
            'word_level' => $wordLevel,
        ]);

        return $rule;
    }
}