<?php

namespace Spyrek\Core;

use Spyrek\Core\Request;

class App
{

    /** The name of the class for the current view */
    private $controller = 'Home';

    /** The method to run in the class */
    private $method = 'index';
    public function splitUrl()
    {
        $url = trim($_SERVER['REQUEST_URI'], "/");
        $url = preg_replace('/\?.*/', "", $url);
        $url = empty($url) ? 'home' : $url;
        $url = explode('/', trim($url, '/'));

        return $url;
    }


    /**
     * The Route Loader */
    public function loadController()
    {
        $current_route = route()->current();

        if (!$current_route) {
            header("HTTP/1.1 404 Not Found");
            /** Show the 404 page when page controllor is not found */
            $this->controller = 'src\Controllers\_404';
        } else {

            $controller = $current_route->controller;

            $this->controller = $controller;
            $this->method = $current_route->method;
        }

        $controller = new $this->controller;
        /**Call the method selected from the page controller */
        call_user_func_array([$controller, $this->method], []);
    }
}
