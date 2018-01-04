<?php
use yii\helpers\Html;

$this->title = "检查项管理";

?>
<script src="./source/ext/layui/lay/modules/laydate.js"></script>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">检查项录入管理</li>
    </ul>
</div>


<div class="dg">
    <div id="tb">
        <span class="glyphicon glyphicon-fire" style="color: rgb(255, 53, 60);;" aria-hidden="true"></span>检查项名称:
        <input id="name" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp
        <a href="#" class="easyui-linkbutton" iconcls="icon-search" plain="true" onclick="doSearch()">查询</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addIns()">添加检查项</a>
    </div>

    <table id="tt" class="easyui-datagrid" style="width:100%;"
           title="检查项数据表"
           url="index.php?r=inspection/list"
           pagination="true"
           iconCls="icon-inspection-edit"
           toolbar="#tb"
           pageSize=20
           pageList=[10,20,30,40,50]
           singleSelect="true"
           sortor="true">
        <thead>
        <tr>
            <th field="id" width="8%">编号</th>
            <th field="name" width="20%">名称</th>
            <th field="desc" width="50%">备注说明</th>
            <th field="created_time" width="6%">录入时间</th>
            <th field="updated_time" width="6%">修改时间</th>
            <th field="username" width="4%">操作人员</th>
            <th data-options="field:'_operate',width:'6%' , align:'center',formatter:formatOper">操作</th>
        </tr>
        </thead>
    </table>
</div>


<form id="add_form" url="" hidden="" class="layui-form" action="" method="post">
    <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
    <input name="id" hidden="hidden" value="">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" required lay-verify="required" placeholder="请输入检查项名称" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">备注说明</label>
        <div class="layui-input-inline">
            <input type="text" name="desc" required lay-verify="required" placeholder="请输入备注说明" autocomplete="off"
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
        var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editIns(' + index + ')">修改</a>';
        var del_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="destroyIns(' + index + ')">删除</a>';
        return edit_button + "&nbsp&nbsp&nbsp&nbsp" + del_button;
    }

    /**
     * 查询
     */
    function doSearch() {
        $('#tt').datagrid('load', {
            name: $('#name').val(),
        });
    }

    /**
     * 添加检查项
     */
    function addIns() {
        document.getElementById("add_form").reset();
//        getLocationList(type=1);
        var index = layer.index;
        layer.open({
            type: 1, //此处以iframe举例
            title: '编辑检查项信息',
            area: ['30%', '60%'],
            shade: 0,
            content: $("#add_form"),
//            content:"index.php?r=extin/get-location-list",
            //btn: ['继续弹出', '全部关闭'], //只是为了演示
            yes: function () {
                $(that).click();
                layer.close(index);
            }
        });
        layui.use('form', function () {
            var form = layui.form();
            form.on('submit(formDemo)', function (data) {
                //layer.msg(JSON.stringify(data.field));
                $('#add_form').form('submit', {
                    url: "index.php?r=inspection/add",
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
                            $('#tb').dialog('close');		// close the dialog
                            $('#tt').datagrid('reload');	// reload the  data
                        }
                    }
                });
            });
        });


    }

    /**
     * 编辑检查项
     */
    function editIns(index) {
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
//        getLocationList(2,row.l_id);
        if (row) {
            layer.open({
                type: 1, //此处以iframe举例
                title: '编辑检查项',
                area: ['30%', '60%'],
                shade: 0,
                content: $("#add_form"),
                //btn: ['继续弹出', '全部关闭'], //只是为了演示
                yes: function () {
                    $(that).click();
                }
            });
            $('input[name="id"]').val(row.id);
            $('input[name="name"]').val(row.name);
            $('input[name="desc"]').val(row.desc);
            $('dd[lay-value]').attr('attr', 'layui-this');

            layui.use('form', function () {
                var form = layui.form();
                form.on('submit(formDemo)', function (data) {
                    //layer.msg(JSON.stringify(data.field));
                    $('#add_form').form('submit', {
                        url: "index.php?r=inspection/update",
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
     * 保存
     */
    function saveIns() {
        $('#fm').form('submit', {
            url: url,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {
                var result = eval('(' + result + ')');
                if (result.msg) {
                    $.messager.show({
                        title: '提示信息',
                        msg: result.msg
                    });
                    $('#tb').dialog('close');		// close the dialog
                    $('#tt').datagrid('reload');
                } else {
                    $('#tb').dialog('close');		// close the dialog
                    $('#tt').datagrid('reload');	// reload the  data
                }
            }
        });
    }

    /**
     * 删除
     */
    function destroyIns(index) {
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
                $.post('index.php?r=inspection/delete', {id: row.id}, function (result) {
                    if (result.status == 1) {
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