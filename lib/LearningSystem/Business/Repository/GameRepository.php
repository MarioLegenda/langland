<?php

namespace LearningSystem\Business\Repository;

use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class GameRepository extends BaseBlueDotRepository
{
    /**
     * @param GameInterface $game
     * @param int $learningUserId
     * @param int $learningMetadataId
     */
    public function createGame(
        GameInterface $game,
        int $learningUserId,
        int $learningMetadataId
    ) {
        $this->blueDot->useRepository('public_api_game');

        $this->doCreateGame($game, $learningUserId, $learningMetadataId);
    }
    /**
     * @param GameInterface $game
     * @param int $learningUserId
     * @param int $learningMetadataId
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function doCreateGame(
        GameInterface $game,
        int $learningUserId,
        int $learningMetadataId
    ) {
        $gameName = $game->getName();
        $data = $game->getGameData();

        $this->blueDot->execute('scenario.create_learning_game', [
            'create_learning_game' => [
                'learning_user_id' => $learningUserId,
                'learning_metadata_id' => $learningMetadataId,
            ],
            'create_learning_game_challenge' => [
                'learning_user_id' => $learningUserId,
            ],
            'create_learning_game_data' => $this->createGameDataParameters($gameName, $data),
        ]);
    }
    /**
     * @param string $gameName
     * @param ProvidedDataInterface $data
     * @return array
     */
    private function createGameDataParameters(
        string $gameName,
        ProvidedDataInterface $data
    ): array {
        $parameters = [];
        /** @var ProvidedDataInterface $item */
        foreach ($data as $item) {
            $parameters[] = [
                'name' => $gameName,
                'data_id' => $item->getField('id'),
            ];
        }

        return $parameters;
    }
}