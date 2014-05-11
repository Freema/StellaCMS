<?php

// absolute filesystem path to this web root
define('WWW_DIR', __DIR__);

// uncomment this line if you must temporarily take down your site for maintenance
// require APP_DIR . '/templates/maintenance.phtml';

// load bootstrap file
$container = require WWW_DIR . '/app/bootstrap.php';

$container->getService('application')->run();