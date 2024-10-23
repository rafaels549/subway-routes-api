<?php

namespace Rafael\SubwayRoutesApi\Public;

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

(require __DIR__ . '/../src/Controllers/UserController.php')($app);
(require __DIR__ . '/../src/Controllers/ManufacturerController.php')($app);

$app->run();
