<?php

class ProjectBootstrap
{
    /**
     * @var string $env
     */
    private $env;
    /**
     * @var bool|string
     */
    private $path;
    /**
     * ProjectBootstrap constructor.
     * @param string $env
     */
    public function __construct(string $env)
    {
        $this->env = $env;

        $this->path = ($env === 'dev' or $env === 'prod') ?
            realpath(__DIR__.'/../web') :
            realpath(__DIR__.'/../tests');
    }
    /**
     * @param array $directories
     * @param array $exclude
     */
    public function bootstrapDirectories(array $directories, array $exclude)
    {
        $this->createDirectory($this->path.'/uploads');

        foreach ($directories as $name => $directory) {
            if (in_array($name, $exclude) === false) {
                $realPath = realpath($directory);

                if ($realPath !== false) {
                    continue;
                }

                $this->createDirectory($directory);
            }
        }

        $this->createDirectory($this->path.'/uploads/sounds/temp');
    }
    /**
     * @param string $dir
     */
    public function createDirectory(string $dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }
    /**
     * @return bool
     */
    public function isBootstrapped() : bool
    {
        if (!is_dir($this->path.'/uploads') === true) {
            return false;
        }

        if (!is_dir($this->path.'/uploads/images') === true) {
            return false;
        }

        if (!is_dir($this->path.'/uploads/sounds') === true) {
            return false;
        }

        if (!is_dir($this->path.'/uploads/sounds/temp') === true) {
            return false;
        }

        return true;
    }
}