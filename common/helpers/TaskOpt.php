<?php
/**
 * Created by PhpStorm.
 * User: Geridge
 * Date: 2016/11/17
 * Time: 14:55
 */

namespace common\helpers;

use yii;
use common\models\AdminTaskModule;
use common\models\AdminTask;
use common\helpers\CurrentUser;
use yii\rest\UpdateAction;

/**
 * 助手。
 * Class ConstantHelper
 * @package common\helpers
 */
class TaskOpt
{
    private $admin_task_module_obj;
    private $admin_task_obj;
    
    private function getTaskModuleObj()
    {
        if (!$this->admin_task_module_obj){
            $this->admin_task_module_obj = new AdminTaskModule();
        }
        return $this->admin_task_module_obj;
    }
    
    private function getTaskObj()
    {
        if (!$this->admin_task_obj){
            $this->admin_task_obj = new AdminTask();
        }
        return $this->admin_task_obj;
    }
    
    /**
     * 获取任务模块列表
     * @return mixed
     */
    public static function getTaskModuleList($status)
    {
        $task_obj = new TaskOpt();
        $res = $task_obj->getTaskModuleObj()->getTaskModuleList($status);
        $menu_postfix = isset(Yii::$app->params['web_postfix']) ? Yii::$app->params['web_postfix'] : '/meterogy';
        $res['opt_url'] = $menu_postfix . '/index.php?r=task';
        //获取任务列表
        if (!empty($res['id'])){
            $res['task_list'] = $task_obj->getTaskObj()->find()->where('task_module_id = ' . $res['id'] . " and status = " . $status)->asArray()->all();
        }
        return $res;
    }
    
   /**
    * 添加任务接口
    * 
    * @param $task_module_id  任务模块id
    * @param $unit_eid      具体任务单位编号
    * @param $deadline_at   截止日期
    * @param $description   任务描述
    * @param $status        任务状态
    * @param $opt_id        任务执行人
    * 
    * @return boolean
    * @author wuxiaokun
    * 
    */
    public static function addTask($task_module_id,$unit_eid,$deadline_at,$description='',$status=2,$opt_id=NULL)
    {
        //查询模块信息
        $task_module_arr = $this->admin_task_module_obj->find()
        ->where('id = ' . $task_module_id)
        ->asArray()
        ->one();
        
        //填充参数
        $description = empty($description) ? '即将过期' : $description;
        $opt_id = empty($opt_id) ? CurrentUser::UserInfo()->id : $opt_id;
        
        //添加数据
        $this->admin_task_obj->task_module_id = $task_module_id;
        $this->admin_task_obj->unit_eid = $unit_eid;
        $this->admin_task_obj->deadline_at = $deadline_at;
        $this->admin_task_obj->description = $description;
        $this->admin_task_obj->optor_id = $opt_id;
        $this->admin_task_obj->opt_url = $task_module_arr['task_url'];
        $this->admin_task_obj->status = $status;
        $this->admin_task_obj->created_at = time();
        $this->admin_task_obj->updated_at = time();
        $this->admin_task_obj->updater_id = CurrentUser::UserInfo()->id;
        return $this->admin_task_obj->save();
        
    }
}