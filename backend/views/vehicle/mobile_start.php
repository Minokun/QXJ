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
.layui-input-block {
	margin-left:5%;
	width:60%;
}
body, html {
	background-color: darkseagreen;
}
</style>
<blockquote class="layui-elem-quote">
 车辆安全管理
</blockquote>    
    
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>请先检查车辆状况哦</legend>
</fieldset>
<div class="content">
<form class="layui-form" action="">
  <div class="layui-form-item">
    <div class="layui-input-block">
      <p>驾驶车辆：</p><select name="vehicle_id" >
        <option value="1" selected="">蒙Kkk334</option>
        <option value="2">蒙K1245</option>
      </select>
    </div>
  </div>
    
    <div class="layui-form-item">
        <div class="layui-input-block">
        <p>起始里程：</p>
          <input lay-verify="required" type="text" name="start_kilometer" placeholder="单位：公里" autocomplete="off" class="layui-input">
        </div>
    </div>
  
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">开始驾驶</button>
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
        $.post('index.php?r=vehicle/add-record',{
        	'vehicle_id':data.field.vehicle_id,
        	'start_kilometer':data.field.start_kilometer,
        	'status':9
        },function(e){
        	if (e.status == 0){
        		swal("操作失败!","重新试一下吧！", "error");
        	}else{
        		swal("Yes!","开始记录本次行驶！注意安全哦~", "success");
        		setTimeout(function(){
        			window.location.href="index.php?r=vehicle/mobile";
            		},2000);
        	}
        },'json');
	    return false;
	  });
	});
</script>