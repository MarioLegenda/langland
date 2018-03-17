<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    public function getLearningMetadata(
        int $learningUserId
    ) {
        $this->blueDot->useRepository('learning_user_metadata');


    }
}