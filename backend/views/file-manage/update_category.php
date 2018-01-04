<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FileManageSearch;

/* @var $this yii\web\View */
/* @var $model app\models\FileManage */

$this->title = '正在修改类别：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '类别列表', 'url' => ['category']];
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
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true,'style'=>'width:240px']) ?>
    <div class="form-group">
        <?php echo Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
