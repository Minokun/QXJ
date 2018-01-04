<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FileManageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="file-manage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'file_path') ?>

    <?php echo $form->field($model, 'file_name') ?>

    <?php echo $form->field($model, 'file_category') ?>

    <?php echo $form->field($model, 'ext_name') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <div class="form-group">
        <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
