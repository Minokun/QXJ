<?php
namespace backend\controllers;

use yii;
use backend\models\JobNumberManage;
use \yii\web\Controller;
use common\helpers\Helper;

class JobNumberManageController extends Controller
{
    public $job_number_manager_obj;
    
    /*
     * 实例化对象
     */
    public function getJobNumberObj(){
        return $this->job_number_manager_obj = new JobNumberManage();
    }
    
    /*
     * 显示工号管理页面
     * 
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * 显示角色权限管理页面
     *
     * @author wuxiaokun
     */
    public function actionRole()
    {
        return $this->render('role');
    }
    
    /**
     * 获取员工信息列表
     *
     */
    public function actionGetList()
    {
        $params = Yii::$app->request->post();
		$data = $this->getJobNumberObj()->UserList($params);
		return json_encode($data);
    }
    
    /**
     * 用户信息操作，判断是更新或者添加
     *
     * @author wuxiaokun
     */
    public function actionUserInfoOpt()
    {
        $data = yii::$app->request->post();
        $status = $this->getJobNumberObj()->userInfoOpt($data);
        return $status;
    }
    
    /**
     * 添加新用户
     * 
     * @author wuxiaokun
     */
    public function actionAddNewUser()
    {
        $data = yii::$app->request->post();
        $status = $this->getJobNumberObj()->AddNewUser($data);
        return $status;
    }
    
    /*
     * 删除一个用户
     * 
     * @author wuxiaokun
     */
    public function actionDel()
    {
        $id = yii::$app->request->post('id');
        $status = $this->getJobNumberObj()->UserDel($id);
        return $status;
    }
    
    /*
     * 恢复一个用户
     * 
     * @author wuxiaokun
     */
    public function actionRecovery()
    {
        $id = yii::$app->request->post('id');
        $status = $this->getJobNumberObj()->UserRecovery($id);
        return $status;
    }
    
    /*
     * 更新用户信息
     * 
     * @author wuxiaokun
     */
    public function actionUpdateUserInfo()
    {
        $data = yii::$app->request->post();
        $status = $this->getJobNumberObj()->UserInfoUpdate($data);
        return $status;
    }
    
    /*
     * 获取角色信息
     *
     * @author wuxiaokun
     */
    public function actionGetRoleInfo()
    {
        $res = $this->getJobNumberObj()->getRoleInfo();
        return json_encode($res);
    }
    
    /*
     * 返回数据网格式的角色信息列表
     *
     * @author wuxiaokun
     */
    public function actionGetRoleList()
    {
        $helper_obj = new Helper();
        $res = $this->getJobNumberObj()->getRoleInfo();
        return $helper_obj->DataGridReturn($res);
    }
    
    /*
     * 返回数据网格式的角色信息列表拓展的详细信息
     *
     * @author wuxiaokun
     */
    public function actionGetRoleListDetail()
    {
        $id = yii::$app->request->get('id');
        $res = $this->getJobNumberObj()->getUserInfoByRoleId($id);
        $helper_obj = new Helper();
        $res = $helper_obj->UnixTimeConversion($res);
        $user_info = '';
        foreach ($res as $k => $v){
            $user_info .= '
                <tr>
                  <td>' . $v['id'] . '</td>
                  <td>' . $v['username'] . '</td>
                  <td>' . $v['email'] . '</td>
                  <td>' . $v['created_time'] . '</td>
                </tr>';
        }
        $html = '
            <table class="layui-table" lay-even="" lay-skin="nob">
              <colgroup>
                <col width="10%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
              </colgroup>
              <thead>
                <tr>
                  <th>用户ID</th>
                  <th>账号</th>
                  <th>联系方式</th>
                  <th>创建时间</th>
                </tr> 
              </thead>
              <tbody>
                ' . 
                $user_info
                . '
              </tbody>
            </table> 
            ';
        return $html;
    }
    
    /*
     * 根据角色id获取菜单树数据
     * 
     * @author wuxiaokun
     */
    public function actionGetMenuTreeData()
    {
        $role_id = yii::$app->request->get('role_id');
        $res = $this->getJobNumberObj()->getMenuTreeData($role_id);
        return json_encode($res);
    }
    
    /*
     * 角色信息操作
     *
     * @author wuxiaokun
     */
    public function actionRoleOpt()
    {
        $post = yii::$app->request->post();
        if (isset($post['id']) && !empty($post['id'])){
            $status = $this->getJobNumberObj()->updateRole($post);
        }else{
            $status = $this->getJobNumberObj()->AddNewRole($post);
        }

        return $status;
    }
    
    /*
     * 角色删除操作
     *
     * @author wuxiaokun
     */
    public function actionDelRole()
    {
        $id = yii::$app->request->post('id');
        $status = $this->getJobNumberObj()->delRole($id);
        return $status;
    }
}
