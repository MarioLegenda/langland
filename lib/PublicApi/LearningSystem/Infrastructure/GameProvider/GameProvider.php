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
     * @var GameDatabaseCreator $gameDatabaseCreator
     */
    private $gameDatabaseCreator;
    /**
     * GameProvider constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param GameDatabaseCreator $gameDatabaseCreator
     */
    public function __construct(
        LearningUserProvider $learningUserProvider,
        GameDatabaseCreator $gameDatabaseCreator
    ) {
        $this->learningUserProvider = $learningUserProvider;
        $this->gameDatabaseCreator = $gameDatabaseCreator;
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

        $this->gameDatabaseCreator->createGame(
            $game,
            $learningUser,
            $learningLesson
        );
    }
}