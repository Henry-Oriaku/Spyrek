<?php

namespace src\Core;

class ExceptionHandler
{
    use Controller;

    function __construct($th)
    {
        $line = $th->getLine();
        $error = $th->getMessage();
        $file = $th->getFile();
        $error_detail = $th->getTraceAsString();

        if (request()->headers("accept") == "application/json") {
           response()->json(compact("line", "error", "file", "error_detail"));
        } else {

            $this->view("exception", compact("line", "error", "file", "error_detail"));
        }
    }
}
