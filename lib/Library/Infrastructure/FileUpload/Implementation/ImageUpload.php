<?php

namespace Library\Infrastructure\FileUpload\Implementation;

use Library\Infrastructure\FileUpload\FileUploadInterface;
use Library\Infrastructure\FileUpload\FileNamerInterface;
use Library\Infrastructure\FileUpload\ImageResizeInterface;

class ImageUpload implements FileUploadInterface
{
    /**
     * @var string $imageDir
     */
    private $imageDir;
    /**
     * @var ImageResizeInterface $imageResize
     */
    private $imageResize;
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
    private $data = [];
    /**
     * FileUploader constructor.
     * @param array $uploadDirs
     * @param ImageResizeInterface $imageResize
     * @param FileNamerInterface $fileNamer
     */
    public function __construct(array $uploadDirs, ImageResizeInterface $imageResize, FileNamerInterface $fileNamer)
    {
        $this->imageDir = realpath($uploadDirs['image_upload_dir']);
        $this->relativePath = $uploadDirs['relative_image_path'];
        $this->imageResize = $imageResize;
        $this->fileNamer = $fileNamer;
    }
    /**
     * @inheritdoc
     */
    public function upload(\SplFileInfo $fileInfo, array $options = array())
    {
        $fileName = $this->fileNamer->createName($options).'.'.$fileInfo->guessExtension();
        $originalName = $fileInfo->getClientOriginalName();

        $path = $this->imageDir.'/'.$fileName;

        if (array_key_exists('resize', $options)) {
            $this->resizeAndSave($options['resize'], $fileInfo, $path);
        } else {
            $fileInfo->move($this->imageDir, $fileName);
        }

        $this->data = array(
            'fileName' => $fileName,
            'relativePath' => $this->relativePath,
            'targetDir' => $this->imageDir,
            'originalName' => $originalName,
            'fullPath' => $this->imageDir.'/'.$fileName,
        );
    }
    /**
     * @return array
     */
    public function getData(): array
    {
        if (empty($this->data)) {
            $message = sprintf('Image upload data did not formulate correctly.');

            throw new \RuntimeException($message);
        }

        return $this->data;
    }

    /**
     * @param array $measurements
     * @param $file
     * @param $path
     */
    public function resizeAndSave(array $measurements, $file, $path)
    {
        $this->imageResize
            ->setWidth($measurements['width'])
            ->setHeight($measurements['height'])
            ->resizeAndSave($file, $path);
    }
}