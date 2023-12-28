<?php
/** Load class not included */
spl_autoload_register(function($classname){
    $filename = "../app/models/".ucfirst($classname). ".php";

    if(file_exists($filename)){
        require $filename;
    }
});

require 'config.php';
require 'utils.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
require __DIR__.'/../../routes/web.php';