<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/3/6
 * Time: 14:28
 */
namespace backend\controllers;

use common\helpers\ExcelFactory;
use common\models\Equipment;
use common\models\EquipmentGroup;
use common\models\EquipmentGroupSearch;
use common\models\EquipmentSearch;
use common\models\Inspection;
use common\models\InspectionSearch;
use yii;
use yii\web\Controller;
use common\helpers\Helper;
use yii\data\Pagination;

class EquipmentController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 显示检查设备数据录入页面
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * 获取检查设备列表
     * @return mixed|string|void
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = new EquipmentSearch();
        $model = $model->equList($post);
        return json_encode($model);
    }

    /**
     * 获取勾选检查项列表
     * @return mixed|string|void
     */
    public function actionGetInsList()
    {
        $eq_id = Yii::$app->request->post('id');
        $group_id = $this->findModel($eq_id)->eq_group;
        $group = EquipmentGroupSearch::findOne($group_id);
        $ins = explode(",", $group->inspections);
        $ins_arr = InspectionSearch::find()
            ->select(['ins_id' => 'id', 'ins_name' => 'name'])
            ->andFilterWhere(['in', 'id', $ins])
            ->asArray()
            ->all();
        return json_encode($ins_arr);
    }

    /**
     * 操作检查项
     * @return mixed|string|void
     */
    public function actionCheck()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentSearch();
            $return_arr = $model->checkEqu($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '操作失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 获取检查设备分组列表
     * @return mixed|string|void
     */
    public function actionGetGroupList()
    {
        $query = EquipmentGroup::find();
        $model = $query
            ->select(['id', 'name'])
            ->andFilterWhere(['is_display' => 1])
            ->asArray()
            ->all();

        return json_encode($model);
    }

    /**
     * 添加检查设备
     * @return mixed|string|void
     */
    public function actionAdd()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentSearch();
            $return_arr = $model->addEqu($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return json_encode($return_arr);
    }


    /**
     * 修改检查设备
     * @return mixed|string|void
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentSearch();
            $return_arr = $model->updateEqu($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 删除检查设备(软删除)
     * @return mixed|string|void
     */
    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentSearch();
            $return_arr = $model->deleteEqu($post);
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
        if (($model = EquipmentSearch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }
}
