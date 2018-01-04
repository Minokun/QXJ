<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file_category".
 *
 * @property string $id
 * @property string $name
 * @property integer $user_id
 * @property integer $created_time
 * @property integer $updated_time
 */
class FileCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'created_time', 'updated_time'], 'required'],
            [['user_id', 'created_time', 'updated_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'user_id' => 'User ID',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}
