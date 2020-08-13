<?php
namespace Harmony\Resources;


class Response
{
    public function sendRedirect($url)
    {
        $env = new Environment();
        header("Location: ".$env->get("context").$url);
        die();
    }
}