<?php

namespace PublicApi\LearningSystem\Infrastructure\DataProvider;

use Armor\Infrastructure\Provider\LanguageSessionProvider;
use BlueDot\BlueDot;
use LearningSystem\Library\DataProviderInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\LearningSystem\Repository\WordDataRepository;
use PublicApi\LearningSystem\Infrastructure\DataProvider\Word\ProvidedWordDataCollection;
use PublicApiBundle\Entity\LearningLesson;

class InitialWordDataProvider extends BaseBlueDotRepository implements DataProviderInterface
{
    /**
     * @var LanguageSessionProvider $languageSessionProvider
     */
    private $languageSessionProvider;
    /**
     * @var WordDataRepository $wordDataRepository
     */
    private $wordDataRepository;
    /**
     * WordDataProvider constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     * @param LanguageSessionProvider $languageSessionProvider
     * @param WordDataRepository $wordDataRepository
     */
    public function __construct(
        BlueDot $blueDot,
        string $apiName,
        LanguageSessionProvider $languageSessionProvider,
        WordDataRepository $wordDataRepository
    ) {
        parent::__construct($blueDot, $apiName);

        $this->wordDataRepository = $wordDataRepository;
        $this->languageSessionProvider = $languageSessionProvider;
    }
    /**
     * @inheritdoc
     */
    public function getData(LearningLesson $learningLesson, array $rules): ProvidedDataInterface
    {
        $language = $this->languageSessionProvider->getLanguage();

        $initialWords = $this->wordDataRepository->getWordsFromLessons(
            $learningLesson,
            $language,
            $rules['word_level']
        );

        $wordNumber = $this->getWordNumber($rules['word_number'], count($initialWords));

        $wordIds = null;
        if (empty($initialWords)) {
            $wordIds = $this->wordDataRepository->getWordsIds(
                $language,
                $rules['word_level']
            );
        } else if (!empty($initialWords)) {
            $wordIds = $this->wordDataRepository->getWordsIdsWithExcludedLesson(
                $language,
                $learningLesson->getLesson(),
                $rules['word_level']
            );
        }

        $restOfTheWords = $this->wordDataRepository->getRestOfTheWords(
            $wordNumber,
            $wordIds,
            $rules['word_level'],
            $language
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