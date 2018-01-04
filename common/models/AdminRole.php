<?php

namespace common\models;

use Yii;
use yii\base\Object;

/**
 * This is the model class for table "admin_role".
 *
 * @property integer $id
 * @property string $name
 * @property string $menu
 */
class AdminRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'menu'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'menu' => 'Menu',
        ];
    }
    
    /*
     * 获取角色信息
     * $id 角色id号
     */
    public function getRoleInfo($id = 0) 
    {
        if ($id == 0){
            $role_obj = new AdminRole();
            return $role_obj->find()
            ->where("id <> 1")
            ->asArray()
            ->all();
        }else{
            $role_obj = new AdminRole();
            return $role_obj->find()
            ->where("id = ".$id)
            ->asArray()
            ->one();
        }
    }
    
    /**
     * 添加新角色
     * @param $data 各个参数(key为字段名，value为需要改的参数)
     * @return array
     * @author wuxiaokun
     */
    public function AddNewRole($data)
    {
        //如果角色已存在，则返回false
        if ($this->findOne(['name' => $data['name']])){
            return -1;
        }
        //添加数据
        foreach ($data as $k => $v){
            $this->$k = $v;
        }
        $status = $this->save();
        return $status ? 1 : 0;
    }
    
    /*
     * 修改角色信息
     * @pparam $data 修改数据
     * @return array
     */
    public function updateRole($data)
    {
        $role_obj = $this->findOne(['id' => $data['id']]);
        unset($data['id']);
        foreach ($data as $k => $v){
            $role_obj->$k = $v;
        }
        if(!$role_obj->save()){
            return 0;
        }else{
            return 1;
        }
        //file_put_contents('a.txt', json_encode($post));
    }
}
