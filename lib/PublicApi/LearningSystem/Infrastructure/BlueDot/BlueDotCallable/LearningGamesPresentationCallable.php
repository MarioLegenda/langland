<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Common\AbstractCallable;

class LearningGamesPresentationCallable extends AbstractCallable
{
    /**
     * @inheritdoc
     */
    public function run(): array
    {
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        $learningUserId = $this->parameters['learning_user_id'];

        $promise = $this->blueDot->execute('simple.select.get_games_presentation_by_learning_user', [
            'learning_user_id' => $learningUserId,
        ]);

        return $promise->getResult()->toArray();
    }
}