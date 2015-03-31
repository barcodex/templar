<?php

use Kasha\Generator\AppGenerator;

//print_r($argv);
//
if ($argc == 1) {
	printUsage();
} elseif ($argv[1] == '--help') {
	printHelp();
} else {
	$scriptName = array_shift($argv);
	$commandName = array_shift($argv);
	print_r($argv);

	switch ($commandName) {
		case 'create:app':
			createApp($argv);
			break;
		case 'create:module':
			createModule($argv);
			break;
		default:
			printUsage();
			break;
	}
}

function printUsage()
{
	print 'Usage: php generator.php [params]' . PHP_EOL;
	print '  --help for help' . PHP_EOL;
}

function printHelp()
{
	printUsage();
	print '  [params] can be one of the following:' . PHP_EOL;
	print '  create:app [[name]]' . PHP_EOL;
	print '  create:module [name]' . PHP_EOL;
}

function createApp($params)
{
	if (count($params) == 0) {
		$g = new AppGenerator(__DIR__);
	} else {
		$appName = $params[0];
		$g = new AppGenerator(dirname(__DIR__) . '/' . $appName);
	}
	$g->createApp();
}

function createModule($params)
{
	if (count($params) == 0) {
		printHelp();
	} else {
		$g = new AppGenerator(__DIR__);
		$g->createModule($params[0]);
	}
}
