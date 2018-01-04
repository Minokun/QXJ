<?php
/**
 * Created by Zendstudio.
 * User: wuxiaokun
 * Date: 16-11-11
 * Time: 上午2:59
 */

namespace common\helpers;

use Yii;
use common\models\AdminMenu;
use common\models\AdminRole;
use common\helpers\CurrentUser;
use yii\filters\AccessControl;
use yii\base\Object;
use yii\helpers\Url;

/*
 * 菜单类
 * 
 */
class CurrentMenu 
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // 设置actions的操作是允许访问还是拒绝访问
                        'allow' => true,
                        // @ 当前规则针对认证过的用户; ? 所有方可均可访问
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /*
     * 获取当前用户可用的菜单
     * 
     * @return array 返回菜单id组成的数组
     * @author wuxiaokun
     */
    public static function menuList()
    {
        //获取当前用户信息
        $menu_obj = new AdminMenu();
        $menu_arr = []; 
        
        //如果用户为超级管理员，既is_admin为1,则返回所有菜单
        $res = self::getAllChildMenu(0);
        
        $menu_header = array();
        $menu_header[0]['label'] = '系统菜单栏';
        $menu_header[0]['options']['class'] = 'header';
        $res = array_merge($menu_header,$res);
        $result['options'] = ['class' => 'sidebar-menu'];
        $result['items'] = $res;
        return $result;
    }
    
    /*
     * 获取某菜单的全部子菜单
     * @param $menu_id 菜单id
     * @return array 返回菜单id组成的数组
     * @author wuxiaokun
     */
    public static function getAllChildMenu($menu_id = 0)
    {
        $menu_obj = new AdminMenu();
        $menu_res_arr = array();
        $menu_arr = $menu_obj->find()->where(['parent_id' => $menu_id])->asArray()->all();
		$menu_postfix = isset(Yii::$app->params['web_postfix']) ? Yii::$app->params['web_postfix'] : '';
		//获取当前用户所有菜单
		$menu_ids = self::getFullMenu();
        if (empty($menu_arr)){
            return '';
        }else{
            foreach ($menu_arr as $k => $v){
                if (in_array($v['id'] , $menu_ids)){
                    $menu_res_arr[$k]['label'] = $v['menu_name'];
                    $menu_res_arr[$k]['icon'] = $v['icon'];
                    $menu_res_arr[$k]['url'] = $menu_postfix . $v['url'];
                    $res = self::getAllChildMenu($v['id']);
                    if (!empty($res)){
                        $menu_res_arr[$k]['items'] = $res;
                    }
                }
            }
            return $menu_res_arr;
        }
    
    }
    
    /*
     * 获取当前角色的完整菜单id
     * 
     */
    public static function getFullMenu() 
    {
        //获取用户的菜单id
        $role_id = CurrentUser::UserInfo()->role;
        $role_obj = new AdminRole();
        $menu_obj = new AdminMenu();
        $role_info = $role_obj->getRoleInfo($role_id);
        if (empty($role_info)){
            $role_info['menu'] = 1;
        }
        //普通用户获取该用户所在角色的菜单id
        if (CurrentUser::UserInfo()->id <> 1){
            $column = "parent_id";
            $condition = 'id in (' . $role_info['menu'] . ') and parent_id not in (' . $role_info['menu'] . ') and parent_id <> 0';
        }else{
            $column = "id";
            $condition = '';
        }
        
        //查出角色的菜单id
        $menu_ids = $menu_obj->find()
        ->select($column)
        ->distinct()
        ->where($condition)
        ->asArray()
        ->all();

        //返回菜单ids
        if (CurrentUser::UserInfo()->id == 1){   
            return array_column($menu_ids,'id');
        }else{
            $role_menu = empty($role_info['menu']) ? [] : explode(',', $role_info['menu']);
            $menu_ids = array_column($menu_ids,'parent_id');
            $menu_ids = empty($menu_ids) ? [] : $menu_ids;
            return array_merge($menu_ids,$role_menu);
        }
        
    }

}