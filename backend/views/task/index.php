<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = "我的任务";
?>
<div class="location">
	<ul class="breadcrumb">
		<li class="active">我的任务</li>
	</ul>
</div>

    <div id="tb" style="padding:3px">
    	<i class="layui-icon">&#xe612;</i><span>任务对象编号:&nbsp</span>
    	<input id="unit_eid" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp
    	
    	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span>任务模块:&nbsp</span>
        <input class="easyui-combobox"  id="task_module_id" style="width:100px" url="index.php?r=task/get-module-list" valueField="id" textField="text">&nbsp&nbsp&nbsp&nbsp&nbsp
    	
    	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span>任务状态:&nbsp</span>
    	<select id="status" class="easyui-combobox" name="status" style="width: 80px;">
	       <option value="">--全部--</option>
	       <option value="2" selected>待执行</option>
            <option value="1">已完成</option>
            <option value="3">已过期</option>
        </select>&nbsp&nbsp&nbsp&nbsp&nbsp
        
    	<i class="layui-icon">&#xe615;</i><a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">查询</a>
    	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><a href="#" class="easyui-linkbutton" plain="true" onclick="addUser()">任务模块设置</a>
    </div>
    
    <table id="tt" class="easyui-datagrid" style="width:100%;"
    		url="index.php?r=task/get-list" toolbar="#tb"
    		title="任务列表" iconCls="icon-save"
    		singleSelect="true" pagination="true" pageSize=20, pageList=[20,30,40,50]>
    	<thead>
    		<tr>
    			<th field="id" width="6%" sortable="true">任务id号</th>
    			<th field="unit_eid" width="10%">任务对象编号</th>
    			<th hidden="hidden" data-options="field:'task_module_id'">模块id</th>
    			<th hidden="hidden" data-options="field:'opt_id'">执行者id</th>
    			<th hidden="hidden" data-options="field:'update_id'">更新员id</th>
    			<th hidden="hidden" data-options="field:'opt_url'">操作地址</th>
    			<th data-options="field:'module_name',width:'8%'">任务模块名</th>
    			<th data-options="field:'status',width:'6%',formatter:formatStatus">状态</th>
    			<th field="deadline_time" width="8%" sortable="true">截止日期</th>
    			<th data-options="field:'opt_name',width:'8%'">执行人</th>
    			<th field="description" width="20%">详细描述</th>
    			<th field="created_time" width="8%" sortable="true">创建时间</th>
    			<th field="updated_time" width="8%" sortable="true">更新时间</th>
    			<th data-options="field:'updater_name',width:'8%'">更新人员</th>
    			<th data-options="field:'_operate',width:'12%' , align:'center',formatter:formatOper">操作</th> 
    		</tr>
    	</thead>
    </table>


<!-- 添加和编辑任务模块页面 -->
<div id='optPart'>
    <form id="opt" class="layui-form" action="">
      <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
      <div class="layui-form-item">
        <label class="layui-form-label">模块名称</label>
        <div class="layui-input-inline">
          <input type="text" id="module_name" name="module_name" required lay-verify="required" placeholder="请输入模块名" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">此处为需要列为任务提醒的模块名称</div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">操作界面</label>
        <div class="layui-input-inline">
          <select id='selector' name="url_opt" lay-verify="required" lay-search>        
          </select>
        </div>
      </div>
      
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
          <button type="reset" id="restBtn" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </form>
</div>


<script>
$('#optPart').hide();
//**********************************获取菜单选项卡信息**********************************
$.ajax({
	type:"POST",
	url:'index.php?r=task/get-menu-list',
	success: function(data){
		var html = '<option value="">--请选择--</option>';;
		$.each(JSON.parse(data),function(index,data_obj){
			html += '<option value="' + data_obj.url + '">' + data_obj.menu_name + '</option>';
		});
		$('#selector').append(html);
	}
});
//**********************************定义layui组件**********************************
layui.use('form', function(){
	  var form = layui.form();
	  
	  //监听提交
	  form.on('submit(formDemo)', function(data){
	    //layer.msg(JSON.stringify(data.field));
        $.post('index.php?r=task/add-task-module',{
          	'module_name':data.field.module_name,
        	'url':data.field.url_opt
        },function(e){
        	if (e.status == 0){
        		layer.msg('操作失败，重新试一下吧！', {icon: 2});
        	}else{
        		layer.msg('Yes!操作成功！', {icon: 1});
        		$('#tt').datagrid('reload');
        	}
        },'json');
	    return false;
	  });
	});

//**********************************数据格式转换**********************************
var row;
//操作栏
function formatOper(val,row,index){  
	opt_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="editTask('+index+',9)">删除</a>';
	if (row.status == 1){
		var edit_button = '';
	}else{
		var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editTask('+index+',1)">完成</a>';
	}
	var redirect_url = '<a href="' + row.opt_url + '" class="easyui-linkbutton">进入操作页面</a>';
    return  redirect_url + "&nbsp&nbsp&nbsp&nbsp" + edit_button + "&nbsp&nbsp&nbsp&nbsp" + opt_button;
}

//用户状态栏
function formatStatus(val,row,index){  
	var sta = '';
	switch(parseInt(val)){
    	case 1:
    		sta = '<font color="green">已完成</font>';
    		break;
    	case 3:
    		sta = '<font color="red">已过期</font>';
    		break;
    	case 9:
    		sta = '删除';
    		break;
    	default:
        	sta = '<font color="#A6A600">待执行</font>';
    		break;
	}
	return sta;
}

//**********************************操作方法**********************************
//查询
function doSearch(){
	$('#tt').datagrid('load',{
		unit_name: $('#unit_name').val(),
		status:$('#status').val(),
		task_module_id:$('#task_module_id').val()
	});
}

//修改任务状态信息
function editTask(index,mark){
	$('#tt').datagrid('selectRow',index);
	row = $('#tt').datagrid('getSelected');
	var tips = '';
	var tips_title = '';
	switch(parseInt(mark)){
    	case 1:
    		tips_title = '确定完成了吗？';
    		tips = "PS:任务号\"" + row.id + "\"将被标记为已完成并不再提醒哦~";
    		break;
    	case 2:
    		tips_title = '确定恢复该任务吗？';
    		tips = "PS:任务号\"" + row.id + "\"将会恢复为待执行并开始提醒哦~";
    		break;
    	case 9:
    		tips_title = '确定删除吗？';
    		tips = "PS:任务号\"" + row.id + "\"将会被删除哦~";
    		break;
    	default:
    		break;
	}
	swal({
	  title: tips_title,
	  text: tips,
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "确认!",
	  closeOnConfirm: false
	},
	function (){
		$.post('index.php?r=task/edit-task-status',{
			'id':row.id,
			'status':mark
			},function(status){
			if (status == 1){
			  	swal("操作成功!", "本次操作有效！", "success");
			}else{
				sweetAlert("额……", "操作出了点问题，联系管理员吧~", "error");
			}
			$('#tt').datagrid('reload',{
				unit_name: $('#unit_name').val(),
				status:$('#status').val(),
				task_module_id:$('#task_module_id').val()
			});
		});
	});
}

//修改任务状态信息
function editTaskModule(index){
	$('#tt').datagrid('selectRow',index);
	row = $('#tt').datagrid('getSelected');
	$('[name=username_opt]').val(row.username);
	$("select[name=role_opt]").val(row.role);
	$('[name=email_opt]').val(row.email);
	$('[name=username_opt]').attr('disabled','disabled');
	layui.form().render();
	layer.open({
		  title:"员工信息编辑",
		  type: 1,
		  area: ['380px', '410px'],
		  fixed: false, //不固定
		  maxmin: false,
		  content: $('#optPart'),
		  cancel: function(index){ 
		    layer.close(index);
			$('#tt').datagrid('reload',{
				status:$('#status').val(),
				username: $('#username').val()
			});
		  }
		});
}

//添加工号
function addUser(){
	$("#restBtn").click();
	$('[name=username_opt]').removeAttr('disabled');
	layer.open({
		  title:"添加账号",
		  type: 1,
		  area: ['380px', '410px'],
		  fixed: false, //不固定
		  maxmin: false,
		  content: $('#optPart'),
		  cancel: function(index){ 
		    layer.close(index);
			$('#tt').datagrid('reload',{
				status:$('#status').val(),
				username: $('#username').val()
			});
		  }
		});
}
</script>
