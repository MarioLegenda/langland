<?php

namespace LearningSystem\Library\Rule;

class Rule implements RuleDataInterface
{
    /**
     * @var int $wordNumber
     */
    private $wordNumber;
    /**
     * @var int $wordLevel
     */
    private $wordLevel;
    /**
     * Rule constructor.
     *
     * Cannot be used. 'Rule' can only be constructed with Rule::createRule(array $metadata)
     * static method.
     */
    private function __construct() {}
    /**
     * @inheritdoc
     */
    public function setWordNumber(int $wordNumber): RuleDataInterface
    {
        $this->wordNumber = $wordNumber;

        return $this;
    }
    /**
     * @inheritdoc
     */
    public function getWordNumber(): ?int
    {
        return $this->wordNumber;
    }
    /**
     * @inheritdoc
     */
    public function getWordLevel(): ?int
    {
        return $this->wordLevel;
    }
    /**
     * @inheritdoc
     */
    public function setWordLevel(int $wordLevel): RuleDataInterface
    {
        $this->wordLevel = $wordLevel;

        return $this;
    }
    /**
     * @inheritdoc
     */
    public static function createRule(array $metadata): RuleDataInterface
    {
        $metadataSignatures = [
            'word_number' => 'setWordNumber',
            'word_level' => 'setWordLevel',
        ];

        if (empty($metadata)) {
            $message = sprintf(
                'Rule has to be created with one of these metadata signatures \'%s\'',
                implode(', ', $metadataSignatures)
            );

            throw new \RuntimeException($message);
        }

        $rule = new Rule();

        foreach ($metadataSignatures as $metadataSignature => $methodName) {
            if (array_key_exists($metadataSignature, $metadata)) {
                $rule->{$methodName}($metadata[$metadataSignature]);
            }
        }

        return $rule;
    }
}