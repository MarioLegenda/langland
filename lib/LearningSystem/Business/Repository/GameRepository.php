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
     */
    public function createGame(
        GameInterface $game,
        int $learningUserId
    ) {
        $this->blueDot->useRepository('public_api_game');

        $this->doCreateGame($game, $learningUserId);
    }
    /**
     * @param GameInterface $game
     * @param int $learningUserId
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function doCreateGame(
        GameInterface $game,
        int $learningUserId
    ) {
        $gameName = $game->getName();
        $data = $game->getGameData();

        $this->blueDot->execute('scenario.create_learning_game', [
            'create_learning_game' => [
                'learning_user_id' => $learningUserId,
            ],
        ])->success(function(PromiseInterface $promise) use ($learningUserId, $gameName, $data) {
            $thisResult = $promise->getResult();

            $dataCollectorId = $thisResult->get('create_data_collector')['last_insert_id'];
            $learningGameId = $thisResult->get('create_learning_game')['last_insert_id'];

            /** @var ProvidedDataInterface $item */
            foreach ($data as $item) {
                $this->blueDot->prepareExecution('scenario.create_game_challenge', [
                    'get_learning_game' => [
                        'learning_user_id' => $learningUserId,
                        'data_collector_id' => $dataCollectorId,
                    ],
                    'create_learning_game_challenge' => [
                        'learning_user_id' => $learningUserId,
                    ],
                    'create_learning_game_data' => [
                        'name' => $gameName,
                        'data_id' => $item->getField('id'),
                    ],
                ]);
            }

            try {
                $this->blueDot->executePrepared();
            } catch (\Exception $e) {

                $this->blueDot->execute('scenario.remove_learning_game', [
                    'get_learning_game' => [
                        'learning_game_id' => $learningGameId,
                    ],
                    'remove_learning_game' => [
                        'learning_game_id' => $learningGameId,
                    ],
                ]);

                throw $e;
            }
        });
    }
}