<?php

namespace Library\Infrastructure\BlueDot\Factory;

use BlueDot\BlueDot;
use BlueDot\Kernel\Connection\Connection;
use Doctrine\DBAL\Connection as DoctrineConnection;

class BlueDotFactory
{
    /**
     * @var array $blueDotApis
     */
    private $blueDotApis;
    /**
     * @var string $blueDotEnvironment
     */
    private $blueDotEnvironment;
    /**
     * @var DoctrineConnection $doctrineConnection
     */
    private $doctrineConnection;
    /**
     * BlueDotFactory constructor.
     * @param DoctrineConnection $doctrineConnection
     * @param array $blueDotApis
     * @param string $blueDotEnvironment
     */
    public function __construct(
        DoctrineConnection $doctrineConnection,
        array $blueDotApis,
        string $blueDotEnvironment
    ) {
        $this->doctrineConnection = $doctrineConnection;
        $this->blueDotApis = $blueDotApis;
        $this->blueDotEnvironment = $blueDotEnvironment;
    }
    /**
     * @return BlueDot
     */
    public function createBlueDot(): BlueDot
    {
        $blueDotConnection = new Connection();
        $blueDotConnection->setPDO($this->doctrineConnection->getWrappedConnection());

        $blueDot = new BlueDot(null);
        $blueDot->setConnection($blueDotConnection);

        foreach ($this->blueDotApis as $apiDir) {
            $blueDot->repository()->putRepository($apiDir);
        }

        return $blueDot;
    }
}