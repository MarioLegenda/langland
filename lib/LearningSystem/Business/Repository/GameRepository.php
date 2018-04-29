<?php

namespace LearningSystem\Business\Repository;

use BlueDot\Entity\PromiseInterface;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class GameRepository extends BaseBlueDotRepository
{
    /**
     * @param GameInterface $game
     * @param int $learningUserId
     * @param int $learningMetadataId
     * @param int $learningLessonId
     */
    public function createGame(
        GameInterface $game,
        int $learningUserId,
        int $learningMetadataId,
        int $learningLessonId
    ) {
        $this->blueDot->useRepository('public_api_game');

        $this->doCreateGame(
            $game,
            $learningUserId,
            $learningMetadataId,
            $learningLessonId
        );
    }
    /**
     * @param GameInterface $game
     * @param int $learningUserId
     * @param int $learningMetadataId
     * @param int $learningLessonId
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function doCreateGame(
        GameInterface $game,
        int $learningUserId,
        int $learningMetadataId,
        int $learningLessonId
    ) {
        $gameName = $game->getName();
        $gameType = $game->getType();
        $data = $game->getGameData();

        $promise = $this->blueDot->execute('scenario.create_learning_game', [
            'create_learning_game' => [
                'name' => $gameName,
                'type' => $gameType,
                'learning_user_id' => $learningUserId,
                'learning_lesson_id' => $learningLessonId,
                'learning_metadata_id' => $learningMetadataId,
            ],
        ]);

        $learningGameId = $promise->getResult()->get('create_learning_game')['last_insert_id'];

		$challengeParameters = $this->createFakeGameChallengeParameters($data, $learningUserId, $learningGameId);

		foreach ($challengeParameters as $parameter) {
			$this->blueDot->prepareExecution('scenario.create_learning_game_challenge', [
				'create_learning_game_challenge' => $parameter,
			]);
		}

		/** @var PromiseInterface[] $promises */
		$promises = $this->blueDot->executePrepared();

		$gameChallengeInsertedIds = [];
        foreach ($promises as $promise) {
            $gameChallengedId = $promise->getResult()->get('create_learning_game_challenge')['last_insert_id'];

            $gameChallengeInsertedIds[] = $gameChallengedId;
        }

        $this->blueDot->execute('simple.insert.create_learning_game_data', $this->createGameDataParameters(
            $data,
            $gameChallengeInsertedIds,
            $learningGameId
        ));
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
