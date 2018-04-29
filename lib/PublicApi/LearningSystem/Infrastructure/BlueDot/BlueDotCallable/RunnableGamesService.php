<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Configuration\Flow\Service\BaseService;

class RunnableGamesService extends BaseService
{
    public function run(): array
    {
        $learningMetadataId = $this->parameters['learning_metadata_id'];

        dump($learningMetadataId);
        die();
    }
}