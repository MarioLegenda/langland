<?php

namespace PublicApi\LearningSystem\DataCollector;

use LearningSystem\Library\DataCollectorInterface;
use LearningSystem\Library\Infrastructure\SystemHeadCollection;
use PublicApi\Infrastructure\Repository\WordRepository;
use PublicApi\LearningSystem\RuleResolver;
use PublicApi\Lesson\Repository\LessonRepository;

class DataCollector implements DataCollectorInterface
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
    /**
     * @var RuleResolver $ruleResolver
     */
    private $ruleResolver;
    /**
     * DataCollector constructor.
     * @param LessonRepository $lessonRepository
     * @param WordRepository $wordRepository
     * @param RuleResolver $ruleResolver
     */
    public function __construct(
        LessonRepository $lessonRepository,
        WordRepository $wordRepository,
        RuleResolver $ruleResolver
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->wordRepository = $wordRepository;
        $this->ruleResolver = $ruleResolver;
    }
    /**
     * @inheritdoc
     */
    public function getCollectedData(): array
    {

    }
}