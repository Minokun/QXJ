<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FileManage */

$this->title = 'Create File Manage';
$this->params['breadcrumbs'][] = ['label' => 'File Manages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-create">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
