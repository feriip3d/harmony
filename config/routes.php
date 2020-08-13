<?php
require_once "bootstrap.php";

//Router::auth();
$router->setPageNotImplementedHandler(function() {
    return "Não implementado";
});

$router->setPageNotFoundHandler(function() {
    return "Não encontrado";
});

$router->setPageMethodNotAllowedHandler(function() {
    return "Metodo nao permitido";
});

$router->get('/', "IndexController|index");

//Router::group("/login");
//Router::get('/', "LoginController|index");
