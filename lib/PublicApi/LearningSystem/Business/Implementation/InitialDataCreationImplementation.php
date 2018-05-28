<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use LearningSystem\Library\Worker\GameWorker;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\Infrastructure\GameProvider\GameProvider;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningMetadata;

class InitialDataCreationImplementation
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var GameWorker $gameWorker
     */
    private $gameWorker;
    /**
     * @var GameProvider $gameProvider
     */
    private $gameProvider;
    /**
     * InitialDataCreationImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param GameWorker $gameWorker
     * @param GameProvider $gameProvider
     */
    public function __construct(
        ApiSDK $apiSDK,
        GameWorker $gameWorker,
        GameProvider $gameProvider
    ) {
        $this->apiSdk = $apiSDK;
        $this->gameWorker = $gameWorker;
        $this->gameProvider = $gameProvider;
    }
    /**
     * @param LearningLesson $learningLesson
     * @return array
     */
    public function createInitialData(
        LearningLesson $learningLesson
    ): array {
        $game = $this->gameWorker->createGame($learningLesson);

        $this->gameProvider->createGame($game, $learningLesson);

        return $this->apiSdk
            ->create([])
            ->method('POST')
            ->isResource()
            ->addMessage(sprintf('Created %s', $game->getName()))
            ->setStatusCode(201)
            ->build();
    }
}