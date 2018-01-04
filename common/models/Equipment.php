<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipment".
 *
 * @property string $id
 * @property integer $eq_id
 * @property integer $eq_group
 * @property string $name
 * @property string $desc
 * @property string $pass_ins
 * @property string $no_pass
 * @property string $reason
 * @property integer $per_pass
 * @property integer $final_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $is_display
 */
class Equipment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'equipment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eq_id', 'eq_group', 'name', 'final_status', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['eq_id', 'eq_group','per_pass', 'final_status', 'created_at', 'updated_at', 'user_id', 'is_display'], 'integer'],
            [['reason'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['desc', 'no_pass','pass_ins'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eq_id' => 'Eq ID',
            'eq_group' => 'Eq Group',
            'name' => 'Name',
            'desc' => 'Desc',
            'pass_ins' => 'Pass Ins',
            'no_pass' => 'No Pass',
            'reason' => 'Reason',
            'per_pass' => 'Per Pass',
            'final_status' => 'Final Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'is_display' => 'Is Display',
        ];
    }
}
