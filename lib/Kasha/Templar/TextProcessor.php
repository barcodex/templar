<?php

namespace Kasha\Templar;

use Temple\Processor;

class TextProcessor
	extends Processor
{
	public static function doSqlTemplate($moduleName, $templateName, $params = array())
	{
		$templateText = Locator::getInstance()->getSqlTemplate($moduleName, $templateName);

		return Processor::doText($templateText, $params);
	}

	public static function doTemplate($moduleName, $templateName, $params = array())
	{
		$templateText = Locator::getInstance()->getTemplate($moduleName, $templateName);

		return Processor::doText($templateText, $params);
	}

	public static function loopTemplate($moduleName, $templateName, $rows = array())
	{
		$templateText = Locator::getInstance()->getTemplate($moduleName, $templateName);

		return count($rows) > 0 ? Processor::loopText($templateText, $rows) : '';
	}
}
