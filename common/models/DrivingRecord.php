<?php

namespace common\models;

use Yii;
use common\helpers\Helper;
/**
 * This is the model class for table "driving_record".
 *
 * @property integer $id
 * @property integer $vehicle_id
 * @property integer $driver
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $gasoline
 * @property integer $start_kilometer
 * @property integer $end_kilometer
 */
class DrivingRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'driving_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vehicle_id', 'driver', 'start_at', 'end_at', 'gasoline', 'start_kilometer', 'end_kilometer'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vehicle_id' => '车辆id号',
            'driver' => '驾驶人id',
            'start_at' => '驾驶起始时间',
            'end_at' => '驾驶结束时间',
            'gasoline' => '本次加油费用，单位元',
            'start_kilometer' => '本次起始里程，单位公里',
            'end_kilometer' => '本次终止里程，单位公里',
        ];
    }
    
    private $helper_obj;
    
    private function getHelperObj()
    {
        if (!$this->helper_obj){
            $this->helper_obj = new Helper();
        }
        return $this->helper_obj;
    }
    
    /*
     * 获取统计列表
     * 
     */
    public function totalList($post)
    {
        $condition = "1 = 1 ";
        //*******************************构建查询条件*******************************
        //车牌号
        if (!empty($post['id'])){
            $condition .= " and v.id =" . $post['id'];
        }
        //驾驶员
        if (isset($post['driver']) && !empty($post['driver'])){
            $condition .= " and dr.driver =" . $post['driver'];
        }
        //开始时间
        if (isset($post['start_at'])){
            $condition .= " and dr.start_at >= " . $post['start_at'];
        }
        //结束时间
        if (isset($post['end_at'])){
            $condition .= " and dr.end_at <= " . $post['end_at'];
        }
        
        //*******************************获取数据*******************************
        //查询数据
        $sql = "
            select *
            from driving_record dr 
            left join vehicle v on dr.vehicle_id = v.id 
            left join user u on dr.driver = u.id
            where " . $condition . "
            ";
        //*******************************执行返回结果*******************************
        $res = $this
        ->findBySql($this->getHelperObj()->SqlReturn($sql),$this->getHelperObj()->SqlParamReturn($post))
        ->asArray()
        ->all();
        //时间戳转换
        $res_time_conversion = $this->getHelperObj()->UnixTimeConversion($res);
        
        return $res_time_conversion;
    }
}
