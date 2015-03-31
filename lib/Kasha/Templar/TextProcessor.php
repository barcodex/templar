<?php

namespace Kasha\Templar;

use Kasha\Core\Config;
use Temple\Processor;

class TextProcessor
    extends Processor
{
    public static function doTemplate($moduleName, $templateName, $params)
    {
        Locator::getInstance()->setFolders(Config::getInstance()->get('folders')); // @TODO set automatically
        $templateText = Locator::getInstance()->getTemplate($moduleName, $templateName);

        return Processor::doText($templateText, $params);
    }
}
