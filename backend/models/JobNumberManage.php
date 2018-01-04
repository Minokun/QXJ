<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\helpers\Helper;
use common\models\User;
use common\models\AdminRole;
use common\models\AdminMenu;
use yii\base\Object;

/**
 * 修改密码表单 form
 * @author wuxiaokun
 * 
 */
class JobNumberManage extends Model
{
    public $emp_obj;
    public $role_obj;
    
    private function getEmpObj(){
        return $this->emp_obj = new User();
    } 
    
    private function getRoleObj(){
        return $this->role_obj = new AdminRole();
    }

    /**
     * 获取用户列表
     * 
     * @return array 
     */
    public function UserList($post)
    {
        $post = Helper::paganitionFilter($post);
        $res = $this->getEmpObj()->UserList($post);
        //给用户添加角色名
        $role_obj = new AdminRole();
        foreach($res as $k => $v){
            $role_arr = $role_obj->getRoleInfo($v['role']);
            $res[$k]['role_name'] = $role_arr['name'];
        }
        $help_obj =new Helper();
        $data['rows'] = $help_obj::UnixTimeConversion($res);
        $data['total'] = count($res);
        return $data;
    }
    
    /**
     * 删除一条用户
     * 
     * @param id    用户id
     * @return boolean  是否成功
     * @author wuxiaokun
     */
    public function UserDel($id)
    {
        //将用户删除状态更新为0
        $user_obj = User::findOne($id);
        $user_obj->status = 9;
        return $user_obj->save();
    }
    
    /**
     * 恢复删除的一条用户
     *
     * @param id    用户id
     * @return boolean  是否成功
     * @author wuxiaokun
     */
    public function UserRecovery($id)
    {
        //将用户删除状态更新为9
        $data['id'] = $id;
        $data['status'] = 1;
        return $this->getEmpObj()->updateUser($data);
    }
    
    /**
     * 添加新用户
     * @param $data 各个参数(key为字段名，value为需要改的参数)
     * @return array
     * @author wuxiaokun
     */
    public function AddNewUser($data)
    {
        if (!isset($data['role']) || empty($data['role'])){
            $data['role'] = 2;
        }
        //添加用户
        return $this->getEmpObj()->AddNewUser($data);
    }
    
    /**
     * 更新用户数据
     *
     * @param id    用户id
     * @return boolean  是否成功
     * @author wuxiaokun
     */
    public function UserInfoUpdate($data)
    {
        return $this->getEmpObj()->updateUser($data);
    }
    
    /**
     * 获取某用户信息
     *
     * @param id    用户id
     * @return array  
     * @author wuxiaokun
     */
    public function getUserInfo($id)
    {
        $user_obj = new User();
        return $user_obj->find()
        ->select('id,username,email,role,status,created_at,updated_at')
        ->where(['id' => $id])
        ->asArray()
        ->one();
    }
    
    /**
     * 通过角色id获取用户信息
     *
     * @param id    角色id
     * @return array
     * @author wuxiaokun
     */
    public function getUserInfoByRoleId($id)
    {
        $user_obj = new User();
        return $user_obj->find()
        ->select('id,username,email,role,status,created_at,updated_at')
        ->where(['role' => $id])
        ->asArray()
        ->all();
    }
    
    /**
     * 用户信息操作，判断是更新或者添加
     *
     * @author wuxiaokun
     */
    public function userInfoOpt($data)
    {
        //查询改用户是否存在
        if (User::findOne(['username' => $data['username']])){
            //编辑用户信息
            $status = $this->UserInfoUpdate($data);
        }else{
            //添加用户信息
            $status = $this->AddNewUser($data);
        }
        return $status;
    }
    
    /**
     * 获取全部角色信息
     *
     * @author wuxiaokun
     */
    public function getRoleInfo()
    {
        $role_obj = new AdminRole();
        return $role_obj->getRoleInfo();
    }
    
    /**
     * 根据角色ID获取菜单树数据
     *
     * @param $role_id 角色id
     * @author wuxiaokun
     */
    public function getMenuTreeData($role_id = 0)
    {
        if ($role_id <> 0){
            $role_obj = new AdminRole();
            $role_info = $role_obj->getRoleInfo($role_id);
            $role_id_arr = explode(',', $role_info['menu']);
        }else{
            $role_id_arr = [];
        }
        //获取所有菜单
        $menu_obj = new AdminMenu();
        $menu_arr = $menu_obj->find()
        ->select('id,menu_name,parent_id')
        ->where('parent_id = 0')
        ->asArray()
        ->all();
        //找出一级菜单
        foreach ($menu_arr as $key => $value){
            $first_menu[$key]['id'] = $value['id'];
            $first_menu[$key]['text'] = $value['menu_name'];
            $first_menu[$key]['state'] = "closed";
            //找出该菜单的子菜单
            $child_menu_arr = $menu_obj->find()
            ->select('id,menu_name')
            ->where('parent_id = ' . $value['id'])
            ->asArray()
            ->all();
            //如果有子菜单
            if (!empty($child_menu_arr)){
                foreach ($child_menu_arr as $k => $v){
                    $child_menu_arr[$k]['text'] = $child_menu_arr[$k]['menu_name'];
                    unset($child_menu_arr[$k]['menu_name']);
                    if (in_array($v['id'], $role_id_arr)){
                        $child_menu_arr[$k]['checked'] = true;
                    }
                }
                $first_menu[$key]['children'] = $child_menu_arr;
            }
        }
        
        return $first_menu;
        //找出子菜单
        
    }
    
    /**
     * 添加新角色
     * @param $data 各个参数(key为字段名，value为需要改的参数)
     * @return boolean
     * @author wuxiaokun
     */
    public function AddNewRole($data)
    {
        //添加新角色
        return $this->getRoleObj()->AddNewRole($data);
    }
    
    /**
     * 更新角色
     *
     * @param $data
     * @return boolean  是否成功
     * @author wuxiaokun
     */
    public function updateRole($data)
    {
        return $this->getRoleObj()->updateRole($data);
    }
    
    /**
     * 删除角色
     * @param $id role_id
     * @return boolean
     * @author wuxiaokun
     */
    public function delRole($id)
    {
        //首先判断该角色是否存在用户
        if (User::findOne(['role' => $id])){
            return -1;
        }else{
            //删除操作
            $status = AdminRole::deleteAll(['id' => $id]);
            return $status ? 1 : 0;
        }
    }
}
