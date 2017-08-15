<?php

namespace Library;

use Library\Infrastructure\FileUpload\ImageResizeInterface;

class ImageResize implements ImageResizeInterface
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
     * @param \SplFileInfo $image
     * @param string $path
     * @return bool
     */
    public function resizeAndSave(\SplFileInfo $image, string $path)
    {
        if ($this->height === null or $this->width === null) {
            throw new \RuntimeException(
                sprintf('Width and height are not set. You have to set width and height before you call ImageResizeInterface::resizeAndSave')
            );
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
     * @return ImageResizeInterface
     */
    public function setWidth(int $width) : ImageResizeInterface
    {
        $this->width = $width;

        return $this;
    }
    /**
     * @param mixed $height
     * @return ImageResizeInterface
     */
    public function setHeight(int $height) : ImageResizeInterface
    {
        $this->height = $height;

        return $this;
    }
}