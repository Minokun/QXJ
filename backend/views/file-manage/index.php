<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\FileManageSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FileManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文档下载与浏览';
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="file-manage-index">
    <div class="location">
        <ul class="breadcrumb">
            <li class="active">文档下载与浏览</li>
        </ul>
    </div>
    <p>
        <?php echo Html::a('市局文档上传', ['upload'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}<div class="text-right tooltip-demo">{pager}</div>',
        'pager' => [
            //'options'=>['class'=>'hidden']//关闭分页
            'firstPageLabel' => "首页",
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'lastPageLabel' => '尾页',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'label' => '编号',
                'value' =>
                    function ($model) {
                        return $model->id;
                    },
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'file_name',
                'label' => '文件名',
                'value' =>
                    function ($model) {
                        return Html::a($model->file_name,
                            ['view', 'id' => $model->id]);
                    },
                'format' => 'raw',
                'headerOptions' => ['width' => '350'],
            ],
            [
                'attribute' => 'ext_name',
                'label' => '文件扩展名',
                'value' =>
                    function ($model) {
                        return $model->ext_name;   //主要通过此种方式实现
                    },
                'headerOptions' => ['width' => '350'],
            ],
            [
                'attribute' => 'file_category',
                'label' => '文件分类',
                'filter' => FileManageSearch::get_type(),
                'format' => [
                    'text',
                ],
                'value' => function ($model) {
                    return FileManageSearch::get_type_text($model->file_category);
                },
                'headerOptions' => ['width' => '120'],
            ],
            [
                'attribute' => 'created_time',
                'filter' => '',
                'label' => '上传时间',
                'value' =>
                    function ($model) {
                        return date('Y-m-d', $model->created_time);   //主要通过此种方式实现
                    },
                'headerOptions' => ['width' => '120'],
            ],
            [
                'attribute' => 'updated_time',
                'filter' => '',
                'label' => '更新时间',
                'value' =>
                    function ($model) {
                        return date('Y-m-d', $model->updated_time);   //主要通过此种方式实现
                    },
                'headerOptions' => ['width' => '120'],
            ],
            [
                //动作列yii\grid\ActionColumn
                //用于显示一些动作按钮，如每一行的更新、删除操作。
                'class' => 'yii\grid\ActionColumn',
                'header' => '选项',
                'template' => '{view} {down} {update} {delete}',//只需要展示删除和更新
                'headerOptions' => ['width' => '240'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i> 详情',
                            ['view', 'id' => $key],
                            [
                                'class' => 'btn btn-default btn-xs',
                            ]
                        );
                    },
                    'down' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-download-alt"></i> 下载',
                            ['down', 'id' => $key],
                            [
                                'class' => 'btn btn-default btn-xs',
                            ]
                        );
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i> 修改',
                            ['update', 'id' => $key],
                            [
                                'class' => 'btn btn-default btn-xs',
                            ]
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i> 删除',
                            ['delete', 'id' => $key],
                            [
                                'class' => 'btn btn-default btn-xs',
                                'data' => ['confirm' => '你确定要删除该文件吗？',]
                            ]
                        );
                    },
                ],
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
