<?php
include_once("../bootstrap/init.php");
if(!isAjaxRequest()){
    
    diepage("Invalid Request");
}
if(! isset($_POST['action'])&&empty($_POST['action'])){
    diepage("Invalid action");
}
switch($_POST['action']){
    case('addFolder'):
       addFolder($_POST['folderName']);
       $folderName=$_POST['folderName'];
       echo json_encode(['folderName'=>$folderName]);

    break;
    case('addTask'):
        $task_title=$_POST['task_title'];
        $folder_id=$_POST['folder_id'];
        if(!isset($task_title)  OR empty($folder_id))
        {
            echo "Please Select Folder";
            die();
        }
        if(!isset($task_title)  OR strlen($task_title) < 3)
        {
            echo "Task param than az 3";
            die();
        }
       echo addTask($task_title,$folder_id);
    break;
    case('task_isdone'):
        $task_id=$_POST['task_isdon'];
        echo taskIsDone($task_id);
    break;
    case('search'):
     
        $searchUserTasks=getTasks($_POST['text_search']);
         echo json_encode($searchUserTasks);
    break;
    default:
    diepage("Invalid action");
}