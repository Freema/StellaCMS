<?php

use Nette\Configurator;
/**
 * My Application bootstrap file.
 */

// BASE definice
define('APP_DIR', WWW_DIR . '/app');
define('VENDOR_DIR', WWW_DIR . '/vendor');

// Load Nette Framework
require __DIR__ . '/../vendor/autoload.php';

// Configure application
$configurator = new Configurator;

// Enable Nette Debugger for error visualisation & logging
//$configurator->setDebugMode(FALSE);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
             ->addDirectory(APP_DIR)
             ->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

if (!is_writable($container->expand('%tempDir%'))) {
    throw new Exception("Make directory '" . $container->parameters['tempDir'] . "' writable!");
}

return $container;
