<?php 
// 载入脚本
require_once "../../config.php";

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$con){
	exit("数据库连接失败");
};
$email= isset($_GET['email'])? $_GET['email']:exit("请输入参数");

$query=mysqli_query($con,"select avatar from users where email ='{$email}'limit 1");

if($data=mysqli_fetch_assoc($query)){
	echo $data['avatar'];
}
//$query=mysqli_query($con, "select avatar from users where email = '{$email}'");
 ?>