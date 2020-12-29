<?php

namespace lav45\fileUpload\behaviors;

use creocoder\flysystem\Filesystem;
use yii\di\Instance;

/**
 * Class FSUploadBehavior
 * @package lav45\fileUpload\behaviors
 */
class FSUploadBehavior extends UploadBehavior
{
    /** @var string|array|Filesystem */
    public $fs = 'fs';

    /**
     * @return Filesystem
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFs()
    {
        return Instance::ensure($this->fs, Filesystem::class);
    }

    /**
     * @param string $file_name
     * @throws \yii\base\InvalidConfigException
     */
    protected function moveFile($file_name)
    {
        $tempFile = $this->getTempDir() . '/' . $file_name;
        $uploadFile = $this->getUploadDir() . '/' . $file_name;

        $stream = fopen($tempFile, 'rb+');
        if ($stream === false) {
            throw new Exception("File '{$file_name}' not found!");
        }

        $this->getFs()->putStream($uploadFile, $stream);
    }

    /**
     * @param string $file_name
     * @throws \yii\base\InvalidConfigException
     */
    protected function unlinkFile($file_name)
    {
        $uploadFile = $this->getUploadDir() . '/' . $file_name;
        $this->getFs()->delete($uploadFile);
    }
}