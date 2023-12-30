<?php

namespace Spyrek\Core;

class Request
{
    private $full_path = null;
    private $raw_path = null;
    private $method;
    private $query;
    private $route;
    private $headers;
    public function __construct()
    {
        // RETRIEVE HEADERS START
        $headers = $_SERVER;
        foreach ($headers as $key => $value) {
            $search = false;
            preg_match("/HTTP_(.*)/", $key, $search);
            if ($search) {
                $this->headers[$search[1]] = $value;
            }
        }
        // RETRIEVE HEADERS END

        $url = trim($_SERVER['REQUEST_URI'], "/");
        $this->raw_path = $url;
        $this->parseQuery();

        $url = preg_replace('/\?.*/', "", $url);
        $this->full_path = $url;
        $url = empty($url) ? 'home' : $url;
        $url = explode('/', trim($url, '/'));
        $this->route = $url[0];

        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function full_path()
    {
        return $this->full_path;
    }

    public function method()
    {
        return $this->method;
    }
    public function route()
    {
        return $this->route;
    }
    /**
     * Extract the Query in to An Array
     */
    private function parseQuery()
    {
        $queries = array();
        preg_match_all("/(\w+=[^&]*)/", $this->raw_path, $queries);
        if ($queries) {
            array_map(function ($query) {
                $raw = explode("=", $query);
                $key = $raw[0];
                $value = $raw[1];

                $this->query[$key] = $value;
            }, $queries[1]);
        }
    }
    /**
     * Get A Query In The Request
     */
    public function query($name)
    {
        return $this->query[$name] ?? null;
    }
    /**
     * Get A Query, Data sent on request
     */
    public function get($name)
    {
        return  $this->query[$name] ?? $_POST[$name] ?? $_GET[$name] ?? $_REQUEST[$name] ?? null;
    }
    public function all()
    {
        return array_merge($this->query, $_POST, $_GET, $_REQUEST);
    }
    /**
     * This Checks if the Request is Valid
     * @param array $rules An Array Containing the rules
     */
    public function validate(array $rules)
    {
        $errors = ["status" => false, "message" => "", "errors" => []];
        foreach ($rules as $key => $value) {
            $validators = explode("|", $value);
            // check if the key has required
            if (in_array("required", $validators)) {
                if (empty(request()->get($key))) {
                    $errors["errors"][$key] = $key . ' is required';
                }
            }
            // validate email fields
            if (in_array("email", $validators)) {
                if (filter_var(request()->get($key), FILTER_VALIDATE_EMAIL) == false) {
                    $errors["errors"][$key] = $key . ' is not a valid email';
                }
            }
        }
        $errors["message"] = implode(", ", array_keys($errors['errors'])) . " is required";
        if (count($errors['errors']) > 0) {
            response()->json($errors)->status(422);
            die();
        }
    }
    public function headers($name)
    {
        return $this->headers[strtoupper($name)] ?? false;
    }
}
