<?php

namespace PublicApi\LearningSystem\Infrastructure\GameProvider;

use Armor\Infrastructure\Provider\LanguageSessionProvider;
use LearningSystem\Business\GameDatabaseCreator;
use LearningSystem\Library\Game\Implementation\GameInterface;
use PublicApiBundle\Entity\LearningLesson;

class GameProvider
{
    /**
     * @var LanguageSessionProvider $languageSessionProvider
     */
    private $languageSessionProvider;
    /**
     * @var GameDatabaseCreator $gameDatabaseCreator
     */
    private $gameDatabaseCreator;
    /**
     * GameProvider constructor.
     * @param LanguageSessionProvider $languageSessionProvider
     * @param GameDatabaseCreator $gameDatabaseCreator
     */
    public function __construct(
        LanguageSessionProvider $languageSessionProvider,
        GameDatabaseCreator $gameDatabaseCreator
    ) {
        $this->languageSessionProvider = $languageSessionProvider;
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
        $learningUser  = $this->languageSessionProvider->getLearningUser();

        $this->gameDatabaseCreator->createGame(
            $game,
            $learningUser,
            $learningLesson
        );
    }
}