<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\helpers\Helper;
use common\models\User;
use common\models\AdminTask;
use common\models\AdminMenu;
use common\models\AdminTaskModule;
use yii\base\Object;



class TaskManage extends Model
{
    
    public $user_obj;
    public $task_obj;
    public $task_module_obj;
    private $helper_obj;
    
    private function getHelperObj()
    {
        if (!$this->helper_obj){
            $this->helper_obj = new Helper();
        }
        return $this->helper_obj;
    }
    
    private function getUserObj()
    {
        if (!$this->user_obj){
            $this->user_obj = new User();
        }
        return $this->user_obj;
    }
    
    private function getTaskObj()
    {
        if (!$this->task_obj){
            $this->task_obj = new AdminTask();
        }
        return $this->task_obj;
    }
    
    private function getTaskModuleObj()
    {
        if (!$this->task_module_obj){
            $this->task_module_obj = new AdminTaskModule();
        }
        return $this->task_module_obj;
    }
    
    /**
     * 获取任务列表
     * @param $post
     * @return array
     */
    public function TaskList($post)
    {
        $post = Helper::paganitionFilter($post);
        $res = $this->getTaskObj()->TaskList($post);
        foreach ($res as $k => &$v){
            $menu_postfix = isset(Yii::$app->params['web_postfix']) ? Yii::$app->params['web_postfix'] : '/meterogy';
            $v['opt_url'] = $menu_postfix . $v['opt_url'] . '&unit_eid=' . $v['unit_eid'];
        }
        //变成datagrid格式
        $data = $this->getHelperObj()->DataGridReturn($res);
        return $data;
    }
    
    /**
     * 获取任务模块列表
     * @return array
     */
    public function TaskModuleList()
    {
        return $this->getTaskModuleObj()->find()->asArray()->all();
    }
    
    /**
     * 修改任务状态
     * @param $post
     * @return array
     */
    public function editTaskStatus($post)
    {
        $task_obj = AdminTask::findOne(['id' => $post['id']]);
        $task_obj->status = $post['status'];
        return $task_obj->save();
    }
    
    /**
     * 获取菜单列表
     * 
     * @return array
     */
    public function getMenuList()
    {
        $admin_menu_obj = new AdminMenu;
        return $admin_menu_obj->find()
        ->select("menu_name,url")
        ->where("parent_id <> 0")
        ->asArray()
        ->all();
    }
    
    /**
     * 添加任务模块
     * @param $post
     * @return array
     */
    public function addTaskModule($post)
    {
        $module_obj = new AdminTaskModule();
        $module_obj->module_name = $post['module_name'];
        $module_obj->task_url = $post['url'];
        return $module_obj->save();
    }
}
