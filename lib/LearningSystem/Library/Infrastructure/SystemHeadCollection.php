<?php

namespace LearningSystem\Library\Infrastructure;

use LearningSystem\Library\Repository\Contract\SystemHeadInterface;

class SystemHeadCollection
{
    /**
     * @var array $systemHeads
     */
    private $systemHeads;
    /**
     * SystemHeadCollection constructor.
     * @param array $systemHeads
     */
    public function __construct(array $systemHeads)
    {
        $this->validate($systemHeads);

        $this->systemHeads = $systemHeads;
    }
    /**
     * @param array $systemHeads
     */
    private function validate(array $systemHeads)
    {
        foreach ($systemHeads as $systemHead) {
            if (!$systemHead instanceof SystemHeadInterface) {
                $message = sprintf(
                    '%s has to be instantiated with an array of %s instances',
                    SystemHeadCollection::class,
                    SystemHeadInterface::class
                );

                throw new \RuntimeException($message);
            }
        }
    }
}