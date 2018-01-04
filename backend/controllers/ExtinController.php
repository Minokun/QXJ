<?php
namespace backend\controllers;

use common\helpers\ExcelFactory;
use common\models\Extinguisher;
use common\models\ExtinguisherLocation;
use common\models\ExtinguisherLocationSearch;
use common\models\ExtinguisherSearch;
use yii;
use yii\web\Controller;
use common\helpers\Helper;
use yii\data\Pagination;

class ExtinController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 显示数据录入页面
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $unit_eid = isset($get['unit_eid']) ? $get['unit_eid'] : '';
        return $this->render('index', ['unit_eid' => $unit_eid
        ]);
    }


    /**
     * 显示灭火器数据录入页面
     * @return string
     */
    public function actionLocation()
    {
        return $this->render('location');
    }

    /**
     * 获取灭火器位置列表
     * @return mixed|string|void
     */
    public function actionLocation_list()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = ExtinguisherLocationSearch::locationList($post);

        return json_encode($model);
    }

    /**
     * 添加和修改灭火器时获取位置列表
     * @return mixed|string|void
     */
    public function actionGetLocationList()
    {
        $query = ExtinguisherLocation::find();
        $model = $query->select(['id', 'location'])->andFilterWhere(['status' => 1])->asArray()->all();
        return json_encode($model);
    }

    /**
     * 添加位置信息
     * @return mixed|string|void
     */
    public function actionAddLocation()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new ExtinguisherLocationSearch();
            $return_arr = $model->addLocation($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 修改灭火器信息
     * @return mixed|string|void
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new ExtinguisherSearch();
            $return_arr = $model->updateExt($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return json_encode($return_arr);
    }


    /**
     * 修改位置信息
     * @return mixed|string|void
     */
    public function actionUpdateLocation()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new ExtinguisherLocationSearch();
            $return_arr = $model->updateLocation($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 删除位置信息(软删除)
     * @return mixed|string|void
     */
    public function actionDeleteLocation()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new ExtinguisherLocationSearch();
            $return_arr = $model->deleteLocation($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '删除失败!',
            );
        }

        return json_encode($return_arr);
    }


    /**
     * 删除灭火器信息(软删除)
     * @return mixed|string|void
     */
    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new ExtinguisherSearch();
            $return_arr = $model->deleteExt($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '删除失败!',
            );
        }

        return json_encode($return_arr);
    }

    /**
     * easyUI展示数据列表
     * @return string
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = new ExtinguisherSearch();
        $model = $model->extList($post);
        return json_encode($model);
    }

    /*
     * 获取灭火器列表
     *
     * @author wuxiaokun
     */
    public
    function actionGetExtinguisherList()
    {
        $this->enableCsrfValidation = false;
        $post = Yii::$app->request->post();
        $result = $this->getExtinguisherObj()->GoodsSaleList($post);
        return json_encode($result);
    }

    /*
     * 删除灭火器
     *
     * @author wuxiaokun
     */
    public
    function actionDelGoodsSaleById()
    {
        $post = Yii::$app->request->post();
        $result = $this->getDataRecordManagerObj()->delGoodsSaleById($post);
        return $result;
    }

    /*
     * 编辑灭火器
     *
     * @author wuxiaokun
     */
    public
    function actionEdit()
    {
        $post = Yii::$app->request->post();
        $helper_obj = new Helper();
        $data = $helper_obj::ArrRremoveEmpty($post);
        $result = $this->getDataRecordManagerObj()->editGoodsSale($data);
        return $result;
    }

    /**
     * 添加灭火器
     * @return mixed|string|void
     */
    public
    function actionAdd()
    {
        if (Yii::$app->request->post()) {
            $model = new ExtinguisherSearch();
            $return_arr = $model->addExt(Yii::$app->request->post());
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 导出列表数据到Excel
     * @return yii\web\Response
     */
    public function actionExport()
    {
        $user_id = Yii::$app->getUser()->id;

        if (!Yii::$app->getSession()->hasFlash('ExtinDataTotalExport' . $user_id)) {
            return $this->redirect('/index.php?r=extin/index');
        }
        $data = \Yii::$app->getSession()->getFlash('ExtinDataTotalExport' . $user_id);

        $date = date("Y-m-d");
        $config = [
            'width' => '24',
            'height' => '15',
            'th' => [
                'eid' => '灭火器编号',
                'brand' => '品牌名',
                'model' => '型号',
                'manufacture_date' => '生产日期',
                'effective_date' => '有效期',
                'last_checkout_date' => '上次检验日期',
                'next_checkout_date' => '下次检验日期',
                'status' => '状态',
                'status_desc' => '状态说明',
                'person_in_charge' => '设备负责人',
                'location_number' => '所在位置',
                'created_time' => '录入时间',
                'updated_time' => '修改时间',
                'user_id' => '最后一次操作人',
            ],
            'rules' => [
                //                [['organizationPrice', 'totalPrice'], 'money'],
            ],
            'fileName' => $date . '灭火器数据统计',
        ];
        $export = ExcelFactory::exportInstance('StandardExport', $config);
        $export->load($data);
        $export->export();
    }


    protected
    function findExtModel($id)
    {
        if (($model = Extinguisher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }


    protected
    function findExtLocationModel($id)
    {
        if (($model = ExtinguisherLocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }
}
