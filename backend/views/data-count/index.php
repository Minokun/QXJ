<?php
use yii\helpers\Html;

$this->title = "数据汇总统计";

?>
<script src="./source/ext/layui/lay/modules/laydate.js"></script>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">数据汇总统计</li>
    </ul>
</div>

<div class="dg">
    <div id="tb">
        <span class="glyphicon glyphicon-fire" style="color: rgb(255, 53, 60);;" aria-hidden="true"></span>分组名称:
        <input id="group_name" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp
        <a href="#" class="easyui-linkbutton" iconcls="icon-search" plain="true" onclick="doSearch()">查询</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="exportExcel()">数据导出</a>

    </div>

    <table id="tt" class="easyui-datagrid" style="width:100%;"
           title="检查设备分组统计"
           url="index.php?r=data-count/list"
           pagination="true"
           iconCls="icon-equipment-edit"
           toolbar="#tb"
           pageSize=20
           pageList=[10,20,30,40,50]
           singleSelect="true"
           sortor="true">
        <thead>
        <tr>
            <th field="id" width="8%">分组编号</th>
            <th field="name" width="28%">名称</th>
            <th field="total_number" width="22%">设备数量</th>
            <th field="pass_number" width="22%">合格数量</th>
            <th field="per_pass" width="4%">合格率</th>
            <th field="created_time" width="6%">录入时间</th>
            <th field="updated_time" width="6%">修改时间</th>
            <th field="username" width="4%">操作人员</th>
            <!--            <th data-options="field:'_operate',width:'8%' , align:'center',formatter:formatOper">操作</th>-->
        </tr>
        </thead>
    </table>
</div>

<script>

    /**
     * 操作选项
     */
    function formatOper(val, row, index) {
        var check_button = '<a href="#" class="easyui-linkbutton" onclick="checkEqu(' + index + ')">选项</a>';
        var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editEqu(' + index + ')">修改</a>';
        var del_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="destroyEqu(' + index + ')">删除</a>';
        return check_button + "&nbsp&nbsp&nbsp&nbsp" + edit_button + "&nbsp&nbsp&nbsp&nbsp" + del_button;
    }

    /**
     * 查询
     */
    function doSearch() {
        $('#tt').datagrid('load', {
            group_name: $('#group_name').val(),
        });
    }

    /**
     * 结果列表导出为Excel
     * @constructor
     */
    function exportExcel() {
        //利用PHPExcel实现查询结果导出为Excel
        window.location = 'index.php?r=data-count/export';
    }
</script>

<style>
    .panel-body {
        padding: 0;
    }

    div.layui-unselect {
        width: 77.5%;
    }

</style>