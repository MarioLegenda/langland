<?php

namespace Library\Infrastructure\FileUpload;

interface FileUploadInterface
{
    /**
     * @param \SplFileInfo $fileInfo
     * @param array $options
     * @return mixed
     */
    public function upload(\SplFileInfo $fileInfo, array $options = array());
    /**
     * @return array
     */
    public function getData(): array;
}