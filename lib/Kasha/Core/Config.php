<?php

namespace Kasha\Core;

class Config
{
	private static $instance = array();

	private $config = array();

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Config();
		}

		return self::$instance;
	}
}
