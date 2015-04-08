<?php

namespace Kasha\Templar; // @TODO either rename namespace to Templar or the project to kasha-templar

use Temple\Util;

// @TODO shall we use kasha-caching, like kasha-model? It would make sense, but brings another dependency

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

		// check if all folders in the path exist for the $key
		$pathFolders = explode('/', $key);
		if (count($pathFolders) > 1) {
			$pureKeyName = array_pop($pathFolders); // do not create folder for the last element
			$path = $this->cacheFolder;
			foreach ($pathFolders as $folderName) {
				$path .= ($folderName . '/');
				if (!file_exists($path)) {
					mkdir($path);
				}
			}
		}

		return file_exists($fileName) ? file_get_contents($fileName) : '';
    }

    public function set($key, $content)
    {
        $fileName = $this->cacheFolder . $key . '.txt';
        file_put_contents($fileName, $content);
    }

}
