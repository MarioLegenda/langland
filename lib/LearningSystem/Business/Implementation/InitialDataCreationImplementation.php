<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use LearningSystem\Library\Worker\GameWorker;
use PublicApi\LearningSystem\GameProvider\GameProvider;
use PublicApiBundle\Entity\LearningUser;

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
     * @return array
     */
    public function createInitialData(): array
    {
        $game = $this->gameWorker->createGame();

        $this->gameProvider->createGame($game);

        return $this->apiSdk
            ->create([])
            ->method('POST')
            ->isResource()
            ->addMessage(sprintf('Created %s', $game->getName()))
            ->setStatusCode(201)
            ->build();
    }
}