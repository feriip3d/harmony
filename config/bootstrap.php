<?php
// Loading autoloader
require_once ROOT_DIR."vendor/autoload.php";

try {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR);
    $dotenv->load();    
} catch (Exception $e) {
    header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 500);
    echo "Error 500 - Internal Error<br/>Harmony has failed to load .env file.
            <br/>Please contact the administrator.";
    die();
}
