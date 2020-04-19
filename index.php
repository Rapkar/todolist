<?php
include("bootstrap/init.php");

if(logoutUser()){
    unset($_SESSION['login']);
}
if(!isLoggedIn()){
// redirect to aut form
header("location:". site_url('auth.php'));

}
if(isset($_GET['delete_folder']) && is_numeric($_GET['delete_folder'])){
    $Folder_delete=deleteFolder($_GET['delete_folder']);

}
$folders=getFolders();

if(isset($_GET['delete_task']) && is_numeric($_GET['delete_task'])){
    $Folder_delete=deleteTask($_GET['delete_task']);

}
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$totalPagination=totalPagination('',$pageno);
if( !empty(totalPagination('',$pageno))){
    $lim=" LIMIT ".$totalPagination['offset'].",".$totalPagination['per_page'];
}  
else
 $lim=" LIMIT 0,0";

$tasks= getTasks('',$lim);

$userloggin=getLoggedInUser();
$userloggin->image = "https://www.gravatar.com/avatar/" . md5( strtolower( trim($userloggin->email  ) ) );
include("tpl/tpl-index.php");
