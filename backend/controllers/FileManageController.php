<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/2/6
 * Time: 10:45
 */

namespace backend\controllers;

use app\models\FileCategory;
use app\models\FileCategorySearch;
use app\models\FileManageSearch;
use common\helpers\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\FileManage;
use Yii;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UrlManager;


class FileManageController extends Controller
{
    /**
     * 文档下载与浏览
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FileManageSearch();
        if (!$_GET) {
            Yii::$app->request->queryParams = [
                'sort' => '-id',
            ];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 修改文件(名称,类别)
     * @return string
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_time = time();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', "更新文件" . $model->file_name . "成功");
                return $this->actionView($model->id);
            } else {
                Yii::$app->getSession()->setFlash('error', "更新文件" . $model->file_name . "失败");
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 查看文件详情
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * 在线预览文件(目前仅支持Word,Excel,PDF)
     * @param $id
     * @return string|void
     */
    public function actionScan($id)
    {
        $model = $this->findModel($id);
        //当根据文件后缀判断是否能预览
        if ($model->ext_name == 'xlsx' || $model->ext_name == 'xls') {
            set_time_limit(0);
            require Url::to('@common/widgets/excel/PHPExcel.php'); //更改为你的phpexcel文件地址
            $filePath = $model->file_path;
            if ($model->ext_name == 'xlsx') {
                $objReader = new \PHPExcel_Reader_Excel2007();
            } else {
                $objReader = new \PHPExcel_Reader_Excel5();
            }
            $model->view_times += 1;
            if ($model->save()) {
                $objWriteHtml = new \PHPExcel_Writer_HTML($objReader->load($filePath));
            }
            return $objWriteHtml->save("php://output");
        } elseif (($model->ext_name == 'doc' || $model->ext_name == 'docx') && $model->html_path != null && $model != '') {
            $model->view_times += 1;
            $status = $model->save() ? 1 : 0;
            return $status;
        } elseif ($model->ext_name == 'pdf') {
            header("Content-type: application/pdf");
            $model->view_times += 1;
            if ($model->save()) {
                readfile($model->file_path);
            }
        }
    }

    /**
     * 删除文件
     * @return string|\yii\web\Response
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        if (unlink($model->file_path) && $model->delete()) {
            Yii::$app->getSession()->setFlash('success', "删除文件" . $model->file_name . "成功");
        } else {
            Yii::$app->getSession()->setFlash('error', "删除文件" . $model->file_name . "失败");
            return $this->render('view', [
                'model' => $model,
            ]);
        }
        return $this->redirect(["index"]);
    }

    /**
     * 市局文档上传
     * @return string|\yii\web\Response
     */
    public function actionUpload()
    {
        $model = new FileManage();
        $file_path = "source/file/" . date('Ymd') . '/';//将上传图片尺寸修改为指定的再保存
        if (isset($_POST["FileManage"]["file_path"])) {
            $tmp_path = UploadedFile::getInstance($model, 'file_path');
            $ext_name = $tmp_path->getExtension();
            $randName = rand(0, 9999) . '_' . time() . '.' . $ext_name;

            if (!file_exists($file_path)) {
                mkdir($file_path, true);
                @chmod($file_path, 0755);
            }

            $tmp_path->saveAs($file_path . $randName);//保存文件到目录
            @chmod($file_path . $randName, 0666);

            $model->file_path = $file_path . $randName;
            $model->file_name = $tmp_path->name;
            $model->file_category = Yii::$app->request->post()['FileManage']['file_category'];
            $model->ext_name = $ext_name;
            $model->user_id = Yii::$app->getUser()->id;
            $model->created_time = time();
            $model->updated_time = time();

            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', "上传文件" . $model->file_name . "成功");
                return $this->redirect(["index"]);
            } else {
                Yii::$app->getSession()->setFlash('error', "上传文件" . $model->file_name . "失败");
                echo '<pre>';
                var_dump($model->errors);
                exit();
            }
        } else {
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 下载文件
     * @return $this|static
     */
    public function actionDown()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        return Yii::$app->response->sendFile($model->file_path, $attachmentName = $model->file_name);//第二个参数为用户下载时看到的文件名
    }

    /**
     * 文件类别列表
     * @return string
     */
    public function actionCategory()
    {
        $searchModel = new FileCategorySearch();
        if (!$_GET) {
            Yii::$app->request->queryParams = [
                'sort' => '-id',
            ];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('category', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 添加文件类别
     * @return \yii\web\Response
     */
    public function actionAdd_category()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = new FileCategorySearch();
            $model->name = $post['category_name'];
            $model->user_id = Yii::$app->getUser()->id;
            $model->created_time = time();
            $model->updated_time = time();
            if ($model->validate() && $model->save()) {
                echo '添加文件类别成功';
                return $this->redirect(["category"]);
            } else {
                echo '添加文件类别失败';
                var_dump($model->errors);
                exit();
            }
        } else {
            echo '数据传递失败';
            Yii::$app->getSession()->setFlash('error', "添加失败");
        }
    }

    /**
     * 修改文件类别
     * @return string
     */
    public function actionUpdate_category()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findCategoryModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->getUser()->id;
            $model->updated_time = time();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', "更新文件类别成功");
                return $this->actionCategory();
            } else {
                Yii::$app->getSession()->setFlash('error', "更新文件类别失败");
            }
        } else {
            return $this->render('update_category', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 删除文件类别
     * @return string|\yii\web\Response
     */
    public function actionDelete_category()
    {
        $id = Yii::$app->request->get('id');
        //删除前先判断是否有文件选中了该类别
        $selected_category = FileManageSearch::find()->andFilterWhere(['file_category' => $id])->count();
        if ($selected_category==0){
            $model = $this->findCategoryModel($id);
            if ($model->delete()) {
                Yii::$app->getSession()->setFlash('success', "删除文件类别成功");
            } else {
                Yii::$app->getSession()->setFlash('error', "删除文件类别失败");
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
        }else{
            Yii::$app->getSession()->setFlash('error', "该类别已被选中,请勿直接删除");
        }

        return $this->redirect(["category"]);
    }

    /**
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
     */
    protected function findCategoryModel($id)
    {
        if (($model = FileCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('您请求的页面不存在.');
        }
    }
}