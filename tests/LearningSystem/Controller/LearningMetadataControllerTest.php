<?php

namespace LearningSystem\Controller;

use ArmorBundle\Entity\User;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;
use PublicApi\LearningSystem\Repository\LearningMetadataRepository;

class LearningMetadataControllerTest extends PublicApiTestCase
{
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;

    public function setUp()
    {
        parent::setUp();

        $this->learningMetadataRepository = $this->container->get('public_api.repository.learning_system.blue_dot.learning_metadata');
    }

    public function test_get_learning_metadata_repository()
    {
        $this->manualReset();

        $learningUsers = $this->prepareTest();

        die("mile");

        /** @var LearningUser $learningUser */
        foreach ($learningUsers as $learningUser) {
            $data = $this->learningMetadataRepository->getLearningMetadata($learningUser->getId());

            $this->assertRepositoryData($data);
        }
    }
    /**
     * @param array $data
     */
    private function assertRepositoryData(array $data)
    {
        static::assertNotEmpty($data);
        static::assertInternalType('array', $data);
    }
    /**
     * @void
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return array
     */
    private function prepareTest(): array
    {
        $numberOfLanguages = 3;
        $wordLevels = [1, 2, 3];

        $learningUsers = [];

        for ($i = 0; $i < $numberOfLanguages; $i++) {
            $language = $this->prepareLanguageData($wordLevels[$i]);
            $userData = $this->prepareUserData($language);

            /** @var LearningUser $learningUser */
            $learningUser = $userData['learningUser'];
            /** @var User $user */
            $user = $userData['user'];

            $learningUsers[] = $learningUser;

            $mockedProviderData = $this->mockLearningUserProvider($user);

            $this->prepareLearningMetadata($learningUser);

            foreach ($wordLevels as $level) {
                $this->createWords($language,50, [
                    'level' => $level,
                ]);
            }

            $wordNumbers = [15, 17, 20];

            foreach ($wordNumbers as $wordNumber) {
                $this->mockInitialWordDataProvider(
                    $mockedProviderData['learningUserProvider'],
                    $mockedProviderData['languageProvider']
                );

                $controller = $this->container->get('learning_system.business.controller.initial_data_creation');

                $response = $controller->makeInitialDataCreation();

                static::assertInstanceOf(JsonResponse::class, $response);
                static::assertEquals(201, $response->getStatusCode());
            }
        }

        if (empty($learningUsers)) {
            throw new \RuntimeException('Failed test preparation. There are not learning users');
        }

        return $learningUsers;
    }
}