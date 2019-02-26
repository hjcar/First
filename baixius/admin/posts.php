<?php 
//加载用户状态脚本
require_once"base/base_fn.php";



  //处理数据格式转换
function status_transfer($status){
  $dict =array('published'=>'已发布','drafted'=>'草稿','trashed'=>'回收站');

  //如果 传入的状态 不存?  返回位置
  return isset($dict[$status]) ? $dict[$status] : '未知';
};

//时间转换
function created_transfer($created){
  $timestamp=strtotime($created);
  return date('Y年m月d日 <\b\r> H:i:s',$timestamp);
};


//分类查询
$class=db_Query("select * from categories");
if(!empty($_GET['class'])){
  $a=(int)$_GET['class'];
  $where="and posts.category_id={$a}";
}
//发布状态查询
$status=db_Query("select DISTINCT `status` from posts ");
if(!empty($_GET['status'])){
  $b=$_GET['status'];
  $where="and posts.status='{$b}'";
}

//判断获取到每页数据
$page= empty($_GET['page'])? 1 : (int)$_GET['page'];
//每页12条数据
$size = 12;
//第一页从0开始
$offset=($page-1)*$size;
//列表查询


//1.查询全部数据 可以使用关联数组查询
$sql="select  posts.id,posts.title,users.nickname,posts.`status`,posts.created,categories.`name` from posts ,categories ,users where posts.category_id=categories.id AND users.id=posts.user_id {$where}  ORDER BY posts.created  limit {$offset},12";

$posts= db_Query($sql);

//===============分页===================

//确保数据准确 查出数据总数
$num=db_Query_one("select count(1) as num from posts ,categories where posts.category_id = categories.id ");
//最大页数
$maxpage=ceil($num['num']/$size);



//获取分页页码
$visiables=5;//显示五个

// //开始   
$begin=$page - ($visiables - 1)/ 2 ;
$end=$begin + 4; // //结束

//如果分页出现不合理的情况下
$begin= $begin < 1 ? 1 : $begin; //确保begin 不会小于一
$end=$begin+$visiables-1;
$end= $end>$maxpage ? $maxpage : $end; //确保end不大于maxpage
$begin=$end-$visiables+1; //因为 最后一个会改变 end,也就肯打破 begin和 end 的关系
$begin= $begin < 1 ? 1 : $begin;//确保begin不小于1

//防止用户输入page过多过少
if($page<1){
  header("Location:/admin/posts.php?page=1");
} 
if($page>$maxpage){
  header("Location:/admin/posts.php?page={$maxpage}");
}


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <script src="/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
        <!-- 公共的导航栏 -->
<?php include_once "base/header.php" ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章 <?php echo $sql ?></h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>" method='GET'">
          <select name="class" class="form-control input-sm">
            <option value="">所有分类</option>
            <?php foreach ($class as $name) {
             ?>
            <option value="<?php echo $name['id'] ?>"><?php echo $name['name'] ?></option>
            <?php } ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="">所有状态</option>
            <?php foreach ($status as $data ) {?>
               <option value="<?php echo  $data['status'] ?> "><?php echo status_transfer($data['status']) ?></option>
            <?php } ?>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="/admin/posts.php?page=<?php echo $page-1 ?>">上一页</a></li>
          <?php for($i = $begin ;$i<= $end ; $i++){  ?>
          <li <?php echo $page==$i ? "class=active" :'' ?>><a href="/admin/posts.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
            <?php } ?>
          <li><a href="/admin/posts.php?page=<?php echo $page+1 ?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $data) {?> 
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $data['title']?></td>
            <td><?php echo $data['nickname']?></td>
            <td><?php echo $data['name']?></td>
            <td class="text-center"><?php echo created_transfer($data['created'])?></td>
            <!-- 一旦判断逻辑 转换逻辑 不建议直接写在混编的位置 -->
            <td class="text-center"><?php echo status_transfer($data['status']) ?></td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

<?php $flag='posts' ?>
<?php include_once"base/base.php" ?>

  <script src="/assets/vendors/jquery/jquery.js"></script>
  <script src="/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
