<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "file_manage".
 *
 * @property string $id
 * @property string $file_path
 * @property string $file_name
 * @property integer $file_category
 * @property string $ext_name
 * @property integer $user_id
 * @property integer $created_time
 * @property integer $updated_time
 * @property string $html_path
 */
class FileManage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_manage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_path', 'file_name', 'file_category', 'ext_name', 'user_id', 'created_time', 'updated_time'], 'required'],
            [['file_name'], 'string'],
            [['file_category', 'user_id', 'created_time', 'updated_time'], 'integer'],
            [['file_path', 'html_path'], 'string', 'max' => 200],
            [['ext_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_path' => 'File Path',
            'file_name' => 'File Name',
            'file_category' => 'File Category',
            'ext_name' => 'Ext Name',
            'user_id' => 'User ID',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'html_path' => 'Html Path',
        ];
    }
}
