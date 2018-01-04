<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/2/7
 * Time: 13:46
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = '编辑文件类别';
$request_url = Url::to(['/file-manage/add_category']);
$jsBlock = "
$(\"#add_category\").click(function () {
        var csrfToken = $('meta[name=\"csrf-token\"]').attr(\"content\");
        if($('#add_category_name').val()==null || $('#add_category_name').val()=='') {
            alert('请输入名称!');
            return;
        }
        $.ajax({
            url: '{$request_url}',
            type: 'POST', //GET
            async: true,    //或false,是否异步
            data: {
                _csrf: csrfToken,
                category_name: $('#add_category_name').val(),
            },
            timeout: 5000,    //超时时间
            dataType: 'text',    //返回的数据格式：json/xml/html/script/jsonp/text
            success: function (data) {
            $('#myModal').modal('toggle')
            }
        });
    });
";
$this->registerJs($jsBlock);
?>

<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    添加文件类别
</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="add_category">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">输入新的文件类别</h4>
                </div>
                <div class="modal-body">
                    <input id="add_category_name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button id="add_category" onclick="" type="button" class="btn btn-primary">保存
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>


<div class="file-manage-index">
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
            'id',
            'name',
            [
                'attribute' => 'user_id',
                'label' => '操作用户id',
                'value' =>
                    function ($model) {
                        return $model->user_id;   //主要通过此种方式实现
                    },
                'headerOptions' => ['width' => '250'],
            ],
            [
                'attribute' => 'created_time',
                'label' => '创建时间',
                'value' =>
                    function ($model) {
                        return date('Y-m-d H:i:s', $model->created_time);
                    },
                'headerOptions' => ['width' => '250'],
                'filter' => '',
            ],
            [
                'attribute' => 'updated_time',
                'label' => '修改时间',
                'value' =>
                    function ($model) {
                        return date('Y-m-d H:i:s', $model->updated_time);
                    },
                'headerOptions' => ['width' => '250'],
                'filter' => '',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '选项',
                'template' => ' {update} {delete}',//只需要展示删除和更新
                'headerOptions' => ['width' => '240'],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i> 修改类别',
                            ['update_category', 'id' => $key],
                            ['class' => 'btn btn-default btn-xs',]
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i> 删除类别',
                            ['delete_category', 'id' => $key],
                            [
                                'class' => 'btn btn-default btn-xs',
                                'data' => ['confirm' => '你确定要删除该类别吗？',]
                            ]
                        );
                    },
                ],
            ],
//        ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>