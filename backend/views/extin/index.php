<?php
use yii\helpers\Html;

$this->title = "数据录入管理";

?>
<script src="./source/ext/layui/lay/modules/laydate.js"></script>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">灭火器数据录入管理</li>
    </ul>
</div>
<div class="dg">
    <div id="tb">
        <span class="glyphicon glyphicon-fire" style="color: rgb(255, 53, 60);;" aria-hidden="true"></span>灭火器编号:
        <input id="eid" value="<?php echo $unit_eid;?>" style="line-height:26px;border:1px solid #ccc">&nbsp&nbsp&nbsp&nbsp&nbsp

        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span>灭火器状态:&nbsp</span>
        <select name="status" id="search_status_tag"  class="easyui-combobox">
            <option id="status_search_0" selected value="">--全部--</option>
            <option id="status_search_1" value="1">正常</option>
            <option id="status_search_2" value="2">已替换</option>
            <option id="status_search_6" value="6">损坏</option>
            <option id="status_search_7" value="7">遗失</option>
            <option id="status_search_8" value="8">过期</option>
            <option id="status_search_9" value="9">未检验</option>
        </select>&nbsp&nbsp&nbsp&nbsp&nbsp

        <a id="check" href="#" class="easyui-linkbutton" iconcls="icon-search" plain="true" onclick="doSearch()">查询</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="exportExcel()">数据导出</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addExt()">添加灭火器</a>
    </div>

    <table id="tt" class="easyui-datagrid" style="width:100%;"
           title="灭火器数据表"
           url="index.php?r=extin/list"
           pagination="true"
           iconCls="icon-extin-edit"
           toolbar="#tb"
           pageSize=20
           pageList=[10,20,30,40,50]
           singleSelect="true"
           sortor="true">
        <thead>
        <tr>
            <th field="eid" width="8%">编号</th>
            <th field="brand" width="5%">品牌名</th>
            <th field="model" width="5%">型号</th>
            <th field="manufacture_date" width="6%" sortable="true">生产日期</th>
            <th field="effective_date" width="6%" sortable="true">有效期</th>
            <th field="last_checkout_date" width="7%" sortable="true">上次检验日期</th>
            <th field="next_checkout_date" width="7%" sortable="true">下次检验日期</th>
            <th field="status" width="" sortable="true" hidden>状态值</th>
            <th field="status_text" width="4%" sortable="true" data-options="formatter:formatStatus">状态</th>
            <th field="status_desc" width="11%" sortable="true">状态说明</th>
            <th field="location" width="15%">位置</th>
            <th field="l_id" hidden="hidden"></th>
            <th field="created_time" width="6%">录入时间</th>
            <th field="updated_time" width="6%">修改时间</th>
            <th field="username" width="4%">操作人</th>
            <th data-options="field:'_operate',width:'6%' , align:'center',formatter:formatOper">操作</th>
        </tr>
        </thead>
    </table>
</div>


<form id="add_form" url="" hidden="" class="layui-form" action="" method="post">
    <blockquote class="layui-elem-quote">提交前请核对信息是否正确</blockquote>
    <input name="id" hidden="hidden" value="">
    <div class="layui-form-item">
        <label class="layui-form-label">品牌</label>
        <div class="layui-input-inline">
            <input type="text" name="brand" required lay-verify="required" placeholder="请输入品牌名" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">型号</label>
        <div class="layui-input-inline">
            <input type="text" name="model" placeholder="请输入型号" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block" style="width: 46%">
            <select name="status" id="status_tag" lay-search lay-filter="location" lay-verify="required">
                <option id="status_option_0" selected value="">请选择一个状态</option>
                <option id="status_option_1" value="1">正常</option>
                <option id="status_option_2" value="2">已替换</option>
                <option id="status_option_6" value="6">损坏</option>
                <option id="status_option_7" value="7">遗失</option>
                <option id="status_option_8" value="8">过期</option>
                <option id="status_option_9" value="9">未检验</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">状态说明</label>
        <div class="layui-input-inline">
            <textarea name="status_desc" required lay-verify="required" placeholder="请输入状态说明" autocomplete="off"
                   class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item" id="location_dom">
        <label class="layui-form-label">位置</label>
        <div class="layui-input-block" style="width: 46%">
            <select style="width: " hidden="hidden" name="location_number" id="tag" lay-filter="location"
                    lay-verify="required">
                <option id="location_1" value="">请选择一个位置</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">生产日期</label>
        <div class="layui-form-pane" style="margin-top: 15px;">
            <div class="layui-input-inline">
                <input class="layui-input" placeholder="生产日期" name="manufacture_date" id="manufacture_date">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">有效日期</label>
        <div class="layui-form-pane">
            <div class="layui-input-inline">
                <input class="layui-input" placeholder="有效日期" name="effective_date" id="effective_date">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">上次检验日</label>
        <div class="layui-form-pane">
            <div class="layui-input-inline">
                <input class="layui-input" placeholder="上次检验日期" name="last_checkout_date"
                       id="last_checkout_date">
            </div>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">下次检验日</label>
        <div class="layui-form-pane">
            <div class="layui-input-inline">
                <input class="layui-input" placeholder="下次检验日期" name="next_checkout_date"
                       id="next_checkout_date">
            </div>
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
$('#tt').datagrid({
	onLoadSuccess: function(data){
		if ($('#eid').val() != ''){
    		$('#tt').datagrid('reload',{
    			eid: $('#eid').val()
    		});
    		$('#eid').val('');
		}
	}
});
    /**
     * 操作选项
     */
    function formatOper(val, row, index) {
        var edit_button = '<a href="#" class="easyui-linkbutton" onclick="editExt(' + index + ')">修改</a>';
        var del_button = '<a href="#"  class="easyui-linkbutton" data-options="iconCls:\'icon-remove\'" onclick="destroyExt(' + index + ')">删除</a>';
        return edit_button + "&nbsp&nbsp&nbsp&nbsp" + del_button;
    }

    /**
     * 状态颜色区分
     */
    function formatStatus(val, row, index) {
    	var sta = '';
    	switch(parseInt(row.status)){
        	case 1:
        		sta = '<font color="green">' + val + '</font>';
        		break;
        	case 8:
        		sta = '<font color="red">' + val + '</font>';
        		break;
        	default:
            	sta = '<font color="#A6A600">' + val + '</font>';
        		break;
    	}
    	return sta;
    }

    /**
     * 查询
     */
    function doSearch() {
        $('#tt').datagrid('load', {
            eid: $('#eid').val(),
            search_status: $('#search_status_tag').val(),
        });
    }

    /**
     * 结果列表导出为Excel
     * @constructor
     */
    function exportExcel() {
        //利用PHPExcel实现查询结果导出为Excel
        window.location = 'index.php?r=extin/export';
    }

    /**
     * 获取位置列表
     */
    function getLocationList(type, l_id=null) {
        $.ajax({
            type: 'POST',
            url: 'index.php?r=extin/get-location-list',
            data: 11,
            success: function (obj) {
                var optionHtml = "";
                $.each(obj, function (idx, obj2) {
                    if (l_id == obj2['id']) {
                        $("#status_option_0").attr('selected', false);
                        optionHtml += "<option class='tag_option' selected id=location" + obj2['id'] + " value=" + obj2['id'] + ">" + obj2['location'] + "</option>";
                    } else {
                        optionHtml += "<option class='tag_option' id=location" + obj2['id'] + " value=" + obj2['id'] + ">" + obj2['location'] + "</option>";
                    }
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
     * 添加灭火器
     */
    function addExt() {
        //重置表单
        $("#add_form")[0].reset();
        getLocationList(type = 1);
        var index = layer.index;
        layer.open({
            type: 1, //此处以iframe举例
            title: '添加灭火器',
            area: ['25%', '80%'],
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
                    url: "index.php?r=extin/add",
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
     * 编辑灭火器
     */
    function editExt(index) {
        $('#tt').datagrid('selectRow', index);
        var row = $('#tt').datagrid('getSelected');

        getLocationList(2, row.l_id, row);


        if (row) {

            layer.open({
                type: 1, //此处以iframe举例
                title: '编辑灭火器',
                area: ['25%', '80%'],
                shade: 0,
                content: $("#add_form"),
                //btn: ['继续弹出', '全部关闭'], //只是为了演示
                yes: function () {
                    $(that).click();
                }
            });
            $('input[name="id"]').val(row.id);
            $('input[name="brand"]').val(row.brand);
            $('input[name="model"]').val(row.model);
            $('input[name="manufacture_date"]').val(row.manufacture_date);
            $('input[name="effective_date"]').val(row.effective_date);
            $('input[name="last_checkout_date"]').val(row.last_checkout_date);
            $('input[name="next_checkout_date"]').val(row.next_checkout_date);
            $('select[name="status"]').val(row.status);
            $('textarea[name="status_desc"]').val(row.status_desc);
            $('input[name="person_in_charge"]').val(row.person_in_charge);
            $('dd[lay-value]').attr('attr', 'layui-this');

            layui.use('form', function () {
                var form = layui.form();
                form.on('submit(formDemo)', function (data) {
                    //layer.msg(JSON.stringify(data.field));
                    $('#add_form').form('submit', {
                        url: "index.php?r=extin/update",
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
    function saveExt() {
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
    function destroyExt(index) {
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
                $.post('index.php?r=extin/delete', {id: row.id}, function (result) {
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
<script>
    layui.use('laydate', function () {
        var laydate = layui.laydate;
        var start = {
            max: '2099-06-16 23:59:59',
            istoday: false,
            choose: function (datas) {
                //end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };

        var end = {
            max: '2099-06-16 23:59:59',
            istoday: false,
            choose: function (datas) {
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };

        document.getElementById('manufacture_date').onclick = function () {
            start.elem = this;
            laydate(start);
        }
        document.getElementById('effective_date').onclick = function () {
            end.elem = this
            laydate(end);
        }
        document.getElementById('last_checkout_date').onclick = function () {
            start.elem = this;
            laydate(start);
        }
        document.getElementById('next_checkout_date').onclick = function () {
            end.elem = this
            laydate(end);
        }

    });

</script>
<style>
    .panel-body {
        padding: 0;
    }

    div.layui-unselect {
        width: 80%;
    }
</style>