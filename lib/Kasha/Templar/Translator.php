<?php

namespace Kasha\Templar;

class Translator
{
    private $translationFolder = '';

    public function __construct($translationFolder)
    {
        $this->translationFolder = $translationFolder;
    }

    public function translateTemplate($untranslated, $moduleName, $templateName)
    {
        return $untranslated;
    }
} 