<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "extinguisher_location".
 *
 * @property string $id
 * @property string $building
 * @property integer $floor
 * @property string $location
 * @property string $location_detail
 * @property integer $created_at
 * @property integer $status
 * @property integer $updated_at
 * @property integer $user_id
 */
class ExtinguisherLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extinguisher_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['building', 'floor', 'location', 'location_detail', 'created_at', 'updated_at', 'user_id','status'], 'required'],
            [['floor', 'created_at', 'updated_at', 'user_id','status'], 'integer'],
            [['building', 'location'], 'string', 'max' => 100],
            [['location_detail'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'building' => '楼栋',
            'floor' => '楼层',
            'location' => '位置',
            'location_detail' => '位置详情',
            'created_at' => '录入时间',
            'updated_at' => '更新时间',
            'user_id' => '操作人员ID',
            'status' => '是否显示  1:显示  9:隐藏'
        ];
    }
}
