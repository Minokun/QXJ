<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/2/20
 * Time: 14:19
 */
$this->title = '灭火器位置';

?>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">灭火器位置管理</li>
    </ul>
</div>


<div class="dg">
    <div id="tb">

        <span class="glyphicon glyphicon-map-marker" style="color: rgb(26, 145, 149);"></span>位置编号:
        <input id="id" style="line-height:26px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton l-btn l-btn-small l-btn-plain" iconcls="icon-search" plain="true"
           onclick="doSearch()" group="" id="">
            查询
        </a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newLocation()">添加位置信息</a>
    </div>
    <table id="tt" class="easyui-datagrid" style="width:100%"
           title="灭火器位置列表"
           url="index.php?r=extin/location_list"
           pagination="true"
           iconCls="icon-location-edit"
           toolbar="#tb"
           pageSize=20
           pageList=[10,20,30,40,50]
           singleSelect="true"
           sortor="true">
        <thead>
        <tr>
            <th field="id" width="10%">编号</th>
            <th field="building" width="8%">楼栋</th>
            <th field="floor" width="8%">楼层</th>
            <th field="location" width="16%" align="right">位置</th>
            <th field="location_detail" width="20%" align="right">位置详情</th>
            <th field="created_time" width="8%" align="right">创建时间</th>
            <th field="updated_time" width="8%">更新时间</th>
            <th field="username" width="12%" align="center">最后一次操作人员</th>
            <th data-options="field:'_operate',width:'10%' , align:'center',formatter:formatOper">操作</th>
        </tr>
        </thead>
    </table>
</div>


<form id="add_form" url="" hidden="" class="layui-form" action="" method="post">
    <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
    <input name="id" hidden="hidden" value="">
    <div class="layui-form-item">
        <label class="layui-form-label">楼栋</label>
        <div class="layui-input-inline">
            <input type="text" name="building" required lay-verify="required" placeholder="请输入楼栋" autocomplete="off"
                   class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">楼层</label>
        <div class="layui-input-inline">
            <input type="text" name="floor" required lay-verify="required" placeholder="请输入楼层(纯数字)" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">位置</label>
        <div class="layui-input-inline">
            <input type="text" name="location" required lay-verify="required" placeholder="请输入位置"
                   autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">详情</label>
        <div class="layui-input-inline">
            <input type="text" name="location_detail" required lay-verify="required" placeholder="请输入位置详情"
                   autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <a class="layui-btn" lay-submit lay-filter="formDemo">立即提交</a>
            <a type="reset" class="layui-btn layui-btn-primary">重置</a>
        </div>
    </div>
</form>
<script>
    /**
     * 操作选项
     */
    function formatOper(val, row, index) {
        var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editLocation(' + index + ')">修改</a>';
        var del_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="destroyLocation(' + index + ')">删除</a>';
        return edit_button + "&nbsp&nbsp&nbsp&nbsp" + del_button;
    }
    /**
     * 查询
     */
    function doSearch() {
        $('#tt').datagrid('load', {
            id: $('#id').val()
        });
    }

    /**
     * 编辑位置
     */
    function editLocation(index) {
        $('#tt').datagrid('selectRow', index);

        var row = $('#tt').datagrid('getSelected');
        if (row) {
            layer.open({
                type: 1, //此处以iframe举例
                title: '编辑位置',
                area: ['30%', '60%'],
                shade: 0,
                content: $("#add_form"),
                //btn: ['继续弹出', '全部关闭'], //只是为了演示
                yes: function () {
                    $(that).click();
                }
            });
            $('input[name="id"]').val(row.id);
            $('input[name="building"]').val(row.building);
            $('input[name="floor"]').val(row.floor);
            $('input[name="location"]').val(row.location);
            $('input[name="location_detail"]').val(row.location_detail);

            layui.use('form', function () {
                var form = layui.form();
                form.on('submit(formDemo)', function (data) {
                    //layer.msg(JSON.stringify(data.field));
                    $('#add_form').form('submit', {
                        url: "ndex.php?r=extin/update-location",
                        onSubmit: function () {
                            return $(this).form('validate');
                        },
                        success: function (result) {
                            var result = eval('(' + result + ')');
                            if (result.msg) {
                                layer.msg(result.msg, {
                                    time: 20000, //20s后自动关闭
                                    btn: ['确定', '关闭']
                                });
                                $('#tt').datagrid('reload');
                            } else {
                                $('#tt').datagrid('reload');	// reload the  data
                            }
                        }
                    });
                });
            });
        }

    }


    /**
     * 添加新位置
     */
    function newLocation() {
        $("#add_form")[0].reset();
        layer.open({
            type: 1, //此处以iframe举例
            title: '添加新位置',
            area: ['30%', '60%'],
            shade: 0,
            content: $("#add_form"),
            yes: saveLocation()
        });

    }

    /**
     * 保存新位置
     */
    function saveLocation() {
        layui.use('form', function () {
            var form = layui.form();
            form.on('submit(formDemo)', function (data) {
                //layer.msg(JSON.stringify(data.field));
                $('#add_form').form('submit', {
                    url: "ndex.php?r=extin/add-location",
                    onSubmit: function () {
                        return $(this).form('validate');
                    },
                    success: function (result) {
                        var result = eval('(' + result + ')');
                        if (result.msg) {
                            layer.msg(result.msg, {
                                time: 20000, //20s后自动关闭
                                btn: ['确定', '关闭']
                            });
                            //使用sweetalert会存在提示信息被输入框遮盖的问题
                            //sweetAlert("提示信息",
                            //    result.msg,
                            //    "success"
                            //);
                            $('#tt').datagrid('reload');
                        } else {
                            $('#tt').datagrid('reload');	// reload the  data
                        }
                    }
                });
            });
        });
    }

    /**
     * 删除位置
     */
    function destroyLocation(index) {
        $('#tt').datagrid('selectRow', index);

        var row = $('#tt').datagrid('getSelected');
        if (row) {
            sweetAlert({
                title: "警告!",
                text: "确定删除吗?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                //此处执行后台删除
                $.post('index.php?r=extin/delete-location', {id: row.id}, function (result) {
                    if (result.status==1) {
                        $('#tt').datagrid('reload');	// reload the  data
                        swal("恭喜!",
                            result.msg,
                            "success");
                    } else {
                        swal("抱歉!",
                            result.msg,
                            "error");
                        $('#tt').datagrid('reload');
                    }
                }, 'json');
            });
        }
    }
</script>
<style>
    .panel-body {
        padding: 0;
    }
</style>