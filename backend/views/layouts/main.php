 <?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	
	<!-- 引入easyui -->
    <script type="text/javascript" src="source/easyui_1.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="source/easyui_1.5.1/jquery.easyui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="source/easyui_1.5.1/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="source/easyui_1.5.1/themes/icon.css">
    <script language="JavaScript" type="text/javascript" src="source/easyui_1.5.1/locale/easyui-lang-zh_CN.js"></script>
	
    <!-- 引入sweetalert -->
    <script src="source/sweetalert-master/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="source/sweetalert-master/dist/sweetalert.css">
	
	<!-- 引入layer -->
    <script src='./source/ext/layer/layer.js'></script>
    <link rel="stylesheet" type="text/css" href='./source/ext/layui/css/layui.css'>
    <script src='./source/ext/layui/layui.js'></script>
	
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
