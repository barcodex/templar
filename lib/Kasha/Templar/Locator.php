<?php

namespace Kasha\Templar;

use Temple\Util;

/**
 * Class Locator
 *
 * Use this class to locate files and get their contents
 */
class Locator
{
    static $instance = null;

    private $folders = array(); // expected keys: 'app', 'shared'; paths should end with a slash
	private $language = 'en';

    private $cache = null;
    private $translator = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Locator();
        }

        return self::$instance;
    }

    public function setFolders($folders)
    {
        $this->folders = $folders;
    }

    public function getFolders()
    {
        return $this->folders;
    }

    public function getFolderPath($name)
    {
        return Util::lavnn($name, $this->folders, '');
    }

    public function setLanguage($language)
    {
		$this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Checks if module with a given name exists
     *
     * @param $moduleName
     *
     * @param bool $searchSharedModule
     * @return bool
     */
    public static function moduleExists($moduleName, $searchSharedModule = true)
    {
        return self::getInstance()->appFileExists("modules/$moduleName") ?
            true :
            ($searchSharedModule ? self::getInstance()->sharedFileExists("modules/$moduleName") : false);
    }

    /**
     * Checks if file with given name exists in given module
     *
     * @param string $moduleName
     * @param string $fileName
     *
     * @param bool $searchSharedModule
     * @return bool
     */
    public static function moduleFileExists($moduleName, $fileName, $searchSharedModule = true)
    {
        return self::getInstance()->appFileExists("modules/$moduleName/$fileName") ?
            true :
            ($searchSharedModule ? self::getInstance()->sharedFileExists("modules/$moduleName/$fileName") : false);
    }

    /**
     * @param $moduleName
     * @param $templateName
     *
     * @return bool
     */
    public static function templateFileExists(
        $moduleName,
        $templateName
    ) {
        return self::moduleFileExists($moduleName, "templates/$templateName.html");
    }

    /**
     * @param $moduleName
     * @param $templateName
     *
     * @return bool
     */
    public static function sqlTemplateFileExists(
        $moduleName,
        $templateName
    ) {
        return self::moduleFileExists($moduleName, "sql/$templateName.sql");
    }

    /**
     * Get contents of module name
     *
     * @param string $moduleName
     * @param string $fileName
     *
     * @return string
     */
    public function getModuleFile(
        $moduleName,
        $fileName
    ) {
        $fileName = "modules/$moduleName/$fileName";
        if ($this->appFileExists($fileName)) {
            return $this->appFileGetContents($fileName);
        } elseif ($this->sharedFileExists($fileName)) {
            return $this->sharedFileGetContents($fileName);
        } else {
            // @TODO throw and exception if in the strict mode
            return '';
        }
    }

	public function getTemplateTranslation(
		$moduleName,
		$fileName,
		$language = null
	) {
		if (is_null($language)) {
			$language = $this->getLanguage();
		}
		$fileName = $this->getFolderPath('app') . "modules.translation/$language/$moduleName/$fileName.txt";
		if (file_exists($fileName)) {
			$json = file_get_contents($fileName);
		} else {
			// @TODO throw and exception if in the strict mode
			$json = '';
		}

		return json_decode($json, true);
	}

	public static function getAppModulePath($moduleName)
    {
        return self::getInstance()->getFolderPath('app') . "modules/$moduleName/";
    }

    public static function getAppModuleTranslationPath($moduleName, $language = null)
    {
		if (is_null($language)) {
			$language = self::getInstance()->getLanguage();
		}
		return self::getInstance()->getFolderPath('app') . "modules.translation/$language/$moduleName/";
    }

    public static function getAppModuleFilePath($moduleName, $fileName)
    {
        return self::getInstance()->getFolderPath('app') . "modules/$moduleName/$fileName";
    }

    public static function getSharedModuleFilePath($moduleName, $fileName)
    {
        return self::getInstance()->getFolderPath('shared') . "modules/$moduleName/$fileName";
    }

    /**
     * Gets SQL template addressed by module and template name
     *
     * @param string $moduleName
     * @param string $templateName
     *
     * @return string
     */
    public function getSqlTemplate(
        $moduleName,
        $templateName
    ) {
        $sqlTemplate = '';

        $filename = "modules/$moduleName/sql/$templateName.sql";
        if ($this->appFileExists($filename)) {
            $sqlTemplate = $this->appFileGetContents($filename);
        } elseif ($this->sharedFileExists($filename)) {
            $sqlTemplate = $this->sharedFileGetContents($filename);
        } else {
            // @TODO throw and exception if in the strict mode
        }

        return $sqlTemplate;
    }

	/**
	 * Gets HTML template addressed by module and template name (and, optionally, skin prefix)
	 *
	 * @param string $moduleName
	 * @param string $templateName
	 * @param null string $language
	 *
	 * @return string
	 */
    public function getTemplate(
        $moduleName,
        $templateName,
		$language = null
    ) {
		if (is_null($language)) {
			$language = $this->language;
		}
        $key = "$moduleName:$templateName:$language";
        // First, try to find a translated template in the cache
        $cached = $this->getCachedTemplate($key);
        if (!$cached) {
            // Get template text, translated into the session language
            $translated = $this->translateTemplate($moduleName, $templateName);
            // Cache translated template (depends on environment configuration)
//            $this->setCachedTemplate($key, $translated);

            return $translated;
        } else {
            // @TODO throw and exception if in the strict mode
            return $cached;
        }
    }

    private function getCachedTemplate($key)
    {
        if (is_null($this->cache)) {
            $this->cache = new Cache($this->getFolderPath('cache'));
        }

        return $this->cache->get($key);
    }

    private function setCachedTemplate($key, $content)
    {
        if (is_null($this->cache)) {
            $this->cache = new Cache($this->getFolderPath('cache'));
        }

        $this->cache->set($key, $content);
    }

    private function translateTemplate($moduleName, $templateName)
    {
        $untranslated = $this->getModuleFile($moduleName, "templates/$templateName.html");

        if (is_null($this->translator)) {
            $this->translator = new Translator($this->getFolderPath('app') . 'translations');
        }

        return $this->translator->translateTemplate($untranslated, $moduleName, $templateName);
    }

    public function appFileExists($filePath)
    {
        return file_exists($this->getFolderPath('app') . $filePath);
    }

    public function sharedFileExists($filePath)
    {
        return file_exists($this->getFolderPath('shared') . $filePath);
    }

    public function appFileGetContents($filePath)
    {
        return file_get_contents($this->getFolderPath('app') . $filePath);
    }

    public function sharedFileGetContents($filePath)
    {
        return file_get_contents($this->getFolderPath('shared') . $filePath);
    }

    public function appFilePutContents($filePath, $contents)
    {
        return file_put_contents($this->getFolderPath('app') . $filePath, $contents);
    }

    public function sharedFilePutContents($filePath, $contents)
    {
        return file_put_contents($this->getFolderPath('shared') . $filePath, $contents);
    }

}
