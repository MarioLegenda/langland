<?php

namespace PublicApi\Language\Business\Implementation;

use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use PublicApi\Language\Repository\LanguageRepository;

class LanguageImplementation
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var RepositoryCommunicator $repositoryCommunicator
     */
    private $repositoryCommunicator;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     * @param RepositoryCommunicator $repositoryCommunicator
     */
    public function __construct(
        LanguageRepository $languageRepository,
        RepositoryCommunicator $repositoryCommunicator
    ) {
        $this->languageRepository = $languageRepository;
        $this->repositoryCommunicator = $repositoryCommunicator;
    }
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->languageRepository->findAll();
    }
    /**
     * @param User $user
     * @return array
     */
    public function findAllWithAlreadyLearning(User $user): array
    {
        return $this->repositoryCommunicator->getAllAlreadyLearningLanguages($user);
    }
}