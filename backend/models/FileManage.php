<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file_manage".
 *
 * @property string $id
 * @property string $file_path
 * @property string $html_path
 * @property string $file_name
 * @property integer $file_category
 * @property string $ext_name
 * @property integer $user_id
 * @property integer $created_time
 * @property integer $updated_time
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
            [['file_path', 'file_name', 'file_category', 'ext_name', 'user_id', 'created_time', 'updated_time'], 'required', 'message' => '此处不能为空!'],
            [['file_category', 'user_id', 'created_time', 'updated_time'], 'integer'],
            [['file_path'], 'string', 'max' => 200],
            [['html_path'], 'string', 'max' => 200],
            [['file_name'], 'string', 'max' => 100],
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
            'file_path' => '文件路径',
            'file_name' => '文件名',
            'html_path' => 'html文件路径',
            'file_category' => '文件类别',
            'ext_name' => '扩展名',
            'user_id' => '操作用户id',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }
}
