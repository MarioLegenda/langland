<?php

namespace Library\Infrastructure\FileUpload\Implementation;

use Library\Infrastructure\FileUpload\FileUploadInterface;
use Library\Infrastructure\FileUpload\FileNamerInterface;

class AudioUpload implements FileUploadInterface
{
    /**
     * @var string $soundDir
     */
    private $soundDir;
    /**
     * @var FileNamerInterface $fileNamer
     */
    private $fileNamer;
    /**
     * @var mixed $relativePath
     */
    private $relativePath;
    /**
     * @var array $data
     */
    private $data = array();
    /**
     * FileUploader constructor.
     * @param array $uploadDirs
     * @param FileNamerInterface $fileNamer
     */
    public function __construct(array $uploadDirs, FileNamerInterface $fileNamer)
    {
        $this->soundDir = realpath($uploadDirs['sound_upload_dir']);
        $this->fileNamer = $fileNamer;
    }
    /**
     * @inheritdoc
     */
    public function upload(\SplFileInfo $file, array $options = array())
    {
        $fileName = $this->fileNamer->createName($options).'.mp3';
        $originalName = $file->getClientOriginalName();

        $file->move($this->soundDir.'/temp', $fileName);

        $src = $this->soundDir.'/temp/'.$fileName;
        $dest = $this->soundDir.'/'.$fileName;

        exec(sprintf('/usr/bin/sox -t %s %s %s 2> /dev/null', 'mp3', $src, $dest), $output);

        if (file_exists($src)) {
            unlink($src);
        }

        $this->data = array(
            'fileName' => $fileName,
            'targetDir' => $this->soundDir,
            'originalName' => $originalName,
            'fullPath' => '/uploads/sounds/'.$fileName,
        );
    }
    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        if (empty($this->data)) {
            $message = sprintf('Sound upload data did not formulate correctly.');

            throw new \RuntimeException($message);
        }

        return $this->data;
    }
}