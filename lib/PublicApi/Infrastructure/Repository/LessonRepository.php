<?php

namespace PublicApi\Infrastructure\Repository;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use Library\Infrastructure\Repository\CommonRepository;

class LessonRepository extends CommonRepository
{
    /**
     * @param Language $language
     * @return Lesson[]
     */
    public function getLessonsByLanguage(Language $language): array
    {
        $qb = $this->createQueryBuilderFromClass('l');

        $metadataLessons = $qb
            ->andWhere('l.language = :language')
            ->setParameter(':language', $language->getId())
            ->getQuery()
            ->getResult();

        return $metadataLessons;
    }
}