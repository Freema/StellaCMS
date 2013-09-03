<?php

use AdminModule\Forms\CheckboxList;
use AdminModule\Forms\MultyFileUpload;
use Models\PageRouter\PageRouter;
use Nella\Console\Config\Extension as Extension2;
use Nella\Doctrine\Config\Extension;
use Nella\Doctrine\Config\MigrationsExtension;
use Nette\Config\Configurator;

/**
 * My Application bootstrap file.
 */

// Load Nette Framework
require LIBS_DIR . '/autoload.php';


// Configure application
$configurator = new Configurator;

// Enable Nette Debugger for error visualisation & logging
//$configurator->setDebugMode($configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->register();

Extension2::register($configurator);
Extension::register($configurator);
MigrationsExtension::register($configurator);

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

if (!is_writable($container->expand('%tempDir%'))) {
    throw new Exception("Make directory '" . $container->parameters['tempDir'] . "' writable!");
}

$pageRouter = new PageRouter();
$container->router[] = $pageRouter->createRouter();

MultyFileUpload::register();
CheckboxList::register();

// Configure and run the application!
$container->application->run();
