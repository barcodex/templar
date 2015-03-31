<?php

require_once "vendor/autoload.php";

use Kasha\Templar\TextProcessor;
use Kasha\Templar\Locator;

// this should be handled while framework bootstraps
$folders = array('app' => __DIR__ . '/app/', 'shared' => __DIR__ . '/shared/');
Locator::getInstance()->setFolders($folders);
Locator::getInstance()->setLanguage('en');

$params = array('name' => array('first' => 'John', 'last' => 'Doe'));
print TextProcessor::doTemplate('main', 'index', $params) . PHP_EOL;
