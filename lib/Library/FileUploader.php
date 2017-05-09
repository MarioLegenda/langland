<?php

namespace Library;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var string $imageDir
     */
    private $imageDir;
    /**
     * @var string $soundDir
     */
    private $soundDir;
    /**
     * @var ImageResize $imageResize
     */
    private $imageResize;
    /**
     * @var FileNamer $fileNamer
     */
    private $fileNamer;
    /**
     * @var string $fileName
     */
    private $fileName;
    /**
     * @var mixed $relativePath
     */
    private $relativePath;
    /**
     * @var string $originalName
     */
    private $originalName;
    /**
     * @var array $data
     */
    private $data = array();
    /**
     * FileUploader constructor.
     * @param array $uploadDirs
     * @param ImageResize $imageResize
     * @param FileNamer $fileNamer
     */
    public function __construct(array $uploadDirs, ImageResize $imageResize, FileNamer $fileNamer)
    {
        $this->relativePath = $uploadDirs['relative_path'];
        $this->imageDir = realpath($uploadDirs['image_upload_dir']);
        $this->soundDir = realpath($uploadDirs['sound_upload_dir']);
        $this->imageResize = $imageResize;
        $this->fileNamer = $fileNamer;
    }
    /**
     * @param UploadedFile $file
     * @param array $options
     * @return array
     */
    public function uploadImage(UploadedFile $file, array $options = array())
    {
        $fileName = $this->fileNamer->createName($options).'.'.$file->guessExtension();
        $originalName = $file->getClientOriginalName();

        $path = $this->imageDir.'/'.$fileName;

        if (array_key_exists('resize', $options)) {
            $this->resizeAndSave($options['resize'], $file, $path);
        } else {
            $file->move($this->imageDir, $fileName);
        }

        $this->data = array(
            'fileName' => $fileName,
            'targetDir' => $this->imageDir,
            'originalName' => $originalName,
            'fullPath' => $this->relativePath.'/'.$fileName,
        );
    }

    public function uploadSound(UploadedFile $file, array $options)
    {
        $this->fileName = $this->fileNamer->createName($options).'.mp3';
        $this->originalName = $file->getClientOriginalName();

        $file->move($this->soundDir.'/temp', $this->fileName);

        $src = $this->soundDir.'/temp/'.$this->fileName;
        $dest = $this->soundDir.'/'.$this->fileName;

        exec(sprintf('/usr/bin/sox -t %s %s %s', 'mp3', $src, $dest), $output);

        if (file_exists($src)) {
            unlink($src);
        }
    }
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }
    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }
    /**
     * @param string $originalName
     */
    public function setOriginalName(string $originalName)
    {
        $this->originalName = $originalName;
    }
    /**
     * @return string
     */
    public function getImageDir(): string
    {
        return $this->imageDir;
    }
    /**
     * @param string $imageDir
     */
    public function setImageDir(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }
    /**
     * @return string
     */
    public function getSoundDir(): string
    {
        return $this->soundDir;
    }
    /**
     * @param string $soundDir
     */
    public function setSoundDir(string $soundDir)
    {
        $this->soundDir = $soundDir;
    }
    /**
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    public function resizeAndSave(array $measurements, $file, $path)
    {
        $this->imageResize
            ->setWidth($measurements['width'])
            ->setHeight($measurements['height'])
            ->resizeAndSave($file, $path);
    }
}