<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inspection".
 *
 * @property string $id
 * @property string $name
 * @property string $desc
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $is_display
 */
class Inspection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inspection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['created_at', 'updated_at', 'user_id', 'is_display'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 200],
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
            'desc' => 'Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'is_display' => 'Is Display',
        ];
    }
}
