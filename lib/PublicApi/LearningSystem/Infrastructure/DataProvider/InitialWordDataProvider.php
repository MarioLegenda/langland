<?php

namespace PublicApi\LearningSystem\Infrastructure\DataProvider;

use BlueDot\BlueDot;
use LearningSystem\Library\DataProviderInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\Repository\WordDataRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApi\LearningSystem\Infrastructure\DataProvider\Word\ProvidedWordDataCollection;
use PublicApiBundle\Entity\LearningLesson;

class InitialWordDataProvider extends BaseBlueDotRepository implements DataProviderInterface
{
    /**
     * @var LanguageProvider $languageProvider
     */
    private $languageProvider;
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var WordDataRepository $wordDataRepository
     */
    private $wordDataRepository;
    /**
     * WordDataProvider constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     * @param LanguageProvider $languageProvider
     * @param LearningUserProvider $learningUserProvider
     * @param WordDataRepository $wordDataRepository
     */
    public function __construct(
        BlueDot $blueDot,
        string $apiName,
        LanguageProvider $languageProvider,
        LearningUserProvider $learningUserProvider,
        WordDataRepository $wordDataRepository
    ) {
        parent::__construct($blueDot, $apiName);

        $this->languageProvider = $languageProvider;
        $this->learningUserProvider = $learningUserProvider;
        $this->wordDataRepository = $wordDataRepository;
    }
    /**
     * @inheritdoc
     */
    public function getData(LearningLesson $learningLesson, array $rules): ProvidedDataInterface
    {
        $initialWords = $this->wordDataRepository->getWordsFromLessons(
            $learningLesson,
            $this->languageProvider->getLanguage(),
            $rules['word_level']
        );

        $wordNumber = $this->getWordNumber($rules['word_number'], count($initialWords));

        $wordIds = null;
        if (empty($initialWords)) {
            $wordIds = $this->wordDataRepository->getWordsIds(
                $this->languageProvider->getLanguage(),
                $rules['word_level']
            );
        } else if (!empty($initialWords)) {
            $wordIds = $this->wordDataRepository->getWordsIdsWithExcludedLesson(
                $this->languageProvider->getLanguage(),
                $learningLesson->getLessonObject()->getId(),
                $rules['word_level']
            );
        }

        $restOfTheWords = $this->wordDataRepository->getRestOfTheWords(
            $wordNumber,
            $wordIds,
            $rules['word_level'],
            $this->languageProvider->getLanguage()
        );

        $finalWords = array_merge($initialWords, $restOfTheWords);

        if ($rules['word_number'] !== count($finalWords)) {
            $message = sprintf(
                'Provided word number does not qualify. There has to be %d words. %d given',
                $rules['word_number'],
                count($finalWords)
            );

            throw new \RuntimeException($message);
        }

        return new ProvidedWordDataCollection($finalWords);
    }
    /**
     * @param int $rulesWordNumber
     * @param int $wordFromLessonsCount
     * @return int
     */
    private function getWordNumber(int $rulesWordNumber, int $wordFromLessonsCount): int
    {
        return $rulesWordNumber - $wordFromLessonsCount;
    }
}