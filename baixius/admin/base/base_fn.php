<?php
require_once "../config.php"; 

/*======存入公共的函数
 *
 * //PHP 定义函数时注意于函数重名
 * 
 */
//用户状态
function user_state(){
	session_start();
	//判断SESSION
	//是否有用户
  if(empty($_SESSION['user'])){
    header("Location:/admin/login.php");
    exit(); //结束
  }
  return $_SESSION['user'];
}

user_state();

//数据库连接  返回查询结果集
function db_con($sql){
	$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if(!$con){
			exit(数据库连接失败);
		}
	//施行sql语句 查询失败返回
		$result=mysqli_query($con, $sql);
			if(!$result){
			//输入的查询语句有问题返回false
			return false;
		}
		mysqli_close($con);//断开连接
		return $result;
};


//封装增删改个数据 返回受影响行数
function ZSG($sql){
		$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if(!$con){
			exit(数据库连接失败);
		}
	//施行sql语句 查询失败返回
			if(!mysqli_query($con, $sql)){
			//输入的查询语句有问题返回false
			return false;
			}
		//对于增删改函数都是获取受影响函数
		$affected_rows=mysqli_affected_rows($con);
		return $affected_rows;
};


//查询,获取多条数据
function db_Query($sql){
	$query=db_con($sql);
	//遍历
	while($row = mysqli_fetch_assoc($query)){
		$result[]=$row;
	}
	mysqli_free_result($query);//清理数据
	return $result;
};

		
//封装查询单个数据
function db_Query_one($sql){
		$result = db_Query($sql);
		return isset($result[0])? $result[0] : null;
};

	
 ?>