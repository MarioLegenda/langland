<?php

namespace LearningSystem\Business;

use LearningSystem\Library\Game\Implementation\GameInterface;
use PublicApi\Infrastructure\Model\Word\InitialCreationWord;
use PublicApi\LearningSystem\Infrastructure\DataProvider\Word\ProvidedWordDataCollection;
use PublicApi\LearningSystem\Repository\DataCollectorRepository;
use PublicApi\LearningSystem\Repository\LearningGameChallengeRepository;
use PublicApi\LearningSystem\Repository\LearningGameDataRepository;
use PublicApi\LearningSystem\Repository\LearningGameRepository;
use PublicApi\LearningSystem\Repository\LearningMetadataRepository;
use PublicApiBundle\Entity\LearningGame;
use PublicApiBundle\Entity\LearningGameChallenge;
use PublicApiBundle\Entity\LearningGameData;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\DataCollector;
use PublicApiBundle\Entity\LearningMetadata;
use PublicApiBundle\Entity\LearningUser;

class GameDatabaseCreator
{
    /**
     * @var LearningGameRepository $learningGameRepository
     */
    private $learningGameRepository;
    /**
     * @var DataCollectorRepository $dataCollectorRepository
     */
    private $dataCollectorRepository;
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;
    /**
     * @var LearningGameChallengeRepository $learningGameChallengeRepository
     */
    private $learningGameChallengeRepository;
    /**
     * @var LearningGameDataRepository $learningGameDataRepository
     */
    private $learningGameDataRepository;
    /**
     * GameDatabaseCreator constructor.
     * @param LearningGameRepository $learningGameRepository
     * @param DataCollectorRepository $dataCollectorRepository
     * @param LearningMetadataRepository $learningMetadataRepository
     * @param LearningGameChallengeRepository $learningGameChallengeRepository
     * @param LearningGameDataRepository $learningGameDataRepository
     */
    public function __construct(
        LearningGameRepository $learningGameRepository,
        DataCollectorRepository $dataCollectorRepository,
        LearningMetadataRepository $learningMetadataRepository,
        LearningGameChallengeRepository $learningGameChallengeRepository,
        LearningGameDataRepository $learningGameDataRepository
    ) {
        $this->learningGameRepository = $learningGameRepository;
        $this->dataCollectorRepository = $dataCollectorRepository;
        $this->learningMetadataRepository = $learningMetadataRepository;
        $this->learningGameChallengeRepository = $learningGameChallengeRepository;
        $this->learningGameDataRepository = $learningGameDataRepository;
    }
    /**
     * @param GameInterface $game
     * @param LearningUser $learningUser
     * @param LearningLesson $learningLesson
     */
    public function createGame(
        GameInterface $game,
        LearningUser $learningUser,
        LearningLesson $learningLesson
    ) {
        $this->doCreateGame(
            $game,
            $learningUser,
            $learningLesson
        );
    }
    /**
     * @param GameInterface $game
     * @param LearningUser $learningUser
     * @param LearningLesson $learningLesson
     */
    private function doCreateGame(
        GameInterface $game,
        LearningUser $learningUser,
        LearningLesson $learningLesson
    ) {
        $gameName = $game->getName();
        $gameType = $game->getType();
        /** @var ProvidedWordDataCollection $data */
        $data = $game->getGameData();

        $learningGameDataCollector = new DataCollector();
        $learningMetadataDataCollector = new DataCollector();
        $learningGameMetadata = new LearningMetadata(
            $learningMetadataDataCollector,
            $learningUser
        );

        $this->dataCollectorRepository->persist($learningMetadataDataCollector);
        $this->dataCollectorRepository->persist($learningGameDataCollector);
        $this->learningMetadataRepository->persist($learningGameMetadata);

        $learningGame = new LearningGame(
            $gameName,
            $gameType,
            $learningGameDataCollector,
            $learningUser,
            $learningGameMetadata,
            $learningLesson,
            false
        );

        $this->learningGameRepository->persist($learningGame);

        $learningGameChallengeDataCollector = new DataCollector();

        $this->dataCollectorRepository->persist($learningGameChallengeDataCollector);

        $this->persistGameChallenges(
            $data,
            $learningGameChallengeDataCollector,
            $learningUser,
            $learningGame
        );

        $this->learningGameRepository->flush();
    }
    /**
     * @param ProvidedWordDataCollection $data
     * @param DataCollector $learningGameChallengeDataCollector
     * @param LearningUser $learningUser
     * @param LearningGame $learningGame
     */
    public function persistGameChallenges(
        ProvidedWordDataCollection $data,
        DataCollector $learningGameChallengeDataCollector,
        LearningUser $learningUser,
        LearningGame $learningGame
    ) {
        /** @var InitialCreationWord $item */
        foreach ($data as $item) {
            $learningGameChallenge = new LearningGameChallenge(
                $learningGameChallengeDataCollector,
                $learningUser,
                $learningGame
            );

            $learningGameData = new LearningGameData(
                $learningGameChallenge,
                $learningGame,
                $item->getId()
            );

            $this->learningGameChallengeRepository->persist($learningGameChallenge);
            $this->learningGameDataRepository->persist($learningGameData);
        }
    }
}
