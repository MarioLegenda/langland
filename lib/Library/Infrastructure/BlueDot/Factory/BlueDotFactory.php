<?php

namespace Library\Infrastructure\BlueDot\Factory;

use BlueDot\BlueDot;
use BlueDot\Database\Connection;
use Doctrine\DBAL\Connection as DoctrineConnection;

class BlueDotFactory
{
    /**
     * @var string $blueDotDir
     */
    private $blueDotDir;
    /**
     * @var DoctrineConnection $doctrineConnection
     */
    private $doctrineConnection;
    /**
     * BlueDotFactory constructor.
     * @param DoctrineConnection $doctrineConnection
     * @param string $blueDotDir
     */
    public function __construct(
        DoctrineConnection $doctrineConnection,
        string $blueDotDir
    ) {
        $this->doctrineConnection = $doctrineConnection;
        $this->blueDotDir = $blueDotDir;
    }
    /**
     * @return BlueDot
     */
    public function createBlueDot(): BlueDot
    {
        $blueDotConnection = new Connection();
        $blueDotConnection->setPDO($this->doctrineConnection->getWrappedConnection());

        $blueDot = new BlueDot($this->blueDotDir, $blueDotConnection);

        return $blueDot;
    }
}