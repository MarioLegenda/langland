<?php

namespace PublicApi\LearningSystem\GameProvider;

use LearningSystem\Business\Repository\GameRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;

class GameProvider
{
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var GameRepository $gameRepository
     */
    private $gameRepository;
    /**
     * GameProvider constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param GameRepository $gameRepository
     */
    public function __construct(
        LearningUserProvider $learningUserProvider,
        GameRepository $gameRepository
    ) {
        $this->learningUserProvider = $learningUserProvider;
        $this->gameRepository = $gameRepository;
    }

    public function createGame($game)
    {
        $learningUserId = $this->learningUserProvider->getLearningUser()->getId();

        $this->gameRepository->createGame($game, $learningUserId);
    }

    public function getGame()
    {

    }
}