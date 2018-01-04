<script type="text/javascript" src="source/easyui_1.5.1/jquery.min.js"></script>
<!-- 引入sweetalert -->
    <script src="source/sweetalert-master/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="source/sweetalert-master/dist/sweetalert.css">
<!-- 引入layer -->
    <script src='./source/ext/layer/layer.js'></script>
    <link rel="stylesheet" type="text/css" href='./source/ext/layui/css/layui.css'>
    <script src='./source/ext/layui/layui.js'></script>
<!-- =========================================== 手机端引用 =========================================== -->
<!-- 式样 css -->
<link rel="stylesheet" type="text/css" href="css/comm.css">
<!-- 通用JS引入 -->
<link rel="stylesheet" type="text/css" href="css/plugins.app.all.css">
<script type="text/javascript" src="js/cn.tenstrip.web.app.all.js"></script>
 
<style>
.body {
	height: 500px;
	background-color:lavender;
}
.content {
	border: 2px solid black;
}
.layui-input-block {
	margin-left:5%;
	width:60%;
}
body, html {
	background-color: lightslategrey;
}
</style>
<blockquote class="layui-elem-quote">
车辆安全管理
</blockquote>    
    
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>旅途辛苦了哦</legend>
</fieldset>
<div class="content">
<blockquote class="layui-elem-quote layui-quote-nm">
<ul>
    <li><span>驾驶人：</span><span><?= $driver ?></span></li>
    <li><span>车牌号：</span><span><?= $plate_number ?></span></li>
    <li><span>开始里程：</span><span><?= $start_kilometer ?> 公里</span></li>
    <li><span>开始时间：</span><span><?= $start_at ?></span></li>
</ul>
</blockquote>
<form class="layui-form" action="">
    
    <div class="layui-form-item">
        <div class="layui-input-block">
        <p>终止里程</p>
            <input name="id" hidden="true" value="<?= $id ?>">
          <input lay-verify="required" type="text" name="end_kilometer" placeholder="单位：公里" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <div class="layui-input-block">
        <p>加油费用</p>
          <input type="text" name="gasoline" placeholder="单位：元（可为空）" autocomplete="off" class="layui-input">
        </div>
    </div>
  
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">结束驾驶</button>
    </div>
  </div>
</form>
</div>
<script>
//**********************************定义layui组件**********************************
layui.use(['form'], function(){
	  var form = layui.form();
	  
	  //监听提交
	  form.on('submit(formDemo)', function(data){
// 	    layer.msg(JSON.stringify(data.field));
        $.post('index.php?r=vehicle/update-record',{
          	'id':data.field.id,
        	'end_kilometer':data.field.end_kilometer,
        	'gasoline':data.field.gasoline,
        },function(e){
        	if (e.status == 0){
        		swal("操作失败!","重新试一下吧！", "error");
        	}else{
        		swal("Yes!","本次行程结束！辛苦了哦~", "success");
        	}
        },'json');
	    return false;
	  });
	});
</script>