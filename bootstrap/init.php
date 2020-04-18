<?php
session_start();
 include 'constants.php';
include BASE_PATH.'/bootstrap/config.php';
include BASE_PATH."/vendor/autoload.php";
include BASE_PATH."/libs/helpers.php";

//var 
$dsn="mysql:dbname={$database_config->db};host={$database_config->host}";
$user=$database_config->user;
$password=$database_config->pass;

//connect
try{
$pdo=new PDO($dsn,$user,$password);
 
}catch(PDOException $e){
    diepage ("Connection failed ". $e->getMessage());
}

include BASE_PATH."/libs/lib-auth.php";
include BASE_PATH."/libs/lib-tasks.php";