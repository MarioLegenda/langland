<?php

namespace TestLibrary;

class Seed
{
    /**
     * @var Seed $instance
     */
    private static $instance;
    /**
     * @var bool $hasSeeded
     */
    private $hasSeeded = false;
    /**
     * @return Seed
     */
    public static function inst()
    {
        self::$instance = (self::$instance instanceof self) ? self::$instance : new self();

        return self::$instance;
    }

    public function seed()
    {
        if ($this->hasSeeded) {
            return null;
        }

        $this->hasSeeded = true;

        exec('/usr/bin/php /var/www/bin/console langland:reset');
        exec('/usr/bin/php /var/www/bin/console langland:seed');
    }
    /**
     * @return Seed
     */
    public function reset() : Seed
    {
        exec('/usr/bin/php /var/www/bin/console langland:reset');

        return $this;
    }
    /**
     * @return Seed
     */
    public function populate() : Seed
    {
        exec('/usr/bin/php /var/www/bin/console langland:seed');

        return $this;
    }
}