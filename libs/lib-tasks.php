<?php

 defined('BASE_PATH') or die('NO PERMISION'); 


function getCurrentUserId(){
    return getLoggedInUser()->id;
}
function deleteFolder($folder_id){
    global $pdo;
    $sql="DELETE FROM folders WHERE id=$folder_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function getFolders(){
    global $pdo;
    $current_user_id=getCurrentUserId();
    $sql="SELECT * FROM folders WHERE user_id=$current_user_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $records=$stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}
function addFolder($folder_name){
    global $pdo;
    $current_user_id=getCurrentUserId();
    $sql="INSERT INTO folders (name,user_id) VALUES (:foldername,:user_id)";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([':foldername'=>$folder_name,':user_id'=>$current_user_id]);
    return $stmt->rowCount();
}
function getTasks($var="",$limit=" "){
    try{
        global $pdo;
        global $task_sort;
        $current_user_id=getCurrentUserId();
        $folder=$_GET['folder_id'] ?? null;
        $folder_condition="";
        $sort="ORDER BY created_at ".$task_sort->Sort; 
        
        if(isset($folder) and is_numeric($folder)){
            $folder_condition="AND folder_id=$folder";
        }     
        $var_search="AND title LIKE "."'%".$var."%'";
        $sql="SELECT * FROM tasks WHERE user_id=$current_user_id $var_search  $folder_condition $sort $limit";
        $stmt=$pdo->prepare($sql);
        $stmt->execute();
        $records=$stmt->fetchAll(PDO::FETCH_OBJ);
        if(empty($records)){
            return null;
        }
        return $records;
    }catch(PDOException $e){
        return "Sorry : ".$e;
    }
}
function deleteTask($task_id){
    global $pdo;
    $sql="DELETE FROM tasks WHERE id=$task_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}
function addTask($taskTitle,$folderId){
    global $pdo;
    $current_user_id=getCurrentUserId();
    $sql="INSERT INTO tasks (title,user_id,folder_id) VALUES (:title,:user_id,:folder_id)";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([':title'=>$taskTitle,':user_id'=>$current_user_id,'folder_id'=>$folderId]);
    return $stmt->rowCount();
}
function taskIsDone($task_id){
    global $pdo;
    $current_user_id=getCurrentUserId();
    $sql="UPDATE tasks SET id_done= 1 - id_done  Where id= :task_id and user_id= :user_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([':task_id'=>$task_id,':user_id'=>$current_user_id]);

    return $stmt->rowCount();

}
function totalPagination($var="",$pageno="1"){
    global $pdo;
    global $task_sort;
    global $post_per_page;
    $per_page=$post_per_page->count;
    $current_user_id=getCurrentUserId();
    $folder=$_GET['folder_id'] ?? null;
    $folder_condition="";
    $sort="ORDER BY created_at ".$task_sort->Sort; 
    if(isset($folder) and is_numeric($folder)){
        $folder_condition="AND folder_id=$folder";
    }     
    $var_search="AND title LIKE "."'%".$var."%'";
    $sql="SELECT Count(*)  FROM tasks WHERE user_id=$current_user_id $var_search  $folder_condition $sort";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $records=$stmt->fetch()[0];
    $offset = ($pageno - 1) * $per_page; 
    $total_pages = ceil($records / $per_page);
    if(empty($total_pages) || is_null($offset)){
        return  null;
    }
    $res=array('totalpages'=>$total_pages,'offset'=>$offset,'records'=>$records,'per_page'=>$per_page);
    return $res;
}