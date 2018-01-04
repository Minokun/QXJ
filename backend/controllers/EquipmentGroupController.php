<?php
namespace backend\controllers;

use common\helpers\ExcelFactory;
use common\models\EquipmentGroup;
use common\models\EquipmentGroupSearch;
use common\helpers\Helper;
use common\models\InspectionSearch;
use yii\data\Pagination;
use yii;
use yii\web\Controller;

class EquipmentGroupController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 显示数据录入页面
     * @author wuxiaokun
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * 获取分组列表
     * @return mixed|string|void
     */
    public function actionList()
    {
        $post = Yii::$app->request->post();
        $post = Helper::paganitionFilter($post);
        $model = new EquipmentGroupSearch();
        $model = $model->groupList($post);
        return json_encode($model);
    }

    /**
     * 添加和修改分组时获取检查项列表
     * @return mixed|string|void
     */
    public function actionGetInsList()
    {
        $query = InspectionSearch::find();
        $model = $query->select(['id', 'name'])->andFilterWhere(['is_display' => 1])->asArray()->all();
        return json_encode($model);
    }

    /**
     * 添加分组信息
     * @return mixed|string|void
     */
    public function actionAdd()
    {

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentGroupSearch();
            $return_arr = $model->addGroup($post);
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
            $model = new EquipmentGroupSearch();
            $return_arr = $model->updateGroup($post);
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return json_encode($return_arr);
    }

    /**
     * 删除分组信息(软删除)
     * @return mixed|string|void
     */
    public function actionDelete()
    {
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $model = new EquipmentGroupSearch();
            $return_arr = $model->deleteGroup($post);
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
        if (($model = EquipmentGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }

}
