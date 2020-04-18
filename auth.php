<?php
include("bootstrap/init.php");
if($_SERVER['REQUEST_METHOD']=='POST'){
    $action=$_GET['action'];
    $param=$_POST;
    $home=site_url();
     if($action=='register'){
        $Resault=  register($param);
        if(!$Resault){
          message("Error : an error in Registration !");  
        }else{
            message(" your account Created !<a href='$home'> to your index</a>"); 
        }
     }
       else if($action=='login')
       {
       $Resault= login($param['Email'],$param['Password']);
       if(!$Resault)
       {
        message("Error : an error in Registration !");  
       }
        else{
            redirect($home);
        }
    }
}
include("tpl/tpl-auth.php");
