<?php

namespace Library\Infrastructure\FileUpload;

interface FileNamerInterface
{
    public function createName(array $options) : string;
}