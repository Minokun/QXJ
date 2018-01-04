<?php 
$this->title = "工号管理";
?>
<div class="location">
	<ul class="breadcrumb">
		<li class="active">工号管理</li>
	</ul>
</div>

    <div id="tb" style="padding:3px">
    	<i class="layui-icon">&#xe612;</i><span>用户账号:&nbsp</span>
    	<input id="username" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp
    	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span>账号状态:&nbsp</span>
    	<select id="status" class="easyui-combobox" name="status" style="width: 80px;">
	       <option value="" selected>全部</option>
            <option value="1">正常</option>
            <option value="9">已删除</option>
        </select>&nbsp&nbsp&nbsp&nbsp&nbsp
    	<i class="layui-icon">&#xe615;</i><a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">查询</a>
    	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><a href="#" class="easyui-linkbutton" plain="true" onclick="addUser()">添加新用户</a>
    </div>
    
    <table id="tt" class="easyui-datagrid" style="width:100%;"
    		url="index.php?r=job-number-manage/get-list" toolbar="#tb"
    		title="用户信息表" iconCls="icon-save"
    		singleSelect="true" pagination="true" pageSize=20, pageList=[20,30,40,50]>
    	<thead>
    		<tr>
    			<th field="id" width="6%" sortable="true">用户id号</th>
    			<th field="username" width="10%">账户名</th>
    			<th hidden="hidden" data-options="field:'role'">角色</th>
    			<th data-options="field:'role_name',width:'8%'">角色</th>
    			<th data-options="field:'status',width:'8%',formatter:formatStatus">状态</th>
    			<th field="email" width="15%">联系方式</th>
    			<th field="created_time" width="14%" sortable="true">创建时间</th>
    			<th field="updated_time" width="14%" sortable="true">更新时间</th>
    			<th data-options="field:'_operate',width:'25%' , align:'center',formatter:formatOper">操作</th> 
    		</tr>
    	</thead>
    </table>


<!-- 添加和编辑用户页面 -->
<div id='optPart'>
    <form id="opt" class="layui-form" action="">
      <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
      <div class="layui-form-item">
        <label class="layui-form-label">用户账号</label>
        <div class="layui-input-inline">
          <input type="text" id="username_opt" name="username_opt" required lay-verify="required" placeholder="请输入账户名" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">此处为登录账号可以是数字或字母</div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">用户角色</label>
        <div class="layui-input-inline">
          <select id='selector' name="role_opt"  lay-search>        
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">联系方式</label>
        <div class="layui-input-inline">
          <input type="text" name="email_opt" required lay-verify="required|phone|email" placeholder="请输入邮箱或电话" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">联系方式可以是邮箱电话或QQ号</div>
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
//**********************************获取角色选项卡信息**********************************
$.ajax({
	type:"POST",
	url:'index.php?r=job-number-manage/get-role-info',
	success: function(data){
		var html = '<option value="">--默认普通用户--</option>';;
		$.each(JSON.parse(data),function(index,data_obj){
			html += '<option value="' + data_obj.id + '">' + data_obj.name + '</option>';
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
        $.post('index.php?r=job-number-manage/user-info-opt',{
          	'username':data.field.username_opt,
        	'role':data.field.role_opt,
        	'email':data.field.email_opt
        },function(e){
        	if (e.status == -1){
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
	if (row.status == 9){
		opt_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="recUser('+index+')">恢复</a>'; 
	}else{
		opt_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="delUser('+index+')">删除</a>'; 
	}
    var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editUser('+index+')">用户信息修改</a>';
    return edit_button + "&nbsp&nbsp&nbsp&nbsp" + opt_button;
}

//用户状态栏
function formatStatus(val,row,index){  
	return val == 1 ? '正常' : '删除';
}

//**********************************操作方法**********************************
//查询
function doSearch(){
	$('#tt').datagrid('load',{
		username: $('#username').val(),
		status:$('#status').val()
	});
}

//恢复此员工
function recUser(index){
	row = $('#tt').datagrid('getSelected');
	swal({
	  title: "确定要恢复?",
	  text: "PS:账号\"" + row.username + "\"将会恢复~",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "是的，还原!",
	  closeOnConfirm: false
	},
	function (){
		$.post('index.php?r=job-number-manage/recovery',{
			'id':row.id,
			},function(status){
			if (status == 1){
			  	swal("成功恢复!", "该用户已经成功恢复！", "success");
			}else{
				sweetAlert("额……", "操作出了点问题，联系管理员吧~", "error");
			}
			$('#tt').datagrid('reload',{
				username: $('#username').val(),
				status:$('#status').val()
			});
		});
	});
}

//删除此员工
function delUser(index){
	$('#tt').datagrid('selectRow',index);
	row = $('#tt').datagrid('getSelected');
	swal({
	  title: "确定要恢复?",
	  text: "PS:账号\"" + row.username + "\"删除后可能会丢失账号信息哦~",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "是的，删掉!",
	  closeOnConfirm: false
	},
	function (){
		$.post('index.php?r=job-number-manage/del',{
			'id':row.id,
			},function(status){
			if (status == 1){
			  	swal("成功删除!", "该用户已经成功删除掉了！", "success");
			}else{
				sweetAlert("额……", "操作出了点问题，联系管理员吧~", "error");
			}
			$('#tt').datagrid('reload',{
				username: $('#username').val(),
				status:$('#status').val()
			});
		});
	});
}

//修改员工信息
function editUser(index){
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
