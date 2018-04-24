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
            $temp['images'] = $this->parseImages($language->getImages());
            $temp['alreadyLearning'] = false;
            $temp['urls'] = [
                'backend_url' => null,
                'frontend_url' => sprintf('language/%s/%d', $language->getName(), $language->getId())
            ];

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
    /**
     * @param array $images
     * @return array
     */
    private function parseImages(array $images): array
    {
        $parsed = [];
        $parsed['cover'] = sprintf(
            '%s/%s',
            $images['cover_image']['relativePath'],
            $images['cover_image']['originalName']
        );

        $parsed['icon'] = sprintf(
            '%s/%s',
            $images['icon']['relativePath'],
            $images['icon']['originalName']
        );

        return $parsed;
    }
}