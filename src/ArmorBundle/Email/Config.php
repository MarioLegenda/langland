<?php

namespace ArmorBundle\Email;

class Config
{
    /**
     * @var array $config
     */
    private $config;
    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    /**
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function get(string $name, string $value)
    {
        return $this->config[$name][$value];
    }
}