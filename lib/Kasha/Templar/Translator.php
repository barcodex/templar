<?php

namespace Kasha\Templar;

class Translator
{
    private $translationFolder = '';

    public function __construct($translationFolder)
    {
        $this->translationFolder = $translationFolder;
    }

    public function translateTemplate($untranslated, $moduleName, $templateName, $language = null)
    {
		// $translations is expected to be an array
		$translations = Locator::getInstance()->getTemplateTranslation($moduleName, $templateName, $language);
		if (is_array($translations)) {
			$translations = array();
		}

        return TextProcessor::doTextVariation($untranslated, '[[', ']]', $translations);
    }
}
