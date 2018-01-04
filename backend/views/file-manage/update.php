<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FileManageSearch;

/* @var $this yii\web\View */
/* @var $model app\models\FileManage */

$this->title = '正在修改：' . $model->file_name;
$this->params['breadcrumbs'][] = ['label' => '文档预览', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';

$cssBlock = "
    .file-manage-update{
        margin:100px 100px 100px;
    }
";
$this->registerCss($cssBlock);

?>
<div class="file-manage-update">

    <h2><?php echo Html::encode($this->title) ?></h2>
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>
    <?php echo $form->field($model, 'file_category')->dropDownList(FileManageSearch::get_type(),
        ['prompt' => '--请选择文档分类--', 'style' => 'width:240px', 'required' => 'required', 'oninvalid' => "setCustomValidity('请选择分类')", 'oninput' => "setCustomValidity('')"])
    ?>
    <div class="form-group">
        <?php echo Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
