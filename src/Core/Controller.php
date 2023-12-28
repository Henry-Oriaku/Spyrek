<?php

namespace src\Core;

trait Controller
{
    public function view($name, $data = [])
    {
        if (!empty(($data)))
            extract($data);

        $filename = "../app/views/" . $name . ".view.php";

        if (file_exists(($filename))) {
            require $filename;
        } else {
            require "../app/views/404.view.php";
        }
    }

    public function failureResponse($message, $errors = null, \Throwable|\Exception $th = null)
    {
        if ($th) {
            logger($th->getMessage() . "::line::" . $th->getLine() . '::of::' . $th->getFile() . "\n----------\n" . $th->getTraceAsString() . "\n----------\n");
        }
        return response()->json([
            "status" => false,
            "message" => $message,
            "errors" => $errors
        ]);
    }
    public function successResponse($message, $data = null)
    {
        return response()->json([
            "status" => true,
            "message" => $message,
            "data" => $data
        ]);
    }
}
