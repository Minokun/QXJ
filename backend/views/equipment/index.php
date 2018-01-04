<?php
use yii\helpers\Html;

$this->title = "检查设备管理";

?>
<script src="./source/ext/layui/lay/modules/laydate.js"></script>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">检查设备录入管理</li>
    </ul>
</div>

<p>
    <?php echo Html::a('编辑设备分组', ['/equipment-group'], ['class' => 'btn btn-primary']) ?>
</p>

<div class="dg">
    <div id="tb">
        <span class="glyphicon glyphicon-fire" style="color: rgb(255, 53, 60);;" aria-hidden="true"></span>检查设备名称:
        <input id="eq_name" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp
        <a href="#" class="easyui-linkbutton" iconcls="icon-search" plain="true" onclick="doSearch()">查询</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addEqu()">添加检查设备</a>
    </div>

    <table id="tt" class="easyui-datagrid" style="width:100%;"
           title="检查设备数据表"
           url="index.php?r=equipment/list"
           pagination="true"
           iconCls="icon-equipment-edit"
           toolbar="#tb"
           pageSize=20
           pageList=[10,20,30,40,50]
           singleSelect="true"
           sortor="true">
        <thead>
        <tr>
            <th field="eq_id" width="5%">编号</th>
            <th field="name" width="10%">名称</th>
            <th field="group_name" width="6%">分组</th>
            <th field="no_pass" width="14%">未通过项</th>
            <th field="pass_ins" width="0%" hidden></th>
            <th field="reason" width="18%">未通过原因</th>
            <th field="per_pass" width="3%">通过率</th>
            <th field="final_status" width="3%">合格</th>
            <th field="desc" width="17%">备注说明</th>

            <th field="created_time" width="6%">录入时间</th>
            <th field="updated_time" width="6%">修改时间</th>
            <th field="username" width="4%">操作人员</th>
            <th data-options="field:'_operate',width:'8%' , align:'center',formatter:formatOper">操作</th>
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
            <input type="text" name="name" required lay-verify="required" placeholder="请输入设备名称" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item" id="group_dom">
        <label class="layui-form-label">选择分组</label>
        <div class="layui-input-block" style="width: 46%">
            <select style="width:30% " name="eq_group" id="tag" lay-filter="group"
                    lay-verify="">
                <option id="group_1" value="">请选择检查设备分组</option>
            </select>
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


<form id="check_form" url="" hidden="" class="layui-form" action="" method="post">
    <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
    <input name="id" hidden="hidden" value="">
    <input name="group_id" hidden="hidden" value="">
    <div class="layui-form-item" pane="">
        <label class="layui-form-label">通过项</label>
        <div class="layui-input-block" id="pass_checkbox">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">未通过原因</label>
        <div class="layui-input-inline">
            <textarea type="textarea" name="reason" lay-verify="" placeholder="请输入未通过原因(选填)"
                      autocomplete="off"
                      class="layui-teatarea"></textarea>
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
            eq_name: $('#eq_name').val(),
        });
    }

    /**
     * 获取检查设备分组列表
     */
    function getGroupList(type, id=null) {
        $.ajax({
            type: 'POST',
            url: 'index.php?r=equipment/get-group-list',
            data: 11,
            success: function (obj) {
                var optionHtml = "";
                $.each(obj, function (idx, obj2) {
                    optionHtml += "<option class='tag_option' id=group" + obj2['id'] + " value=" + obj2['id'] + ">" + obj2['name'] + "</option>";
                });
                $(".tag_option").remove();
                $("#tag").append(optionHtml).attr('hidden', false);

                //向select中append之后需要再重新渲染(什么j8玩意儿??!!)
                layui.use(['form', 'element'], function () {
                    var form = layui.form();
                    var element = layui.element();
                    form.render('select');
                });
            },
            dataType: 'json'
        });
    }

    /**
     * 获取检查项列表
     */
    function getInsList(type, row=null) {
        var eq_id = (type == 2) ? {id: row.id,} : {id: null};
        $("#pass_checkbox").empty();
        $.ajax({
            type: 'POST',
            url: 'index.php?r=equipment/get-ins-list',
            data: eq_id,
            success: function (obj) {
                var checkbox = "";
                $.each(obj, function (idx, obj2) {
                    checkbox += "<input type='checkbox'  lay-skin='primary' title=" + obj2['ins_name'] + " name=ins[" + obj2['ins_id'] + "] value=" + obj2['ins_id'] + ">";
                });
                $("#pass_checkbox").append(checkbox);
                //向select中append之后需要再重新渲染(什么j8玩意儿??!!)
                layui.use(['form', 'element'], function () {
                    var form = layui.form();
                    var element = layui.element();
                    form.render('checkbox');
                    //渲染之后再显示选中项
                    if (type == 2) {
                        var ins = (row.pass_ins).split(',');
                        for (var i = 0; i < ins.length; i++) {
                            $('input[type="checkbox"][value=' + ins[i] + ']').attr('checked', 'checked').next().addClass(' layui-form-checked');
                        }
                    }
                });
            },
            dataType: 'json'
        });
    }


    /**
     * 操作检查结果
     */
    function checkEqu(index) {

        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        getInsList(2, row);
        if (row) {
            layer.open({
                type: 1, //此处以iframe举例
                title: '操作检查结果',
                area: ['30%', '60%'],
                shade: 0,
                content: $("#check_form"),
                //btn: ['继续弹出', '全部关闭'], //只是为了演示
                yes: function () {
                    $(that).click();
                }
            });
            $('input[name="id"]').val(row.id);
            $('input[name="group_id"]').val(row.eq_group);
            $('textarea[name="reason"]').val(row.reason);
//            $('dd[lay-value]').attr('attr', 'layui-this');

            layui.use('form', function () {
                var form = layui.form();
                form.on('submit(formDemo)', function (data) {
                    //layer.msg(JSON.stringify(data.field));
                    $('#check_form').form('submit', {
                        url: "index.php?r=equipment/check",
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
     * 添加检查设备
     */
    function addEqu() {
        $("#add_form")[0].reset();
        $("#tag").attr("disabled", false);
        $("#group_dom").show();
        getGroupList(type = 1);
        var index = layer.index;
        layer.open({
            type: 1, //此处以iframe举例
            title: '添加检查设备',
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
                    url: "index.php?r=equipment/add",
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
     * 编辑
     */
    function editEqu(index) {
        $("#tag").attr("disabled", "disabled");
        $("#group_dom").hide();
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');
        getGroupList(2, row.id);
        if (row) {
            layer.open({
                type: 1, //此处以iframe举例
                title: '编辑检查设备',
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
                        url: "index.php?r=equipment/update",
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
     * 删除
     */
    function destroyEqu(index) {
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
                $.post('index.php?r=equipment/delete', {id: row.id}, function (result) {
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

    div.layui-unselect {
        width: 77.5%;
    }

</style>