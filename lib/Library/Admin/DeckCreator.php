<?php

namespace Library\Admin;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class DeckCreator
{
    /**
     * [__construct description]
     * @param string $path [description]
     */
    private $path;
    /**
     * [__construct description]
     * @param string $path [description]
     */
    private $fileName;
    /**
     * [__construct description]
     * @param string $path [description]
     */
    public function __construct(string $path)
    {
        if (!is_readable($path)) {
            throw new FileNotFoundException('Deck creating file not readable or not exists');
        }

        $this->path = $path;
    }
    /**
     * [setFileName description]
     * @param  string      $fileName [description]
     * @return DeckCreator           [description]
     */
    public function setFileName(string $fileName) : DeckCreator
    {
        $this->fileName = $fileName;

        return $this;
    }
    /**
     * [createDeckTwigFile description]
     */
    public function createDeckTwigFile($data)
    {
        $path = $this->path.'/'.preg_replace('#\s#', '_', trim($this->fileName));

        if (is_string($data)) {
            file_put_contents($path, $data);
        } else {
            file_put_contents($path, '');
        }
    }
}
