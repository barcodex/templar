<?php

namespace Kasha\Generator;

class AppGenerator
{
	private $rootPath = '';

	public function __construct($rootPath)
	{
		$this->rootPath = $rootPath;
		// Try to create root path if it does not exist
		if (!file_exists($rootPath)) {
			mkdir($rootPath);
		}
	}

	public function createApp()
	{
		$this->createFolderIfNotExists('/app');
		$this->createFolderIfNotExists('/app/cache');
		$this->createFolderIfNotExists('/app/modules');
		$this->createFolderIfNotExists('/app/modules.translation');
	}

	public function createModule($moduleName)
	{
		$this->createFolderIfNotExists('/app');
		$this->createFolderIfNotExists('/app/modules');
		$this->createFolderIfNotExists('/app/modules/' . $moduleName);
		$this->createFolderIfNotExists('/app/modules/' . $moduleName . '/actions');
		$this->createFolderIfNotExists('/app/modules/' . $moduleName . '/templates');
		$this->createFolderIfNotExists('/app/modules/' . $moduleName . '/sql');
	}

	private function createFolderIfNotExists($folderPath) {
		if (!file_exists($this->rootPath . $folderPath)) {
			mkdir($this->rootPath . $folderPath);
		}
	}
}
