<?php

namespace common\models;

use Yii;
use common\helpers\CurrentUser;

/**
 * This is the model class for table "admin_task_module".
 *
 * @property integer $id
 * @property string $module_name
 * @property string $task_url
 */
class AdminTaskModule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_task_module';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_name'], 'string', 'max' => 50],
            [['task_url'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_name' => 'Module Name',
            'task_url' => 'Task Url',
        ];
    }
    
    /*
     * 获取某状态任务模块和数量,当前用户的
     * @param $status
     * @return array
     * @author wuxiaokun
     */
    public function getTaskModuleList($status)
    {
        $user_id = CurrentUser::UserInfo()->id;
        if ($user_id == 1){
            $sql = "
            select atm.id,atm.module_name,atm.task_url,count(atm.id) num
            from admin_task_module atm left join admin_task adt on atm.id = adt.task_module_id
            where adt.`status` = " . $status . "
            group by atm.id
            ";
        }else{
            $sql = "
            select atm.id,atm.module_name,atm.task_url,count(atm.id) num
            from admin_task_module atm left join admin_task adt on atm.id = adt.task_module_id
            where adt.`status` = " . $status . " and adt.optor_id = " . $user_id . "
            group by atm.id
            ";
        }
        $res = $this->findBySql($sql)->asArray()->one();
        return $res;
    }
}
