<?php

namespace Library\Infrastructure\FileUpload;

interface ImageResizeInterface
{
    public function resizeAndSave(\SplFileInfo $image, string $path);
    public function setWidth(int $width) : ImageResizeInterface;
    public function setHeight(int $height) : ImageResizeInterface;
}