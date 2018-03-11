<?php

namespace PublicApi\Lesson\Repository\BlueDot;

use AdminBundle\Entity\Lesson;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LessonRepository extends BaseBlueDotRepository
{
    /**
     * @param int $languageId
     * @return Lesson
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function findFirstLesson(int $languageId): Lesson
    {
        return $this->blueDot->execute('simple.select.find_first_lesson', [
            'language_id' => $languageId,
        ])->getResult();
    }
    /**
     * @param int $languageId
     * @return Lesson|null
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function tryFindFirstLesson(int $languageId): ?Lesson
    {
        $promise = $this->blueDot->execute('simple.select.find_first_lesson', [
            'language_id' => $languageId,
        ]);

        if ($promise->isSuccess()) {
            return $promise->getResult();
        }

        return null;
    }
}