<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\helpers\CurrentUser;
use common\helpers\Helper;
use common\models\DrivingRecord;
use common\models\Vehicle;
use common\models\User;
use yii\base\Object;

/**
 * 修改密码表单 form
 * @author wuxiaokun
 * 
 */
class VehicleManage extends Model
{
    public $user_obj;
    public $dr_obj;
    public $vehicle_obj;
    public $helper_obj;
    
    private function getUserObj(){
        return $this->user_obj = new User();
    } 
    
    private function getVehicle(){
        return $this->vehicle_obj = new Vehicle();
    }
    
    private function getDringRecord(){
        return $this->dr_obj = new DrivingRecord();
    }
    
    private function getHelper(){
        return $this->helper_obj = new Helper();
    }

    /**
     * 获取用户列表
     * 
     * @return array 
     */
    public function UserList()
    {
        $res = $this->getUserObj()->find()->select("id,username")->where("status = 1 and role <> 1")->asArray()->all();
        return $res;
    }
    
    /**
     * 获取车辆列表
     *
     * @return array
     */
    public function vehicleList()
    {
        $res = $this->getVehicle()->find()->asArray()->all();
        return $res;
    }
    
    /**
     * 添加新记录
     * @param $data 各个参数(key为字段名，value为需要改的参数)
     * @return array
     * @author wuxiaokun
     */
    public function AddRecord($data)
    {
        $record_obj = new DrivingRecord();
        foreach ($data as $k => $v){
            $record_obj->$k = $v;
        }
        return $record_obj->save();
    }
    
    /**
     * 获取统计列表
     *
     * @return array
     */
    public function TotalList($post)
    {
        $res = $this->getDringRecord()->totalList($post);
        return $res;
    }
    
    /**
     * 获取当前用户未完成的行车记录
     *
     * @return array
     */
    public function getUnfinished()
    {
        $res[] = $this->getDringRecord()->find()->where("status = 9 and driver = " .  CurrentUser::UserInfo()->id)->asArray()->one();
        if (!empty($res[0])){
            $res = $this->getHelper()->UnixTimeConversion($res);
            $driver_arr = $this->getVehicle()->find()->select('plate_number')->where('id = ' . $res[0]['vehicle_id'])->asArray()->one();
            $res[0]['plate_number'] = $driver_arr['plate_number'];
        }
        return $res[0];
    }
    
    /**
     * 更新当前用户的行车记录
     *
     * @param $id
     * @param $end_kilometer
     * @param $gasoline
     * @return array
     * @author wuxiaokun
     */
    public function updateRecord($id,$end_kilometer,$gasoline = 0)
    {
        $data_obj = DrivingRecord::findOne($id);
        
        $data_obj->end_kilometer = $end_kilometer;
        $data_obj->gasoline = $gasoline;
        $data_obj->status = 1;
        $status = $data_obj->save();
        return $status;
    }
}
