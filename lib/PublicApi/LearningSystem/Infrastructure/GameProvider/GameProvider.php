<?php

namespace PublicApi\LearningSystem\Infrastructure\GameProvider;

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
    /**
     * @param $game
     * @param int $learningMetadataId
     * @param int $learningLessonId
     */
    public function createGame($game, int $learningMetadataId, int $learningLessonId)
    {
        $learningUserId = $this->learningUserProvider->getLearningUser()->getId();

        $this->gameRepository->createGame(
            $game,
            $learningUserId,
            $learningMetadataId,
            $learningLessonId
        );
    }
}