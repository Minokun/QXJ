<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/3/6
 * Time: 14:28
 */
namespace backend\controllers;

use common\helpers\ExcelFactory;
use common\models\Inspection;
use common\models\InspectionSearch;
use yii;
use yii\web\Controller;
use common\helpers\Helper;
use yii\data\Pagination;

class InspectionController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 显示检查项数据录入页面
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * 获取检查项列表
     * @return mixed|string|void
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = new InspectionSearch();
        $model = $model->insList($post);
        return json_encode($model);
    }

    /**
     * 添加检查项
     * @return mixed|string|void
     */
    public function actionAdd()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new InspectionSearch();
            $return_arr = $model->addIns($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return json_encode($return_arr);
    }


    /**
     * 修改检查项
     * @return mixed|string|void
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new InspectionSearch();
            $return_arr = $model->updateIns($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 删除检查项(软删除)
     * @return mixed|string|void
     */
    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new InspectionSearch();
            $return_arr = $model->deleteIns($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '删除失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * @param $id
     * @return static
     */
    protected function findModel($id)
    {
        if (($model = Inspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }
}
