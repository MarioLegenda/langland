<?php

namespace Library;

use Symfony\Component\HttpFoundation\File\File;

class ImageResize
{
    /**
     * @var int $width
     */
    private $width;
    /**
     * @var int $height
     */
    private $height;
    /**
     * @param File $image
     * @param string $path
     * @return bool
     */
    public function resizeAndSave(File $image, string $path)
    {
        if ($this->height === null or $this->width === null) {
            return false;
        }

        $size = getimagesize($image->getPathname());

        $src = imagecreatefromstring( file_get_contents($image->getPathname()) );
        $dst = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, $this->width, $this->height, $size[0], $size[1] );
        imagedestroy($src);

        if ($image->guessExtension() === 'jpg' or $image->guessExtension() === 'jpeg') {
            imagejpeg($dst, $path);
        }

        if ($image->guessExtension() === 'png') {
            imagepng($dst, $path);
        }

        imagedestroy($dst);

        $this->width = null;
        $this->height = null;
    }
    /**
     * @param mixed $width
     * @return ImageResize
     */
    public function setWidth($width) : ImageResize
    {
        $this->width = $width;

        return $this;
    }
    /**
     * @param mixed $height
     * @return ImageResize
     */
    public function setHeight($height) : ImageResize
    {
        $this->height = $height;

        return $this;
    }
}