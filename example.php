<?php

require_once "vendor/autoload.php";

use Kasha\Templar\TextProcessor;
use Kasha\Core\Config;

// this should be handled while template bootstraps
Config::getInstance()->set('folders', array('app' => __DIR__ . '/app/', 'shared' => __DIR__ . '/shared/'));

$params = array('name' => array('first' => 'John', 'last' => 'Doe'));
print TextProcessor::doTemplate('main', 'index', $params) . PHP_EOL;
