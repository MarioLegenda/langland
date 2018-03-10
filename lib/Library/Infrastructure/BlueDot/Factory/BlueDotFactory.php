<?php

namespace Library\Infrastructure\BlueDot\Factory;

use BlueDot\BlueDot;
use BlueDot\Database\Connection;
use Doctrine\DBAL\Connection as DoctrineConnection;

class BlueDotFactory
{
    /**
     * @var array $blueDotApis
     */
    private $blueDotApis;
    /**
     * @var DoctrineConnection $doctrineConnection
     */
    private $doctrineConnection;
    /**
     * BlueDotFactory constructor.
     * @param DoctrineConnection $doctrineConnection
     * @param array $blueDotApis
     */
    public function __construct(
        DoctrineConnection $doctrineConnection,
        array $blueDotApis
    ) {
        $this->doctrineConnection = $doctrineConnection;
        $this->blueDotApis = $blueDotApis;
    }
    /**
     * @return BlueDot
     */
    public function createBlueDot(): BlueDot
    {
        $blueDotConnection = new Connection();
        $blueDotConnection->setPDO($this->doctrineConnection->getWrappedConnection());

        $blueDot = new BlueDot();
        $blueDot->setConnection($blueDotConnection);

        foreach ($this->blueDotApis as $apiDir) {
            $blueDot->repository()->putRepository($apiDir);
        }

        return $blueDot;
    }
}