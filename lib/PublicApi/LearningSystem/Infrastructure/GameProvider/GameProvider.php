<?php

namespace PublicApi\LearningSystem\Infrastructure\GameProvider;

use LearningSystem\Business\Repository\GameRepository;
use LearningSystem\Library\Game\Implementation\GameInterface;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningMetadata;

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