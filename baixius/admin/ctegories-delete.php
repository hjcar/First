<?php 
//删除页面

require_once "base/base_fn.php";
if(empty($_GET['id'])){
	//没有id
	exit("请传入id");
}

//单个数据删除
// //只能传入数字 避免有人恶意
// $id=(int)$_GET['id']; //只会获取第一对数字

// ZSG("delete from categories where id = {$id}");

//这样就安全了 
$id=$_GET['id'];

$sql="delete from categories where id in (".$id.")";

ZSG($sql);

//删除完成返回页面
header("Location:/admin/categories.php");

 ?>