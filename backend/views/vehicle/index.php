<?php 
$this->title = "行车记录";
?>
<script type="text/javascript" src="http://www.w3cschool.cc/try/jeasyui/datagrid-detailview.js"></script>
<div class="location">
	<ul class="breadcrumb">
		<li class="active">行车记录</li>
	</ul>
</div>
<form class="layui-form" action="">
<div class="layui-form-item">

    <label class="layui-form-label">车辆选择</label>
        <div class="layui-input-inline">
          <select id='selector_vehicle' name="vehicle_id" lay-verify="required" lay-search>        
          </select>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">驾驶人员</label>
        <div class="layui-input-inline">
          <select id='selector_user' name="driver" lay-verify="required" lay-search>        
          </select>
        </div>
    </div>
  
    <div class="layui-form-item">
        <label class="layui-form-label">加油费用</label>
        <div class="layui-input-block">
          <input type="text" name="gasoline" placeholder="单位：元" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">起始里程</label>
        <div class="layui-input-block">
          <input type="text" name="start_kilometer" placeholder="单位：公里" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">终止里程</label>
        <div class="layui-input-block">
          <input type="text" name="end_kilometer" placeholder="单位：公里" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">行车时间</label>
        <div class="layui-input-inline">
            <input name="start_time" class="layui-input" placeholder="开始时间" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
        </div>
        <div class="layui-input-inline">
            <input name="end_time" class="layui-input" placeholder="结束时间" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
        </div>
    </div>
  
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
      <button id="reset_button" type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
 
<script>
//**********************************获取菜单选项卡信息**********************************
//获取用户列表
$.ajax({
	type:"POST",
	url:'index.php?r=vehicle/get-user-list',
	success: function(data){
		var html = '<option value="">--默认当前用户--</option>';
		$.each(JSON.parse(data),function(index,data_obj){
			html += '<option value="' + data_obj.id + '">' + data_obj.username + '</option>';
		});
		$('#selector_user').append(html);
	}
});
//获取车辆列表
$.ajax({
	type:"POST",
	url:'index.php?r=vehicle/vehicle-list',
	success: function(data){
		var html = '<option value="">--请选择--</option>';
		$.each(JSON.parse(data),function(index,data_obj){
			html += '<option value="' + data_obj.id + '">' + data_obj.plate_number + '</option>';
		});
		$('#selector_vehicle').append(html);
	}
});
//**********************************定义layui组件**********************************
layui.use(['form','laydate'], function(){
	  var form = layui.form();
	  
	  //监听提交
	  form.on('submit(formDemo)', function(data){
	    //layer.msg(JSON.stringify(data.field));
        $.post('index.php?r=vehicle/add-record',{
          	'vehicle_id':data.field.vehicle_id,
        	'driver':data.field.driver,
        	'start_kilometer':data.field.start_kilometer,
        	'end_kilometer':data.field.end_kilometer,
        	'gasoline':data.field.gasoline,
        	'start_at':data.field.start_time,
        	'end_at':data.field.end_time,
        },function(e){
        	if (e.status == 0){
        		swal("操作失败!","重新试一下吧！", "error");
        	}else{
        		swal("Yes!","操作成功！", "success");
        		$("#reset_button").click();
        	}
        },'json');
	    return false;
	  });
	});



</script>