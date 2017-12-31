<?php

namespace PublicApi\Infrastructure\Communication;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningUser\Repository\LearningUserRepository;

class RepositoryCommunicator
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * RepositoryCommunicator constructor.
     * @param LanguageRepository $languageRepository
     * @param LearningUserRepository $learningUserRepository
     */
    public function __construct(
        LanguageRepository $languageRepository,
        LearningUserRepository $learningUserRepository
    ) {
        $this->languageRepository = $languageRepository;
        $this->learningUserRepository = $learningUserRepository;
    }
    /**
     * @param User $user
     * @return array
     */
    public function getAllAlreadyLearningLanguages(User $user): array
    {
        $languages = $this->languageRepository->findAll();

        $languageIds = [];

        /** @var Language $language */
        foreach ($languages as $language) {
            $languageIds[] = $language->getId();
        }

        $qb = $this->learningUserRepository->createQueryBuilderFromClass('lu');

        $learningUsers = $qb
            ->andwhere('lu.language IN (:languageIds)')
            ->andWhere('lu.user = :userId')
            ->setParameter(':languageIds', $languageIds)
            ->setParameter(':userId', $user->getId())
            ->getQuery()
            ->getResult();

        $viewable = [];
        foreach ($languages as $language) {
            $temp = [];
            $temp['id'] = $language->getId();
            $temp['name'] = $language->getName();
            $temp['desc'] = $language->getListDescription();
            $temp['images'] = $language->getImages();
            $temp['alreadyLearning'] = false;

            foreach ($learningUsers as $learningUser) {
                if ($this->equalsLanguage($language, $learningUser->getLanguage())) {
                    $temp['alreadyLearning'] = true;
                }
            }

            $viewable[] = $temp;
        }

        return $viewable;
    }
    /**
     * @param Language $language1
     * @param Language $language2
     * @return bool
     */
    private function equalsLanguage(Language $language1, Language $language2): bool
    {
        return (int) $language1->getId() === (int) $language2->getId();
    }
}