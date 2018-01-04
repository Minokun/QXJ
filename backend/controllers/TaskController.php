<?php
namespace backend\controllers;

use yii;
use backend\models\TaskManage;
use \yii\web\Controller;
use common\helpers\Helper;

class TaskController extends Controller
{
    public $task_manager_obj;
    
    /*
     * 实例化对象
     */
    public function getTaskManageObj(){
        return $this->task_manager_obj = new TaskManage();
    }
    
    /*
     * 显示任务管理页面
     * 
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * 获取任务列表
     *
     */
    public function actionGetList()
    {
        $post = Yii::$app->request->post();
        //一开始只显示待执行任务
        if (!isset($post['status'])){
            $post['status'] = 2;
        }
        $helper_obj = new Helper();
        $post = $helper_obj->ArrRremoveEmpty($post);
        $data = $this->getTaskManageObj()->TaskList($post);
        return $data;
    }
   
    /**
     * 获取模块选项
     *
     */
    public function actionGetModuleList()
    {
        $data = $this->getTaskManageObj()->TaskModuleList();
        $res[0]['id'] = '';
        $res[0]['text'] = '--全部--';
        foreach ($data as $k => $v){
            $res[$k + 1]['id'] = $v['id'];
            $res[$k + 1]['text'] = $v['module_name'];
        }
        return json_encode($res);
    }
    
    /**
     * 修改任务状态
     *
     */
    public function actionEditTaskStatus()
    {
        $post = Yii::$app->request->post();
        $status = $this->getTaskManageObj()->editTaskStatus($post);
        return $status;
    }
    
    /**
     * 获取所有二级菜单列表
     *
     */
    public function actionGetMenuList()
    {
        $res = $this->getTaskManageObj()->getMenuList();
        return json_encode($res);
    }
    
    /**
     * 修改任务状态
     *
     */
    public function actionAddTaskModule()
    {
        $post = Yii::$app->request->post();
        $status = $this->getTaskManageObj()->addTaskModule($post);
        return $status;
    }
}
