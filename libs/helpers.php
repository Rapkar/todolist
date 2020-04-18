<?php
defined('BASE_PATH') or die('NO PERMISION'); 


function getCurrentUrl(){
    return 1;
}
function isAjaxRequest(){
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        /* special ajax here */
        return true;
    }
}
function diepage($msg){
    echo "<div style='padding: 30px;width: 88%;margin: 50px auto;background: #f9dede;border: 1px solid #cca4a4;font-family: sans-serif;border-radius: 5px;'>$msg</div>";
    die();
}
function message($msg,$Cssclas="info"){
    echo "<div class='$Cssclas'>$msg</div>";
}
function dd($var){
    echo "<pre style='color: #0C1667BF;z-index: 999;position: relative;background-color: cornsilk;padding: 15px;border-radius: 11px;margin: 20px;border-left: 3px solid orange;font-weight: bold;'>";
    var_dump($var);
    echo "</pre>";
}
function site_url($uri=""){
    return BASE_URL.$uri;
}
function redirect($url){
    header("location: $url");
    die();
}