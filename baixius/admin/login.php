<?php
//载入配置信息脚本
session_start();
require_once "../config.php";

//清除数据库连接数据
function mysqlii_Clear($result,$con){
  //释放
    mysqli_free_result($result);
    //关闭
    mysqli_close($con);
}


function upUsers(){
  //验证用户表单是否为空
  if(empty($_POST['email'])){
    $GLOBALS['message'] = "请输入用户名";
    return;
  };
  //验证密码表单
  if(empty($_POST['password'])){
    $GLOBALS['message'] = "请输入密码";
    return;
  } ;

//验证数据库是否有用户
 
$con   = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$query = mysqli_query($con,"select * from users where email ='{$_POST['email']}' limit 1");
  if(!$query){
    $GLOBALS['message'] = "用户名错误";
    mysqlii_Clear($query,$con);
    return;
  }
//用户名数据存在
$data=mysqli_fetch_assoc($query);
  //密码是否匹配
  if($data['password']!=$_POST['password']){
    $GLOBALS['message'] = "用户名或密码错误";
    mysqlii_Clear($query,$con);
    return;
  };

  //密码匹配成功 保存用户信息 跳转页面
  $_SESSION['id']=$data['id'];
  //清除
  mysqlii_Clear($query,$con);

  header('Location: /admin/index.php');
  
};
  
  //1.提交表单等于POST
if($_SERVER['REQUEST_METHOD']==="POST"){
   //2.持久化
  //3.提交数据
   upUsers();
}
 
  

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" action=" <?php echo $_SERVER['PHP_SELF'] ?> "method="POST"  >
      <img class="avatar" src="/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(isset($GLOBALS['message'])){ ?>
      <div class="alert alert-danger">
        <strong>错误！</strong>  <?php echo $GLOBALS['message'] ?>
      </div>
      <?php } ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" value=" <?php echo isset($_POST['email'])? $_POST['email'] :''  ?> " name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
</body>
</html>
