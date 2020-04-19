<?php defined('BASE_PATH') or die('NO PERMISION'); ?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title><?= SITE_TITLE ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <style>
.pagination {
  display: inline-block;
  margin-left: 30%;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}
.hide{
  display: none;
}
.show{
  display: block;
}
</style>
</head>
<body>
<!-- partial:index.partial.html -->
<div class="page">
  <div class="pageHeader">
    <div class="title">Dashboard</div>
    <div class="userPanel">
      <a href="?signout=1"><i style="cursor: pointer;text-decoration:none;color: wheat;" class="fa fa-sign-out"> </i></a>
      <span class="username"><?= $userloggin->name; ?> </span>
   <img src="<?= $userloggin->image ?>" width="40" height="40"/></div>
  </div>
  <div class="main">
    <div class="nav">
      <div class="searchbox">
        <div><i class="fa fa-search"></i>
          <input id="search" type="search" placeholder="Search"/>
        </div>
      </div>
      <div class="menu">
        <div class="title">Folders</div>
        <ul id="ullis">
              
        <li class="<?=isset($_GET['folder_id']) ? 'active' : '' ?>">
        <a href="<?= site_url(); ?>">
         <i style="cursor: pointer;" class="fa fa-folder active"></i>allTasks
        </a>
        </li>
          <?php foreach ($folders as $folder) :?>
           <li class="">
              <a href="<?= site_url("?folder_id= $folder->id")?>">
              <i class="fa fa-folder <?= ($_GET['folder_id']==$folder->id) ? 'active': '' ?>"></i><?=$folder->name ?>
            </a>
            <a href="<?= site_url("?delete_folder= $folder->id") ?>" class="remove" onclick="return confirm('Are you sure Deleted this task?\n<?=$folder->name ?>');">X</a>
            </li>
          <?php endforeach; ?> 
      
        </ul>
      </div>
      <div>
      <input type="text" id="addFolderInput" placeholder="add new folder">
      <button class="btn clickable" id="addFolderBtn">+</button>
      </div>
    </div>
    <div class="view">
      <div class="viewHeader">
        <div class="title">
        <input type="text" id="addTaskName" placeholder="add new Task" style="line-height: 3;width: 580px;border-left: 3px solid #54b9cd;">
        </div>
        <div class="functions">
          <div class="button active">Add New Task</div>
          <div class="button">Completed</div>
          <div class="button inverz"><i class="fa fa-trash-o"></i></div>
        </div>
      </div>
      <div class="content">
        <div class="list">
          <div class="title">Today
          <i class="fa fa-sort" style="margin-left: 20px;cursor:pointer;"></i>
          </div>
          <ul id="tasksul">
          <?php if($tasks != null) : ?>
            <?php if(sizeof($tasks)>0):?>
          <?php foreach ($tasks as $task) :?>
            <li class="<?=$task->id_done ? 'checked':''; ?>">
            <i  data-taskid="<?=$task->id ?>" class="isdone fa <?=$task->id_done ? 'fa-check-square-o' : 'fa-square-o'; ?>">
            </i><span><?=$task->title;?></span>
              <div class="info">
                <span class="created_at">Created at:<?=$task->created_at?></span>
                <a href="?delete_task=<?= $task->id ?>" class="remove" onclick="return confirm('Are you sure Deleted this task?\n<?=$task->title;?>');">X</a>
              </div>
            </li>
          <?php endforeach; ?>
          <?php
          else: ?>
          <li><span>This folder Not Exist Task</span>
              <div class="info"></div>
            </li>
          <?php endif; ?>
          <?php endif; ?>
          <!-- </ul>
        </div>
        <div class="list">
          <div class="title">Tomorrow</div>
          <ul>
            <li><i class="fa fa-square-o"></i><span>Find front end developer</span>
              <div class="info"></div>
            </li>
          </ul> -->
          <?php if($lim !=" LIMIT 0,0"):  ?>
          <div class="pagination">
          <a href="?pageno=1">First</a>
          <a class="<?= ($pageno<= 1) ? "hide" : "Show" ?>" href="<?= ($pageno<=1) ? "#" : "?pageno=".($pageno - 1); ?>">Prev</a>
          <a class="<?= ($pageno >= $totalPagination['totalpages']) ? "hide" : "Show" ?>" href="<?= ($pageno >=$totalPagination['totalpages']) ? '#' : "?pageno=".($pageno + 1); ?>">Next</a>
          <a  href="?pageno=<?= $totalPagination['totalpages']; ?>">Last</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>
<script>
  $(document).ready(function(){
    $('.isdone').click(function(e){
      var taskid=$(this).attr('data-taskid');
      $.ajax({
        url:"process/Ajax_handler.php",
        method:'post',
        data:{action:'task_isdone',task_isdon:taskid},
        success:function(resp){
              location.reload();
        }
        
      })
    });
    $("#addFolderBtn").click(function(event){
      var input=$("#addFolderInput").val();
    
      $.ajax({
          url:"process/Ajax_handler.php",
          method:"post",
          data:{action:"addFolder",folderName:input},
          success:function(response){
            var Folderobj=jQuery.parseJSON(response);
         $('<li><i class="fa fa-folder active"></i>'+ Folderobj.folderName +'</li>').appendTo('#ullis');
         location.reload();
          }
      });
    });
    $("#addTaskName").on('keypress',function(e) {
    if(e.which == 13) {
      $.ajax({
          url:"process/Ajax_handler.php",
          method:"post",
          data:{action:"addTask",task_title:$("#addTaskName").val(),folder_id:"<?= $_GET['folder_id'] ??''; ?>"},
          success:function(response){
           if(response=='1'){
         
              location.reload(); 
           }else{
            alert(response);
           }
          }
      });
    }
    });
    $("#search").on('keypress',function(e) {
      var inp=$(this).val();
    if(e.which == 13) {
      $.ajax({
        url:"process/Ajax_handler.php",
          method:"post",
          data:{action:"search",text_search:inp},
          success:function(response){
            var objSearch=jQuery.parseJSON(response);
            if(objSearch===null){
              $("#tasksul").empty();
              $('<li><span>Not found Task Whith this txt</span>').appendTo('#tasksul');
            }
            else
            {
         
            $("#tasksul").empty();
            $.each( objSearch, function( key, value ) {
              $('<li> <i class="isdone"></i><span>'+objSearch[key].title+'</span><div class="info"> <span class="created_at">'+objSearch[key].created_at+'</span> </div></li>').appendTo('#tasksul');
            });
            
            
           }
        }
      });
      
     }
    });
  });
</script>
</body>
</html>
