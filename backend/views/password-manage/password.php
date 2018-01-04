<?php 
$this->title = "密码管理";
?>

<div class="location">
	<ul class="breadcrumb">
		<li class="active">密码管理</li>
	</ul>
</div>

<form class="layui-form" action="">

    <div class="layui-form-item">
        <label class="layui-form-label">原密码</label>
            <div class="layui-input-inline">
              <input type="password" name="orginal_password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        <div class="layui-form-mid layui-word-aux">请输当前密码</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
              <input type="password" name="new_password" required lay-verify="required" placeholder="请输入新密码" autocomplete="off" class="layui-input">
            </div>
        <div class="layui-form-mid layui-word-aux">输入6位以上数字和字母组合的密码</div>
    </div>
  
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
              <input type="password" name="password_copy" required lay-verify="required" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
            </div>
        <div class="layui-form-mid layui-word-aux">两次输入需要一致</div>
    </div>
  
    <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script>
    layui.use('form', function(){
        var form = layui.form();
        
        //监听提交
        form.on('submit(formDemo)', function(data){
            if ($.trim(data.field.new_password) !== $.trim(data.field.password_copy)){
            	swal("咦...", "两次输入不一样哦，重来一次吧(￣▽￣)", "error");
            }else{
            	$.post('index.php?r=password-manage/password-change',{
                    	'orginal_password':data.field.orginal_password,
                		'new_password':data.field.new_password
            		},function(e){
        			if (e.status == -1){
        				swal("抱歉~", "原密码输入错误，要认真点哦~ o(*￣▽￣*)o", "error");
        			}else if(e.status == -2){
        				swal("啊！", "出现了未知问题修改失败了,还是呼叫管理员吧┭┮﹏┭┮", "error");
        			}else{
        				swal("Yes!", "密码修改成功勒，重新登陆一下吧!", "success");
        			}
        		},'json');
            }
            return false;
        });
    });
</script>