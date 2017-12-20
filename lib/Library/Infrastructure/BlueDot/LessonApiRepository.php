<?php

namespace Library\Infrastructure\BlueDot;

use BlueDot\BlueDot;

class LessonApiRepository
{
    /**
     * @var BlueDot $blueDot
     */
    private $blueDot;
    /**
     * LessonApiRepository constructor.
     * @param BlueDot $blueDot
     */
    public function __construct(BlueDot $blueDot)
    {
        $blueDot->useApi('public_api_lesson');

        $this->blueDot = $blueDot;
    }
}