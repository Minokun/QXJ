<?php 
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = "车辆汇总统计";
?>
<div class="location">
	<ul class="breadcrumb">
		<li class="active">车辆汇总统计</li>
	</ul>
</div>

    <div id="tb" style="padding:3px">
    	<i class="layui-icon">&#xe612;</i><span>车牌号:&nbsp</span>
    	<input class="easyui-combobox"  id="plate_number" style="width:100px" url="index.php?r=vehicle/vehicle-list" valueField="id" textField="plate_number">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
    	
    	<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span>驾驶员:&nbsp</span>
        <input class="easyui-combobox"  id="driver" style="width:100px" url="index.php?r=vehicle/get-user-list" valueField="id" textField="username">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        
    	<i class="layui-icon">&#xe615;</i><a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">查询</a>
    </div>
    
    <table id="tt" class="easyui-datagrid" style="width:100%;"
    		url="index.php?r=vehicle/total-list" toolbar="#tb"
    		title="车辆统计信息" iconCls="icon-save"
    		singleSelect="true" pagination="true" pageSize=20, pageList=[20,30,40,50]>
    	<thead>
    		<tr>
    			<th field="id" width="6%">行车id号</th>
    			<th field="plate_number" width="12%">车牌号</th>
    			<th hidden="hidden" data-options="field:'vehicle_id'">车辆id</th>
    			<th hidden="hidden" data-options="field:'driver'">驾驶人id</th>
    			<th data-options="field:'username',width:'12%'">驾驶人</th>
    			<th field="start_at" width="12%" sortable="true">行车开始时间</th>
    			<th field="end_at" width="12%" sortable="true">行车结束时间</th>
    			<th field="gasoline" width="10%" sortable="true" formatter="formatgasoline">加油费用</th>
    			<th field="start_kilometer" width="12%" sortable="true" formatter="formatKilometer">起始里程(单位/Km)</th>
    			<th field="end_kilometer" width="12%" sortable="true" formatter="formatKilometer">结束里程(单位/Km)</th>
    			<th data-options="field:'_operate',width:'12%' , align:'center',formatter:formatOper">操作</th> 
    		</tr>
    	</thead>
    </table>

<script>
//**********************************数据格式转换**********************************
var row;
//操作栏
function formatOper(val,row,index){  
	opt_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="editTask('+index+',9)">删除</a>';

    return opt_button;
}

//用户状态栏
function formatgasoline(val,row,index){  
	return "￥ " + val;
}
//用户状态栏
function formatKilometer(val,row,index){  
	return val + " Km";
}

//**********************************操作方法**********************************
//查询
function doSearch(){
	$('#tt').datagrid('reload',{
		id: $('#plate_number').val(),
		driver:$('#driver').val()
	});
}

</script>
