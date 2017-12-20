<?php

namespace LearningSystem\Infrastructure\Type;

class RepeatType extends BaseType
{
    protected static $types = [
        0 => 'end_repeat_all',
        1 => 'difficult_words_repeat',
        2 => 'end_repeat_difficult_words',
        3 => 'had_trouble_words_repeat',
        4 => 'had_trouble_words_repeat_end',
    ];
}