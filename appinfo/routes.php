<?php 

namespace OCA\SalesQuestionnaire;

use \OCA\AppFramework\Routing\RouteConfig;
use \OCA\SalesQuestionnaire\DependencyInjection\DIContainer;

$routeConfig = new RouteConfig(new DIContainer(), $this, __DIR__ . '/routes.yml');
$routeConfig->register();