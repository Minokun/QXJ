<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_user_role".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $user_id
 */
class AdminUserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'user_id' => 'User ID',
        ];
    }
}
