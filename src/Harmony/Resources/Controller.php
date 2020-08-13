<?php
namespace Harmony\Resources;

use Twig\Environment as TwigEnvironment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Controller
{
    public Session $session;
    public Environment $env;
    public Request $request;

    public function __construct()
    {
        $this->session = new Session();
        $this->env = new Environment();
        $this->request = new Request();

        $this->session->startSession();
    }

    public function render(String $view_path, ?Array $parameters=[])
    {
        $view_path = str_replace(".", "/", $view_path);
        try {
            $twig_loader = new FilesystemLoader(ROOT_DIR . "views");
        } catch (LoaderError $e) {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 500);
            echo "Error 500 - Internal Error<br/>Harmony has crashed during views loading.
                    <br/>Seems like the views folder doesn't exists.<br/>Please contact the administrator.";
            die();
        }

        try {
            $twig = new TwigEnvironment($twig_loader, [
                "cache" => ROOT_DIR . "cache/twig",
                "auto_reload" => (!$this->env->get("cache_views"))
            ]);
        } catch (Error $e) {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 500);
            echo "Error 500 - Internal Error<br/>Harmony has crashed while creating Twig Environment.
                    <br/>Please contact the administrator.";
            die();
        }

        try {
            $twig->addFunction(
                new TwigFunction(
                    'csrf_token',
                    function ($lock_to = null) {
                        if (is_null($this->session->restoreObject('token'))) {
                            $this->session->storeObject('token', bin2hex(random_bytes(32)));
                        }
                        if (is_null($this->session->restoreObject('token2'))) {
                            $this->session->storeObject('token2', bin2hex(random_bytes(32)));
                        }
                        if (is_null($lock_to)) {
                            return $this->session->restoreObject('token');
                        }
                        return hash_hmac('sha256', $lock_to, $this->session->restoreObject('token2'));
                    }
                )
            );

            $twig->addFunction(
                new TwigFunction(
                    'load_asset',
                    function ($asset_path) {
                        return $this->env->get("context")."/public/assets/".$asset_path;
                    }
                )
            );

            $twig->addFunction(
                new TwigFunction(
                    'route',
                    function ($route_path) {
                        $route_path = ltrim($route_path, '/');
                        return $this->env->get("context")."/".$route_path;
                    }
                )
            );
        } catch (Error $e) {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 500);
            echo "Error 500 - Internal Error<br/>Harmony has crashed while registring HarmonyFunctions on Twig.
                    <br/>Please contact the administrator.";
            die();
        }

        try {
            echo $twig->render($view_path.".html.twig", $parameters);//parameters
        } catch (Error $e) {
            header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Error", true, 500);
            echo "Error 500 <br/>Harmony has crashed during template renderization.
                    <br/>Please contact the administrator.
                    <br/>[\"path\" => {$view_path}.html.twig]";
            echo $e;
            die();
        }
    }
}