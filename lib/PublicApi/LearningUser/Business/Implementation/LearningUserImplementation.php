<?php

namespace PublicApi\LearningUser\Business\Implementation;

use AdminBundle\Entity\Language;
use ApiSDK\ApiSDK;
use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use LearningSystem\Infrastructure\Questions;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswersValidator;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use PublicApiBundle\Entity\Question;

class LearningUserImplementation
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * @var LearningMetadataImplementation $learningMetadataImplementation
     */
    private $learningMetadataImplementation;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * LearningUserImplementation constructor.
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageRepository $languageRepository
     * @param UserRepository $userRepository
     * @param LearningMetadataImplementation $learningMetadataImplementation
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        LanguageRepository $languageRepository,
        UserRepository $userRepository,
        LearningMetadataImplementation $learningMetadataImplementation,
        ApiSDK $apiSDK
    ) {
        $this->learningUserRepository = $learningUserRepository;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->apiSdk = $apiSDK;
        $this->learningMetadataImplementation = $learningMetadataImplementation;
    }
    /**
     * @param int $id
     * @return null|LearningUser
     */
    public function tryFind(int $id): ?LearningUser
    {
        $learningUser = $this->learningUserRepository->find($id);

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        return $learningUser;
    }
    /**
     * @param int $id
     * @return LearningUser
     */
    public function find(int $id): LearningUser
    {
        $learningUser = $this->learningUserRepository->find($id);

        if (!$learningUser instanceof LearningUser) {
            throw new \RuntimeException('Learning user not found');
        }

        return $learningUser;
    }
    /**
     * @param int|Language $language
     * @param User $user
     * @return null|LearningUser
     */
    public function findExact($language, User $user): ?LearningUser
    {
        if (is_int($language)) {
            $language = $this->languageRepository->find($language);
        }

        if (!$language instanceof Language) {
            throw new \RuntimeException('Not a valid language');
        }

        /** @var LearningUser $learningUser */
        $learningUser = $this->learningUserRepository->findOneBy([
            'language' => $language,
            'user' => $user,
        ]);

        return $learningUser;
    }
    /**
     * @param Language $language
     * @param User $user
     * @return array
     */
    public function registerLearningUser(Language $language, User $user): array
    {
        $learningUser = new LearningUser();
        $learningUser->setLanguage($language);
        $learningUser->setUser($user);

        $user->setCurrentLearningUser($learningUser);
        $learningUser = $this->learningUserRepository->persistAndFlush($learningUser);

        $this->userRepository->persistAndFlush($user);

        $this->learningMetadataImplementation->createFirstLearningMetadata($learningUser);

        $data = [
            'id' => $learningUser->getId(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ],
            'createdAt' => $learningUser->getCreatedAt()->format('Y-m-d H-m-s'),
            'updatedAt' => $learningUser->getUpdatedAt()->format('Y-m-d H-m-s'),
        ];

        return $this->apiSdk
            ->create($data)
            ->isResource()
            ->method('POST')
            ->setStatusCode(201)
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @param User $user
     * @param Language $language
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLearningUser(
        LearningUser $learningUser,
        Language $language,
        User $user
    ): array {
        $user->setCurrentLearningUser($learningUser);

        $this->userRepository->persistAndFlush($user);

        $data = [
            'id' => $learningUser->getId(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ],
            'createdAt' => $learningUser->getCreatedAt()->format('Y-m-d H-m-s'),
            'updatedAt' => $learningUser->getUpdatedAt()->format('Y-m-d H-m-s'),
        ];

        return $this->apiSdk
            ->create($data)
            ->setStatusCode(200)
            ->isResource()
            ->method('POST')
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function markLanguageInfoLooked(LearningUser $learningUser): array
    {
        $data = [
            'isLanguageInfoLooked' => $learningUser->getIsLanguageInfoLooked(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        if ($learningUser->getIsLanguageInfoLooked() === false) {
            $learningUser->setIsLanguageInfoLooked(true);

            $data['isLanguageInfoLooked'] = $learningUser->getIsLanguageInfoLooked();

            $this->learningUserRepository->persistAndFlush($learningUser);
        }

        return $this->apiSdk
            ->create($data)
            ->setStatusCode(201)
            ->isResource()
            ->method('POST')
            ->build();
    }
    /**
     * @param User $user
     * @param QuestionAnswers $questionAnswers
     * @return array
     */
    public function markQuestionsAnswered(User $user, QuestionAnswers $questionAnswers)
    {
        $learningUser = $user->getCurrentLearningUser();

        $data = [
            'areQuestionsLooked' => $learningUser->getAreQuestionsLooked(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        if ($learningUser->getIsLanguageInfoLooked() === false) {
            return $this->apiSdk
                ->create($data)
                ->setStatusCode(403)
                ->isResource()
                ->addMessage('Language info is not looked')
                ->method('POST')
                ->build();
        }

        if ($learningUser->getAreQuestionsLooked() === false) {
            $learningUser->setAreQuestionsLooked(true);
            $learningUser->setAnsweredQuestions($questionAnswers->getAnswers());

            $this->learningUserRepository->persistAndFlush($learningUser);
        }

        $data = [
            'areQuestionsLooked' => $learningUser->getAreQuestionsLooked(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        return $this->apiSdk
            ->create($data)
            ->setStatusCode(201)
            ->isResource()
            ->method('POST')
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function getIsLanguageInfoLooked(LearningUser $learningUser): array
    {
        $response = $this->apiSdk
            ->create([
                'isLanguageInfoLooked' => $learningUser->getIsLanguageInfoLooked(),
                'language' => [
                    'id' => $learningUser->getLanguage()->getId(),
                    'name' => $learningUser->getLanguage()->getName(),
                ],
            ])
            ->setStatusCode(200)
            ->isResource()
            ->method('GET')
            ->build();

        return $response;
    }
    /**
     * @param User $user
     * @return array
     */
    public function getCurrentLearningUser(User $user): array
    {
        $learningUser = $user->getCurrentLearningUser();

        $response = $this->apiSdk
            ->create([
                'id' => $learningUser->getId(),
                'isLanguageInfoLooked' => $learningUser->getIsLanguageInfoLooked(),
                'language' => [
                    'id' => $learningUser->getLanguage()->getId(),
                    'name' => $learningUser->getLanguage()->getName(),
                ],
                'createdAt' => $learningUser->getCreatedAt()->format('Y-m-d H-m-s'),
                'updatedAt' => $learningUser->getUpdatedAt()->format('Y-m-d H-m-s'),
            ])
            ->setStatusCode(200)
            ->isResource()
            ->method('GET')
            ->build();

        return $response;
    }
    /**
     * @param User $user
     * @return array
     */
    public function getDynamicComponentsStatus(User $user): array
    {
        $learningUser = $user->getCurrentLearningUser();

        $languageInfoLooked = $learningUser->getIsLanguageInfoLooked();
        $questionsAnswered = $learningUser->getAreQuestionsLooked();

        return $this->apiSdk
            ->create([
                'isLanguageInfoLooked' => $languageInfoLooked,
                'areQuestionsLooked' => $questionsAnswered,
                'isMainAppReady' => ($languageInfoLooked && $questionsAnswered) ? false: true,
            ])
            ->setStatusCode(200)
            ->isResource()
            ->method('GET')
            ->build();
    }
    /**
     * @return array
     */
    public function getQuestions(): array
    {
        $questions = $this->learningUserRepository->getQuestions();

        return $this->apiSdk
            ->create(Question::fromCollectionToArray($questions))
            ->setStatusCode(200)
            ->isCollection()
            ->method('GET')
            ->build();
    }
    /**
     * @param QuestionAnswers $questionAnswers
     * @return array
     */
    public function validateQuestionAnswers(QuestionAnswers $questionAnswers): array
    {
        $validator = new QuestionAnswersValidator($questionAnswers, new Questions());

        try {
            $validator->validate();
        } catch (\RuntimeException $e) {
            return $this->apiSdk
                ->create([])
                ->setStatusCode(403)
                ->isResource()
                ->method('POST')
                ->build();
        }

        return $this->apiSdk
            ->create([])
            ->setStatusCode(200)
            ->isResource()
            ->method('POST')
            ->build();
    }
}