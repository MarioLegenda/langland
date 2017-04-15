<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;
use BlueDot\Entity\Promise;
use BlueDot\Entity\PromiseInterface;

class SentenceRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * SentenceRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $this->blueDot = $blueDot;
    }
    /**
     * @param string $internalName
     * @param int $lessonId
     * @return PromiseInterface
     */
    public function findSentenceByInternalName(string $internalName, int $lessonId) : PromiseInterface
    {
        return $this->blueDot->execute('simple.select.find_sentence_by_internal_name', array(
            'internal_name' => $internalName,
            'lesson_id' => $lessonId,
        ));
    }
    /**
     * @param Sentence $sentence
     * @return PromiseInterface
     */
    public function create(Sentence $sentence) : PromiseInterface
    {
        return $this->findSentenceByInternalName($sentence->getInternalName(), $sentence->getLessonId())
            ->success(function() {
                return new Promise();
            })
            ->failure(function() use ($sentence) {
                return $this->blueDot->execute('scenario.create_sentence', array(
                    'create_sentence' => array('sentence' => $sentence->getSentence()),
                    'create_lesson_sentence' => array(
                        'internal_name' => $sentence->getInternalName(),
                        'lesson_id' => $sentence->getLessonId(),
                    ),
                    'create_lesson_sentence_translations' => array(
                        'translation' => $sentence->getTranslations(),
                    ),
                ));
            })
            ->getResult();
    }
    /**
     * @param $lessonId
     * @return PromiseInterface
     */
    public function findInternalNames($lessonId) : PromiseInterface
    {
        return $this->blueDot->execute('callable.internal_names_callable', array(
            'lesson_id' => $lessonId,
        ));
    }
    /**
     * @param array $data
     * @return PromiseInterface
     */
    public function findLessonSentence(array $data) : PromiseInterface
    {
        return $this->blueDot->execute('callable.find_sentence_callable', $data);
    }
    /**
     * @param $data
     * @return PromiseInterface
     */
    public function updateLessonSentence(array $data) : PromiseInterface
    {
        $lessonId = $data['data']['lesson_id'];
        $internalName = $data['data']['internal_name'];

        $promise = $this->blueDot->execute('simple.select.find_sentence_by_internal_name', array(
            'internal_name' => $internalName,
            'lesson_id' => $lessonId,
        ));

        if ($promise->isSuccess()) {
            return new Promise();
        }


        return $this->blueDot->execute('callable.update_lesson_sentence_callable', $data);
    }
}