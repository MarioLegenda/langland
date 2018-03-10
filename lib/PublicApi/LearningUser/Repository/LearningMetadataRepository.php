<?php

namespace PublicApi\LearningUser\Repository;

use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * @param int $learningUserId
     * @return bool
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function existsOnLearningUser(int $learningUserId): bool
    {
        return $this->blueDot->execute('simple.select.check_that_exists', [
            'learning_user_id' => $learningUserId,
        ])->isSuccess();
    }
}