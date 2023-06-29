<?php

spl_autoload_register('myAutoloader');

function myAutoloader($className)
{
    $path = $_SERVER['DOCUMENT_ROOT'].'/m/';
    $path2 = $_SERVER['DOCUMENT_ROOT'].'/plugins/dompdf';

    $path_ary=[
        $_SERVER['DOCUMENT_ROOT'].'/m/',
        $_SERVER['DOCUMENT_ROOT'].'/plugins/dompdf-1.2.0',
    ];

    foreach($path_ary as $z){
        if(file_exists($path.$className.'.class.php')){
            include $path.$className.'.class.php';
            return;
        }
    }
}

include($_SERVER['DOCUMENT_ROOT'].'/m/general_functions.php');

?>