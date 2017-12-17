<?php

namespace Library\Infrastructure\BlueDot;

use BlueDot\BlueDot;

class BaseBlueDotRepository
{
    /**
     * @var BlueDot $blueDot
     */
    protected $blueDot;
    /**
     * LessonApiRepository constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     */
    public function __construct(BlueDot $blueDot, string $apiName)
    {
        $blueDot->useApi($apiName);

        $this->blueDot = $blueDot;
    }
    /**
     * @param int $id
     * @return null|object
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function find(int $id): ?object
    {
        return $this->blueDot->execute('simple.select.find_lesson_by_id', [
            'id' => $id,
        ])->getResult();
    }
}