<?php

namespace PublicApi\LearningSystem\Infrastructure\GameProvider;

use LearningSystem\Business\GameDatabaseCreator;
use LearningSystem\Library\Game\Implementation\GameInterface;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningLesson;

class GameProvider
{
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var GameDatabaseCreator $gameRepository
     */
    private $gameRepository;
    /**
     * GameProvider constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param GameDatabaseCreator $gameRepository
     */
    public function __construct(
        LearningUserProvider $learningUserProvider,
        GameDatabaseCreator $gameRepository
    ) {
        $this->learningUserProvider = $learningUserProvider;
        $this->gameRepository = $gameRepository;
    }
    /**
     * @param GameInterface $game
     * @param LearningLesson $learningLesson
     */
    public function createGame(
        GameInterface $game,
        LearningLesson $learningLesson
    ) {
        $learningUser  = $this->learningUserProvider->getLearningUser();

        $this->gameRepository->createGame(
            $game,
            $learningUser,
            $learningLesson
        );
    }
}