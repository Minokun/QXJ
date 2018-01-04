<?php

use yii\helpers\Html;
use common\helpers\CurrentUser;
use common\helpers\TaskOpt;
//获取当前用户的信息
$username = CurrentUser::UserInfo()->username;
$created_time = date('Y-m-d',CurrentUser::UserInfo()->created_at);
//获取任务信息
$expire_task_arr = TaskOpt::getTaskModuleList(3);
$expire_tasks = isset($expire_task_arr['task_list']) ? $expire_task_arr['task_list'] : [];
$opt_task_arr = TaskOpt::getTaskModuleList(2);
$opt_tasks = isset($opt_task_arr['task_list']) ? $opt_task_arr['task_list'] : [];
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">管理台</span><span class="logo-lg">' . yii::$app->params['websiteName'] . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" >
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
             
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?php echo isset($expire_task_arr['num']) ? $expire_task_arr['num'] : '';?></span>
                    </a>
                    
                    <ul class="dropdown-menu">
                        <li class="header">您有<?php echo empty($expire_task_arr['num']) ? 0 : $expire_task_arr['num'];?>个任务已过期</li>
                        <li>
                            <ul class="menu">
                                <?php foreach ($expire_tasks as $k => $v){?>
                                <li>
                                    <a href="<?php echo $expire_task_arr['opt_url'];?>">
                                        <i class="fa fa-warning text-yellow"></i> 
                                        <?php echo 'ID号为' . $v['id'] . '的' .  $expire_task_arr['module_name'] . '&nbsp&nbsp&nbsp编号' . $v['unit_eid'] . '&nbsp&nbsp&nbsp' . $v['description'];?>
                                    </a>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                    </ul>
                </li>
                
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger"><?php echo isset($opt_task_arr['num']) ? $opt_task_arr['num'] : '';?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">您有<?php echo empty($opt_task_arr['num']) ? 0 : $opt_task_arr['num'];?>项任务</li>
                        <li>
                            <ul class="menu">
                                <?php foreach ($opt_tasks as $k => $v){?>
                                <li>
                                    <a href="<?php echo $opt_task_arr['opt_url'];?>">
                                        <i class="fa label-danger text-yellow"></i> 
                                        <?php echo 'ID号为' . $v['id'] . '的' .  $opt_task_arr['module_name'] . '&nbsp&nbsp&nbsp编号' . $v['unit_eid'] . '&nbsp&nbsp&nbsp' . $v['description'];?>
                                    </a>
                                </li>
                                <?php }?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?php echo $username;?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                欢迎您！ - <?php echo $username;?>
                                <small>加入时间&nbsp&nbsp <?php echo $created_time;?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    '修改密码',
                                    ['/password-manage'],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出登录',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
