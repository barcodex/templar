<?php

namespace Kasha\Templar;

use Temple\Processor;

class TextProcessor
	extends Processor
{
	public static function doTemplate($moduleName, $templateName, $params)
	{
		$templateText = Locator::getInstance()->getTemplate($moduleName, $templateName);

		return Processor::doText($templateText, $params);
	}

	public static function loopTemplate($moduleName, $templateName, $rows)
	{
		$templateText = Locator::getInstance()->getTemplate($moduleName, $templateName);

		return Processor::loopText($templateText, $rows);
	}
}
