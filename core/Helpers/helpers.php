<?php

use Chibi\App;
use Chibi\Exceptions\ViewNotFoundException;
use Chibi\Template\Template;

if (!function_exists('route')) {

    /**
     * Get the route path
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    function route($name, $params = [])
    {
        $router = app()->getContainer()->router;

        return $router->getUrlOfNamedRoute($name, $params);
    }
}


if (!function_exists('app')) {

    /**
     * Get The instance of the Application
     *
     * @return mixed
     */
    function app()
    {
        return App::getInstance();
    }
}

if (!function_exists('view')) {

    /**
     * Pass data to the view
     *
     * @param $view
     * @param array $variables
     * @throws ViewNotFoundException
     */
    function view($view, $variables = [])
    {
        $path = "app/Views/{$view}.chibi.php";
        if (!(file_exists($path))) {
            throw new ViewNotFoundException("The {$view} view is not found");
        }
        $template = new Template($path);
        $template->fill($variables)->compileView()->render();
        //require_once("app/views/{$view}.chibi.php");
    }
}

if (!function_exists('redirect')) {

    /**
     * Redirect to a specific path
     *
     * @param $path
     */
    function redirect($path)
    {
        header("Location: /{$path}");
    }
}

if (!function_exists('env')) {
    function env($key,$default = '') {
        return getenv($key) ? getenv($key) : $default;
    }
}

if (!function_exists('bdump')) {
    function bdump() {
        $args = func_get_args();

        foreach ($args as $key => $value) {
            dump($value);
        }
        echo '<style>pre.sf-dump .sf-dump-str{color: #3A69DB;}pre.sf-dump, pre.sf-dump .sf-dump-default{background-color: #F3F3F3;border:1px dashed #cfcfcf}pre.sf-dump .sf-dump-public{color: #333;}</style>';
        exit;
    }
}
