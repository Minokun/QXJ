<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "extinguisher".
 *
 * @property string $id
 * @property integer $eid
 * @property string $brand
 * @property string $model
 * @property integer $manufacture_date
 * @property integer $effective_date
 * @property integer $last_checkout_date
 * @property integer $next_checkout_date
 * @property integer $status
 * @property string $status_text
 * @property string $status_desc
 * @property integer $location_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $is_display
 */
class Extinguisher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extinguisher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eid', 'brand', 'model', 'manufacture_date', 'effective_date', 'last_checkout_date', 'next_checkout_date', 'status', 'location_number', 'created_at', 'updated_at', 'user_id', 'is_display'], 'required'],
            [['eid', 'manufacture_date', 'effective_date', 'last_checkout_date', 'next_checkout_date', 'location_number', 'created_at', 'updated_at', 'user_id', 'status', 'is_display'], 'integer'],
            [['status_desc','status_text'], 'string'],
            [['brand', 'model'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eid' => '灭火器编号',
            'brand' => '品牌名',
            'model' => '型号',
            'manufacture_date' => '生产日期',
            'effective_date' => '有效日期',
            'last_checkout_date' => '上次检验时间',
            'next_checkout_date' => '下次检验时间',
            'status' => '状态 1:在用 2:已替换 3:缺失  6:损坏 7:遗失 8:过期 9:未检验',
            'status_text' => '具体状态值',
            'status_desc' => '状态说明',
            'location_number' => '位置编号',
            'created_at' => '录入时间',
            'updated_at' => '更新时间',
            'user_id' => '操作人员ID',
            'is_display' => '是否显示  1:显示  9:隐藏',
        ];
    }
}
