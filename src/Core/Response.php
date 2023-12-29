<?php

namespace src\Core;

class Response
{
    public function __construct(public $data = null, public $status_code = null)
    {
        header("HTTP/1.1 $this->status_code");
        if ($data) {
            echo json_encode($data);
            die();
        }
    }
    public function json($data)
    {
        header("Content-Type: application/json");
        return new Response($data);
    }
    public function status($status_code)
    {
        return new Response(status_code: $status_code);
        // echo json_encode($data);
    }
}
