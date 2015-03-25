<?php

namespace Kasha\Templar;

use Temple\Util;

/**
 * Class Cache
 *
 * Use this class to manage the cache of templates
 */
class Cache
{
    private $cacheFolder = '';

    public function __construct($cacheFolder)
    {
        $this->cacheFolder = $cacheFolder;
    }

    public function get($key)
    {
        $fileName = $this->cacheFolder . $key . '.txt';

        return file_exists($fileName) ? file_get_contents($fileName) : '';
    }

    public function set($key, $content)
    {
        $fileName = $this->cacheFolder . $key . '.txt';
        file_put_contents($fileName, $content);
    }
}