<?php

namespace PublicApi\LearningSystem\Repository;

use PublicApiBundle\Entity\DataCollector;
use Library\Infrastructure\Repository\CommonRepository;

class DataCollectorRepository extends CommonRepository
{
    /**
     * @param DataCollector $dataCollector
     * @return DataCollector
     */
    public function persistAndFlush(DataCollector $dataCollector): DataCollector
    {
        $this->em->persist($dataCollector);
        $this->em->flush();

        return $dataCollector;
    }
}