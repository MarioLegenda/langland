<?php

namespace API\ApiBundle\Factory;

use BlueDot\BlueDot;
use Doctrine\DBAL\Connection;
use BlueDot\Database\Connection as BlueDotConnection;

class BlueDotFactory
{
    /**
     * @param Connection $connection
     * @param string $blueDotDir
     * @return BlueDot
     */
    public function createBlueDot(Connection $connection = null, string $blueDotDir)
    {
        $blueDot = new BlueDot();

        if (!is_null($connection)) {
            $blueDotConnection = new BlueDotConnection();
            $blueDotConnection->setPDO($connection->getWrappedConnection());

            $blueDot->setConnection($blueDotConnection);
        }

        $blueDot->api()->putAPI($blueDotDir);

        return $blueDot;
    }
}