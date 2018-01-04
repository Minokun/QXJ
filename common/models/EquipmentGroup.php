<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipment_group".
 *
 * @property string $id
 * @property string $name
 * @property string $inspections
 * @property integer $low_pass
 * @property integer $total_number
 * @property integer $pass_number
 * @property integer $per_pass
 * @property string $desc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $is_display
 */
class EquipmentGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'equipment_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'desc', 'created_at', 'updated_at', 'user_id', 'low_pass'], 'required'],
            [['created_at', 'updated_at', 'user_id', 'is_display', 'low_pass', 'total_number', 'pass_number', 'per_pass'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['inspections', 'desc'], 'string', 'max' => 200],
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
            'inspections' => 'Inspections',
            'low_pass' => 'Low Pass',
            'total_number' => 'Total Number',
            'pass_number' => 'Pass Number',
            'per_pass' => 'Per Pass',
            'desc' => 'Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'is_display' => 'Is Display',
        ];
    }
}
