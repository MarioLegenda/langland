<?php

namespace PublicApiBundle\Entity;

use LearningSystemBundle\Entity\SystemHead;

class LearningMetadata
{
    /**
     * @var SystemHead $lessonSystemHead
     */
    private $lessonSystemHead;
    /**
     * @var SystemHead $gamesSystemHeads
     */
    private $gamesSystemHeads;
}