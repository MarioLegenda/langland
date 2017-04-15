<?php

namespace AdminBundle\BlueDotCallable;

use BlueDot\Common\AbstractCallable;
use BlueDot\Entity\PromiseInterface;

class InternalNamesCallable extends AbstractCallable
{
    /**
     * @return array|null
     */
    public function run()
    {
        return $this->blueDot->execute('simple.select.find_internal_names', array(
            'lesson_id' => $this->parameters['lesson_id'],
        ))
            ->success(function(PromiseInterface $promise) {
                $results = $promise->getResult()->toArray();

                $internalNames = array();

                foreach ($results as $result) {
                    $internalNames[] = $result['internal_name'];
                }

                return $internalNames;
            })
            ->failure(function() {
                return null;
            })
            ->getResult();
    }
}