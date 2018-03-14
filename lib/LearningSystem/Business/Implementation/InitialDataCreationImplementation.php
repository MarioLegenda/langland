<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use LearningSystem\Library\Worker\GameWorker;
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
     * InitialDataCreationImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param GameWorker $gameWorker
     */
    public function __construct(
        ApiSDK $apiSDK,
        GameWorker $gameWorker
    ) {
        $this->apiSdk = $apiSDK;
        $this->gameWorker = $gameWorker;
    }
    /**
     * @return array
     */
    public function createInitialData(): array
    {
        $game = $this->gameWorker->createGame();

        return $this->apiSdk
            ->create([])
            ->method('POST')
            ->isResource()
            ->addMessage(sprintf('Created %s', $game->getName()))
            ->setStatusCode(201)
            ->build();
    }
}