<?php
use Nette\Configurator;

// BASE definice
define('APP_DIR', WWW_DIR . '/app');
define('VENDOR_DIR', WWW_DIR . '/vendor');
define('LOG_DIR', WWW_DIR . '/log');
define('TEMP_DIR', WWW_DIR . '/temp');
define('UPLOAD_DIR', WWW_DIR . '/upload');


if(!file_exists(LOG_DIR)) {
    mkdir(LOG_DIR);
}
if(!file_exists(TEMP_DIR)) {
    mkdir(TEMP_DIR);
    mkdir(TEMP_DIR."/sessions/");
}
if(!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR);
}

// Load Nette Framework
require __DIR__ . '/../vendor/autoload.php';

// Configure application
$configurator = new Configurator;

$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(LOG_DIR);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
             ->addDirectory(APP_DIR)
             ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon', Configurator::AUTO);
$container = $configurator->createContainer();

return $container;
