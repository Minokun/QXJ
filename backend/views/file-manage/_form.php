<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FileManage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="file-manage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'file_path')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'file_category')->textInput() ?>

    <?php echo $form->field($model, 'ext_name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'created_time')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
