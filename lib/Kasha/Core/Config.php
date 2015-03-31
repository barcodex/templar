<?php

namespace Kasha\Core;

class Config
{
	/** @var Config */
	private static $instance = null;

	private $config = array();

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Config();
		}

		return self::$instance;
	}

	public function get($name, $default = null) {
		return \Temple\Util::lavnn($name, $this->config, $default);
	}

	public function set($name, $value) {
		return $this->config[$name] = $value;
	}

}
