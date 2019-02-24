<?php 
session_start();
//判断SESSION
//是否有用户
  if(empty($_SESSION['id'])){
    header("Location:/admin/login.php");
  }
 ?>