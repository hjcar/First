 <?php 
//加载用户状态脚本
  require_once"base/base_fn.php";

  //查询统计数据 文章 草稿 分类 评论 待审
$article = db_Query_one("select count(1) as num from posts ");
$draft   = db_Query_one("select count(1) as num from posts where status='drafted' ");
$class   =db_Query_one("select count(1) as num from categories;");
$comment   =db_Query_one("select count(1) as num from comments;");
$held   =db_Query_one("select count(1) as num from comments where status='held';");
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $article['num']; ?></strong>篇文章（<strong><?php echo $draft['num'] ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $class['num'] ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comment['num'] ?></strong>条评论（<strong><?php echo $held['num'] ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"> <canvas id="chart"></canvas> </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

<?php $flag="index";?>
<?php include_once"base/base.php" ?>

  <script src="/assets/vendors/jquery/jquery.js"></script>
  <script src="/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/assets/vendors/chart/chart.js"></script>
  <script>
    var ctx = document.getElementById("chart").getContext("2d");
    var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        datasets: [{
        data:[10,20,30],
        backgroundColor: [
        'rgb(255,106,133)',
        'rgb(131,45,120)',
        'rgb(250,138,255)'
    ]
    }],
   
    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
        '文章',
        '分类',
        '评论'
    ]
    }
});
  </script>
  <script>NProgress.done()</script>
</body>
</html>
