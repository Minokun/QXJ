<?php
namespace backend\controllers;

use yii;
use backend\models\VehicleManage;
use \yii\web\Controller;
use yii\filters\AccessControl;
use common\helpers\Helper;
use common\helpers\CurrentUser;


class VehicleController extends Controller
{
    public $vehicle_manager_obj;
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    
    /*
     * 实例化对象
     */
    public function getVehicleObj(){
        return $this->vehicle_manager_obj = new VehicleManage();
    }
    
    /*
     * 显示车辆录入页面
     * 
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * 移动端显示车辆录入页面
     *
     * @author wuxiaokun
     */
    public function actionMobile()
    {
        //获取当前用户行车记录
        $data = $this->getVehicleObj()->getUnfinished();
        if (empty($data)){
            return $this->renderAjax('mobile_start');
        }else{
            return $this->renderAjax('mobile_end',[
                'id' => $data['id'],
                'plate_number' => $data['plate_number'] , 
                'start_kilometer' => $data['start_kilometer'],
                'start_at' => $data['start_at'],
                'driver' => CurrentUser::UserInfo()->username,
            ]);
        }
        
    }
    
    /*
     * 显示车辆数据统计页面
     *
     * @author wuxiaokun
     */
    public function actionTotal()
    {
        return $this->render('total');
    }
    
    /**
     * 获取用户列表
     *
     */
    public function actionGetUserList()
    {
        $data = $this->getVehicleObj()->UserList();
        array_push($data, [
            'id' => '',
            'username' => '--全部--'
        ]);
        return json_encode($data);
    }
    
    /**
     * 获取车辆列表
     *
     */
    public function actionVehicleList()
    {
        $data = $this->getVehicleObj()->VehicleList();
        return json_encode($data);
    }
    
    /**
     * 添加新记录
     *
     * @author wuxiaokun
     */
    public function actionAddRecord()
    {
        //获取当前用户行车记录
        $data = $this->getVehicleObj()->getUnfinished();
        if (!empty($data)){
            return $this->actionMobile();
        }else{
            $data = yii::$app->request->post();
            $help_obj = new Helper();
            $data = $help_obj->ArrRremoveEmpty($data);
            $data['driver'] = isset($data['driver']) ? $data['driver'] : CurrentUser::UserInfo()->id;
            $data['start_at'] = isset($data['start_at']) ? strtotime($data['start_at']) : time();
            $data['end_at'] = isset($data['end_at']) ? strtotime($data['end_at']) : time();
            $data['status'] = isset($data['status']) ? $data['status'] : 1;
            $status = $this->getVehicleObj()->AddRecord($data);
            return $status ? 1 : 0;
        }
        
    }
    
    /**
     * 获取车辆信息列表
     *
     */
    public function actionGetList()
    {
        $params = Yii::$app->request->post();
		$data = $this->getVehicleObj()->UserList($params);
		
		return json_encode($data);
    }
    
    /**
     * 获取车辆汇总列表
     *
     */
    public function actionTotalList()
    {
        $params = Yii::$app->request->post();
        $params = Helper::paganitionFilter($params);
        $data = $this->getVehicleObj()->TotalList($params);
        return json_encode($data);
    }
    
    /**
     * 用户信息操作，判断是更新或者添加
     *
     * @author wuxiaokun
     */
    public function actionUserInfoOpt()
    {
        $data = yii::$app->request->post();
        $status = $this->getVehicleObj()->userInfoOpt($data);
        return $status;
    }
    

    
    /*
     * 删除一个记录
     * 
     * @author wuxiaokun
     */
    public function actionDel()
    {
        $id = yii::$app->request->post('id');
        $status = $this->getVehicleObj()->Del($id);
        return $status;
    }
 
    /*
     * 更新新车记录
     * 
     * @author wuxiaokun
     */
    public function actionUpdateRecord() 
    {
        $data = yii::$app->request->post();
        $id = yii::$app->request->post('id');
        $end_kilometer = yii::$app->request->post('end_kilometer');
        $gasoline = yii::$app->request->post('gasoline');
        
        $status = $this->getVehicleObj()->updateRecord($id,$end_kilometer,$gasoline);
        return $status;
    }
}
