<?php
namespace console\controllers;
use yii\console\Controller;
use common\helpers\TaskOpt;
/**
 * Test controller
 */
class TaskController extends Controller 
{
  public function actionIndex() {
    //每天检查灭火器的任务，添加离有效期只剩天的设备
    
    
    print_r("OK");
  }
}