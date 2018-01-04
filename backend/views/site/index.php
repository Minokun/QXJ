<?php
use common\helpers\TaskOpt;
/* @var $this yii\web\View */
$this->title = '管理系统';
$opt_task_arr = TaskOpt::getTaskModuleList(2);
$opt_tasks = isset($opt_task_arr['task_list']) ? $opt_task_arr['task_list'] : [];
?>
<div class="content">
    <div style="margin-left:35%">
    <h1 style="margin-left:8%">WELCOME!</h1>
    <p class="lead">欢迎登录东胜气象设备安全管理系统！ </p>
</div>
<div id="tip">
    <ul >
        <li class="header">您有<?php echo empty($opt_task_arr['num']) ? 0 : $opt_task_arr['num'];?>项任务</li>
        <input value=<?php echo empty($opt_task_arr['num']) ? 0 : $opt_task_arr['num'];?> hidden="hidden" id="tn"/>
        <li>
            <ul class="menu" style="width:100%">
                <?php foreach ($opt_tasks as $k => $v){?>
                <li>
                    <a href="<?php echo $opt_task_arr['opt_url'];?>">
                        <i class="fa label-danger text-yellow"></i> 
                        <?php echo '<span style="font-size:0.8em">任务ID</span>:' . $v['id'] . ' ' .  $opt_task_arr['module_name'] . '&nbsp&nbsp&nbsp编号' . $v['unit_eid'] . '&nbsp&nbsp&nbsp' . $v['description'];?>
                    </a>
                </li>
                <?php }?>
            </ul>
        </li>
    </ul>
</div>
<script>
$('#tip').hide();
if ($("#tn").val() != "0"){
	layer.open({
	  type: 1
	  ,title:'提示信息'
	  ,skin: 'layui-layer-molv'
	  ,offset: 'rb' //具体配置参考：offset参数项
	  ,area: ['20%', '30%']
	  ,content: $('#tip')
	  ,shade: 0 //不显示遮罩
	});	
}

</script>