<?php

namespace LearningSystem\Business\Repository;

use BlueDot\BlueDot;
use BlueDot\Entity\PromiseInterface;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\Infrastructure\Model\Word\InitialCreationWord;
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

class GameRepository extends BaseBlueDotRepository
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
     * GameRepository constructor.
     * @param BlueDot $blueDot
     * @param $apiName
     * @param LearningGameRepository $learningGameRepository
     * @param DataCollectorRepository $dataCollectorRepository
     * @param LearningMetadataRepository $learningMetadataRepository
     * @param LearningGameChallengeRepository $learningGameChallengeRepository
     * @param LearningGameDataRepository $learningGameDataRepository
     */
    public function __construct(
        BlueDot $blueDot,
        $apiName,
        LearningGameRepository $learningGameRepository,
        DataCollectorRepository $dataCollectorRepository,
        LearningMetadataRepository $learningMetadataRepository,
        LearningGameChallengeRepository $learningGameChallengeRepository,
        LearningGameDataRepository $learningGameDataRepository
    ) {
        parent::__construct($blueDot, $apiName);

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
     * @throws \BlueDot\Exception\ConnectionException
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
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function doCreateGame(
        GameInterface $game,
        LearningUser $learningUser,
        LearningLesson $learningLesson
    ) {
        $gameName = $game->getName();
        $gameType = $game->getType();
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

        $this->learningGameRepository->flush();
    }
    /**
     * @param ProvidedDataInterface $data
     * @param int $learningUserId
     * @param int $learningGameId
     * @return array
     */
    public function createFakeGameChallengeParameters(
        ProvidedDataInterface $data,
        int $learningUserId,
        int $learningGameId
    ): array {
        $parameters = [];
        foreach ($data as $item) {
            $parameters[] = [
                'learning_user_id' => $learningUserId,
                'learning_game_id' => $learningGameId,
            ];
        }

        return $parameters;
    }
    /**
     * @param ProvidedDataInterface $data
     * @param array $gameChallengeInsertedIds
     * @param int $learningGameId
     * @return array
     */
    private function createGameDataParameters(
        ProvidedDataInterface $data,
        array $gameChallengeInsertedIds,
        int $learningGameId
    ): array {
        $parameters = [];
        /** @var ProvidedDataInterface $item */
        foreach ($data as $key => $item) {
            $parameters[] = [
                'data_id' => $item->getField('id'),
                'learning_game_challenge_id' => $gameChallengeInsertedIds[$key],
                'learning_game_id' => $learningGameId,
            ];
        }

        return $parameters;
    }
}
