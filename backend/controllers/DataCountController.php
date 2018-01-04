<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/3/9
 * Time: 14:14
 */

namespace backend\controllers;

use common\helpers\ExcelFactory;
use common\models\DataCount;
use common\models\EquipmentGroupSearch;
use common\models\Inspection;
use common\models\InspectionSearch;
use yii;
use yii\web\Controller;
use common\helpers\Helper;
use yii\data\Pagination;

class DataCountController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 显示数据统计页面
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * 获取分组统计列表
     * @return mixed|string|void
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = new DataCount();
        $model = $model->dataList($post);
        return json_encode($model);
    }

    /**
     * 导出列表数据到Excel
     * @return yii\web\Response
     */
    public function actionExport()
    {
        $user_id = Yii::$app->getUser()->id;

        if (!Yii::$app->getSession()->hasFlash('DataCountExport' . $user_id)) {
            return $this->redirect('/index.php?r=data-count/index');
        }
        $data = \Yii::$app->getSession()->getFlash('DataCountExport' . $user_id);

        $date = date("Y-m-d");
        $config = [
            'width' => '24',
            'height' => '15',
            'th' => [
                'id' => '分组编号',
                'name' => '分组',
                'total_number' => '设备数量',
                'pass_number' => '合格数量',
                'per_pass' => '合格率',
                'created_time' => '录入时间',
                'updated_time' => '修改时间',
                'user_id' => '最后一次操作人',
            ],
            'rules' => [
                //                [['organizationPrice', 'totalPrice'], 'money'],
            ],
            'fileName' => $date . '数据汇总统计',
        ];
        $export = ExcelFactory::exportInstance('StandardExport', $config);
        $export->load($data);
        $export->export();
    }
}
