<?php
// Harmony PHP Framework - v1.0b (RC1)
// Felipe Geroldi (https://github.com/feriip3d/harmony)
define("ROOT_DIR", str_replace("webroot", '', __DIR__));
require_once ROOT_DIR."config/bootstrap.php";
use Harmony\Resources\Router;

$router = new Router();
require_once ROOT_DIR."config/routes.php";
$router->dispatch();

