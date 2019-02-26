 <?php 
 /*
  1.每次执行完都要查询一次列表 查询放在最后
  2.新增数据
    - 必须是post 请求
    - 并且url 没有传递 id
  3.更新数据
    -必须为post请求
    -必须url有id


  */
//加载用户状态脚本
  require_once"base/base_fn.php";


//说明客户端要拿一个修改数据的表单
if(!empty($_GET['id'])){
  //拿到了用户的id
  //获取数据
  $updata=db_Query_one("select * from categories where id = {$_GET['id']}");
};


//添加数据
function addclass(){
  //名字为空
  if(empty($_POST['name'])){
    $GLOBALS['message'] = "分类名称不能为空";
    return;
  };
   //别名为空
  if(empty($_POST['slug'])){
    $GLOBALS['message'] = "名不能为空";
    return;
  };
  //添加数据
  $row  = ZSG("insert into categories values (null,'{$_POST['slug']}','{$_POST['name']}')");
  $GLOBALS['success'] = $row>0;
  $GLOBALS['message'] = $row>0 ? "添加成功":"添加失败" ;
};

//更新信息
function upclass(){
  //名字为空
  if(empty($_POST['name'])){
    $GLOBALS['message'] = "分类名称不能为空";
    return;
  };
   //别名为空
  if(empty($_POST['slug'])){
    $GLOBALS['message'] = "别名不能为空";
    return;
  };
  $sql="update categories set name='{$_POST['name']}' ,slug='{$_POST['slug']}' where id={$_GET['id']}";
  $row = ZSG($sql);
  $GLOBALS['success'] = $row>0;
  $GLOBALS['message'] = $row>0 ? "更新成功":"更新失败" ;
  if($row>0){
    //添加成功后刷新页面
    header("Location:/admin/categories.php");
  }
}

//必须为POST
if($_SERVER['REQUEST_METHOD']=='POST'){
  //判断是更新还是添加
  if(empty($_GET['id'])){
    addclass();
  }else{
    upclass();  
  }
}

    //查询数据
$categories = db_Query("select * from categories ");




 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(isset($GLOBALS['message'])){ ?>
        <?php if($GLOBALS['success']){ ?>
           <div class="alert alert-success">
          <?php }else{ ?>
             <div class="alert alert-danger">
          <?php } ?>
        <strong><?php echo $GLOBALS['message']; ?></strong>
      </div>
      <?php } ?>

      <div class="row">
        <div class="col-md-4">
          <?php if(isset($updata)){ ?>
          <form action=" <?php echo $_SERVER['PHP_SELF']."?id=".$updata['id'] ?> " method='POST'>
            <h2>更新 <?php echo $updata['name']; ?></h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" value="<?php echo $updata['name'] ?> " name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" value="<?php echo $updata['slug'] ?>" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </form>
          <?php }else{ ?>
          <form action=" <?php echo $_SERVER['PHP_SELF'] ?> " method='POST'>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
          <?php } ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm btn-delete" href="javascript:;" onclick="confirm('确认删除吗!')" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $res) {
               ?>
              <tr>
                <td class="text-center"><input type="checkbox"  data-id="<?php echo $res['id'] ?> "></td>
                <td><?php echo $res['name'] ?></td>
                <td><?php echo $res['slug'] ?></td>
                <td class="text-center">
                  <a href="<?php echo $_SERVER['PHP_SELF']."?id=".$res['id'] ?> " class="btn btn-info btn-xs">编辑</a>
                  <a href="ctegories-delete.php?id=<?php echo $res['id'] ?>" onclick="confirm('确认删除吗!')" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $flag='categories' ?>
  <?php include_once"base/base.php" ?>

  <script src="/assets/vendors/jquery/jquery.js"></script>
  <script src="/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    //1.不要使用无意义的选择操作 
    //方法一
    // $(function(){
    //   var tbodyCheckboxs=$("tbody input");
    //   var btnDelete=$(".page-action a");
    //   tbodyCheckboxs.on("change",function(){
    //        var flag= false;
    //     $("tbody input").each(function(i,item){
    //      if($(item).prop("checked")){
    //          flag = true;
    //      }
    //     });
    //     flag ? btnDelete.fadeIn() : btnDelete.fadeOut();
    //   });
    // });

    //方法二

  $(function(){
    var tbodyCheckboxs=$("tbody input");
    var btnDelete=$(".page-action a");

    var allDataId=[];
    tbodyCheckboxs.on("change",function(){
      //当发生改变时
      var id = $(this).data("id");
        if($(this).prop("checked")){//只要是true;传入当前这个id
          //传入数组
          allDataId.push(id);
        }else{
          allDataId.splice(allDataId.indexOf(id),1);
        }
        allDataId.length ? btnDelete.fadeIn() : btnDelete.fadeOut();
        btnDelete.attr('href','/admin/ctegories-delete.php?id='+allDataId);
    });
    //全选与不全选
        $("thead input").on("change",function(){
          //全选与不全选是清空数组
          allDataId=[];
          //把全选checked给他
            var checked=$(this).prop("checked");
            //下面所以的全选checked都一样 当他改变时触发一次改变事件
          tbodyCheckboxs.prop("checked", checked).trigger("change");
    });


  });    
  </script>
  <script>NProgress.done()</script>
</body>
</html>
