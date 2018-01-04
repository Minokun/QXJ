<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/2/6
 * Time: 10:49
 */

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use app\models\FileManageSearch;
use yii\helpers\Url;
use yii\web\AssetBundle;

$this->title = '市局文档上传';

$cssBlock = "
    .file-upload{
        margin:100px 100px 100px;
    }
    .btn-warning{
        margin-left:50px;
    }
";
$this->registerCss($cssBlock);
?>
<div class="location">
    <ul class="breadcrumb">
        <li class="active">市局文档上传</li>
    </ul>
</div>

<div class="file-upload">

    <div class="file-form">
        <?php echo Html::a('返回文档列表', [Url::to('index')], ['class' => 'btn btn-primary']) ?>

        <?php echo Html::a('编辑文件类别', [Url::to('category')], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <hr/>

    <?php echo $form->field($model, 'file_path')->fileInput(['type' => 'file', 'required' => 'required',])->label('请选择文档') ?>
    <hr/>
    <?php echo $form->field($model, 'file_category')->dropDownList(FileManageSearch::get_type(),
        ['prompt' => '--请选择文档分类--', 'style' => 'width:240px', 'required' => 'required', 'oninvalid' => "setCustomValidity('请选择分类')", 'oninput' => "setCustomValidity('')"])
    ?>
    <hr/>
    <div class="file-form">
        <?php echo Html::submitButton($model->isNewRecord ? '上传' : '替换', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>