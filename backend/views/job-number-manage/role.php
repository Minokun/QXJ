<?php 
$this->title = "角色权限管理管理";
?>
<script type="text/javascript" src="http://www.w3cschool.cc/try/jeasyui/datagrid-detailview.js"></script>
<div class="location">
	<ul class="breadcrumb">
		<li class="active">角色权限管理管理</li>
	</ul>
</div>

<div id="tb" style="padding:3px;text-align:center">
	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span><button class="layui-btn layui-btn-small layui-btn-radius" onclick="addRole()">添加新角色</button>
</div>

<table id="dg" style="width:100%;height:100%" toolbar="#tb"
		url="index.php?r=job-number-manage/get-role-list"
		pagination="true" title="角色权限信息表"
		singleSelect="true" fitColumns="true">
	<thead>
		<tr>
			<th field="id" width="10">角色ID号</th>
			<th field="name" width="40">角色名</th>
			<th field="opt" align="center" width="50" formatter="formatOper">操作</th>
		</tr>
	</thead>
</table>

<!-- 添加和编辑用户页面 -->
<div id='optPart'>
    <form id="opt" class="layui-form" action="">
      <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
      <input  type="text" id="role_id" hidden="hidden" class="layui-input">
      <div class="layui-form-item">
        <label class="layui-form-label">角色名</label>
        <div class="layui-input-inline">
          <input type="text" id="name_opt" name="name_opt" required lay-verify="required" placeholder="请输入角色名" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
          <label class="layui-form-label">菜单权限</label>
        <div class="layui-input-inline">
            <ul id="tt" class="easyui-tree" checkbox="true" multiple="true" lines="true" formatter="formatNode"></ul>
        </div>
      </div>  
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
          <button type="reset" id="restBtn" class="layui-btn layui-btn-primary" onclick="reset()">重置</button>
        </div>
      </div>
    </form>
</div>

<script type="text/javascript">
$(function(){
	
	$('#optPart').hide();
	//**********************************初始化datagrid拓展部份**********************************
	$('#dg').datagrid({
		view: detailview,
		detailFormatter:function(index,row){
			return '<div id="ddv-' + index + '"></div>';
		},
		onExpandRow: function(index,row){
			$('#ddv-'+index).panel({
				border:false,
				cache:false,
				href:'index.php?r=job-number-manage/get-role-list-detail&id=' + row.id,
				onLoad:function(){
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
			$('#dg').datagrid('fixDetailRowHeight',index);
		}
	});
	
	//**********************************定义layui组件**********************************
	layui.use('form', function(){
		  var form = layui.form();
		  
		  //监听提交
		  form.on('submit(formDemo)', function(data){
    		  var node = $('#tt').tree('getChecked');
    		  var menu_ids = new Array();
    			$.each(node,function(index,data){
    				menu_ids.push(data['id']);
    				});
	        $.post('index.php?r=job-number-manage/role-opt',{
		        'id':$('#role_id').val(),
	          	'name':data.field.name_opt,
	        	'menu':menu_ids.join()
	        },function(status){
	        	if (status == 1){
	        		layer.msg('Yes!操作成功！', {icon: 1});
	        	}else{
	        		layer.msg('操作失败，重新试一下吧！', {icon: 2});
	        	}
	        	$('#dg').datagrid('reload');
	        },'json');
		    return false;
		  });
		});
});
//**********************************数据格式转换**********************************
var row;
//操作栏
function formatOper(val,row,index){  
	var opt_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="delRole('+index+')">删除</a>'; 
    var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editRole('+index+')">角色信息修改</a>';
    return edit_button + "&nbsp&nbsp&nbsp&nbsp" + opt_button;
}
function reset(){
	$("#restBtn").click();
}
//添加角色
function addRole(){
	$('#tt').tree({   
        url:'index.php?r=job-number-manage/get-menu-tree-data&role_id=0'
    });  
	$("#restBtn").click();
	layer.open({
		  title:"添加角色",
		  type: 1,
		  area: ['380px', '410px'],
		  fixed: false, //不固定
		  maxmin: false,
		  content: $('#optPart'),
		  cancel: function(index){ 
		    layer.close(index);
		    $('#dg').datagrid('reload');
		  }
		});
}

//修改角色信息
function editRole(index){
	$('#dg').datagrid('selectRow',index);
	row = $('#dg').datagrid('getSelected');
    $('#tt').tree({   
        url:'index.php?r=job-number-manage/get-menu-tree-data&role_id=' + row.id
    });  
	$('#role_id').val(row.id);
	$('[name=name_opt]').val(row.name);
	layer.open({
		  title:"角色信息编辑",
		  type: 1,
		  area: ['380px', '410px'],
		  fixed: false, //不固定
		  maxmin: false,
		  content: $('#optPart'),
		  cancel: function(index){ 
		    layer.close(index);
			$('#dg').datagrid('reload');
		  }
		});
}

//删除此员工
function delRole(index){
	$('#dg').datagrid('selectRow',index);
	row = $('#dg').datagrid('getSelected');
	if (row.id == 2){
		sweetAlert("注意", "普通用户组不能被删除！", "error");
	}else{
		swal({
			  title: "确定要删除?",
			  text: "PS:角色\"" + row.name + "\"删除后无法恢复哦~",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "是的，删掉!",
			  closeOnConfirm: false
			},
			function (){
				$.post('index.php?r=job-number-manage/del-role',{
					'id':row.id,
					},function(status){
					if (status == 1){
					  	swal("成功删除!", "该角色已经成功删除掉了！", "success");
					}else if(status == -1){
						sweetAlert("注意", "请先接触该角色下对应的用户关系，再进行删除！", "error");
					}else{
						sweetAlert("额……", "操作出了点问题，联系管理员吧~", "error");
					}
					$('#dg').datagrid('reload');
				});
			});
	}
	
}
</script>