<?php

namespace common\models;

use Yii;
use common\helpers\Helper;

/**
 * This is the model class for table "admin_task".
 *
 * @property integer $id
 * @property string $task_module
 * @property string $unit_eid
 * @property string $description
 * @property integer $deadline_at
 * @property integer $optor_id
 * @property string $opt_url
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updater_id
 */
class AdminTask extends \yii\db\ActiveRecord
{
    
    private $helper_obj;
    
    private function getHelperObj()
    {
        if (!$this->helper_obj){
            $this->helper_obj = new Helper();
        }
        return $this->helper_obj;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_module_id', 'deadline_at', 'optor_id', 'status', 'created_at', 'updated_at', 'updater_id' , 'unit_eid'], 'integer'],
            [['opt_url'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_module_id' => 'Task Module ID',
            'unit_eid' => 'Unit EID',
            'description' => 'Description',
            'deadline_at' => 'Deadline At',
            'optor_id' => 'Optor ID',
            'opt_url' => 'Opt Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updater_id' => 'Updater ID',
        ];
    }
    
    /*
     * 获取任务列表
     * @param 查询参数
     * @return array
     */
    public function TaskList($post)
    {
        $condition = " at.status <> 9";
        //*******************************构建查询条件*******************************
        //任务对象名
        if (!empty($post['unit_name'])){
            $condition .= " and at.unit_eid like '%" . $post['unit_eid'] .  "%'";
        }
        //任务状态
        if (isset($post['status'])){
            $condition .= " and at.status = " . $post['status'];
        }
        //任务模块
        if (isset($post['task_module_id'])){
            $condition .= " and at.task_module_id = " . $post['task_module_id'];
        }
    
        //*******************************获取数据*******************************
        //查询数据
        $sql = "
            select 
                at.id,
                at.task_module_id,
                at.unit_eid,
                at.description,
                at.deadline_at,
                at.opt_url,
                at.`status`,
                at.created_at,
                at.updated_at,
                u.username opt_name,
                atm.module_name,
                u1.username updater_name
            from admin_task at 
                left join `user` u on at.optor_id = u.id
                left join `user` u1 on at.updater_id = u1.id
                left join admin_task_module atm on at.task_module_id = atm.id
            where " . $condition . "
            ";
        //*******************************执行返回结果*******************************
        $res = $this
        ->findBySql($this->getHelperObj()->SqlReturn($sql),$this->getHelperObj()->SqlParamReturn($post))
        ->asArray()
        ->all();
        //时间戳转换
        $res_time_conversion = $this->getHelperObj()->UnixTimeConversion($res);
        
        return $res_time_conversion;
    }
}
