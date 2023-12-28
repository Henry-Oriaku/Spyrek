<?php

namespace src\Core;

class Route
{
    private static $current_group_name = null;
    private static $current_namespace = null;
    static $routes = [];
    public static function post($path, $controller, $method)
    {
        $group = Route::$current_group_name;
        Route::$routes[$path . "::post"] = ["type" => "post", "route" =>  $group . $path, "controller" => Route::$current_namespace . $controller, "method" => $method];
    }


    public static function get($path, $controller, $method)
    {
        $group = Route::$current_group_name;
        Route::$routes[$path . ":get"] = ["type" => "get", "route" => $group . $path, "controller" => Route::$current_namespace . $controller, "method" => $method];
    }
    public static function patch($path, $controller, $method)
    {
        $group = Route::$current_group_name;
        Route::$routes[$path . "::patch"] = ["type" => "patch", "route" => $group . $path, "controller" => Route::$current_namespace . $controller, "method" => $method];
    }
    public static function delete($path, $controller, $method)
    {
        $group = Route::$current_group_name;
        Route::$routes[$path . "::delete"] = ["type" => "delete", "route" => $group . $path, "controller" => Route::$current_namespace . $controller, "method" => $method];
    }
    public static function api($path, $controller)
    {
        Route::post($path, $controller, "store");
        Route::get($path, $controller, "index");
        Route::patch($path, $controller, "update");
        Route::delete($path,  $controller, "delete");
    }

    /**
     * @param array $config ["prefix" => null, "namespace" => null]
     */
    public static function group($config, \Closure $closure)
    {
        Route::$current_group_name = ($config["prefix"] ?? "") . "/";
        Route::$current_namespace = ($config["namespace"] ?? "") . "\\";
        $closure();
        Route::$current_group_name = Route::$current_namespace = null;
    }
    public function current()
    {
        $url = request()->full_path();
        $method = request()->method();
        $match = [];
        $routes = Route::$routes;
        foreach ($routes as $value) {
            if ($value["route"] == $url && strtolower($method) == $value['type']) {
                return (object)$value;
            };
        }
        return false;
    }
}
