<?php

namespace Library\App\Factory;

use BlueDot\BlueDot;
use Doctrine\DBAL\Connection;
use BlueDot\Database\Connection as BlueDotConnection;

class BlueDotFactory
{
    /**
     * @param Connection $connection
     * @return BlueDot
     */
    public static function createBlueDot(Connection $connection)
    {
        $blueDotConnection = new BlueDotConnection();
        $blueDotConnection->setPDO($connection->getWrappedConnection());

        $blueDot = new BlueDot();

        $blueDot
            ->setConnection($blueDotConnection)
            ->api()
            ->putAPI(__DIR__.'/../../Armor/blue_dot_api/user.yml');

        return $blueDot;
    }
}