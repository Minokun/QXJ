<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use app\models\FileManageSearch;

/* @var $this yii\web\View */
/* @var $model app\models\FileManage */


$this->title = '文档预览--' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '文档列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['/file-manage/scan','id'=>$model->id]);
$file_path = $model->file_path;
$js = <<<js
        $(".view").click(function(){
            //Ajax获取
            $.post('{$url}', {}, function(str){
                    layui.use(['layer','form','element','layedit'], function(){
                        var layer = layui.layer,
                            form  = layui.form()
                            element = layui.element()
                            layedit = layui.layedit;
                        layer.open({
                            type: 2,
                            title: '{$model->file_name}',
                            shadeClose: true,
                            fixed:false,
                            maxmin:true,
                            shade: 0.8,
                            area: ['680px','500px'],
                            content: '{$model->html_path}' //iframe的url
                        });
                    }); 
            });
        });
        $(".excel_view").click(function(){
            layui.use(['layer','form','element','layedit'], function(){
                var layer = layui.layer,
                    form  = layui.form()
                    element = layui.element()
                    layedit = layui.layedit;
                layer.open({
                    type: 2,
                    title: '{$model->file_name}',
                    shadeClose: true,
                    fixed:false,
                    maxmin:true,
                    shade: 0.8,
                    area: ['680px','500px'],
                    content: '$url' //iframe的url
                });
            });    
        });
js;
$this->registerJs($js);



?>
<div class="file-manage-view">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        <?php echo Html::a('返回', Yii::$app->request->getReferrer(), ['class' => 'btn btn-default']) ?>
        <?php echo Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
        if (in_array($model->ext_name, ['doc', 'docx']) && $model->html_path != null && $model->html_path != '')
         {
            echo Html::a('在线预览', 'javascript:;', ['class' => 'view btn btn-warning']);
        } elseif(in_array($model->ext_name, ['xls', 'xlsx','pdf'])){
            echo Html::a('在线预览', 'javascript:;', ['class' => 'excel_view btn btn-warning']);
        }else {
            echo Html::a('该文件无法预览', 'javascript:;', ['class' => 'btn btn-danger']);
        }
        ?>
        <?php echo Html::a('下载', ['down', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?php echo Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认删除该文件吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table id="w0" class="table table-striped table-bordered detail-view">
        <tbody>
        <tr>
            <th>ID</th>
            <td><?php echo $model->id ?></td>
        </tr>
        <tr>
            <th>文件名</th>
            <td><?php echo $model->file_name ?></td>
        </tr>
        <tr>
            <th>文件类别</th>
            <td><?php echo FileManageSearch::get_type_text($model->file_category); ?></td>
        </tr>
        <tr>
            <th>扩展名</th>
            <td><?php echo $model->ext_name ?></td>
        </tr>
        <tr>
            <th>创建时间</th>
            <td><?php echo date('Y-m-d', $model->updated_time); ?></td>
        </tr>
        <tr>
            <th>最近一次操作人员</th>
            <td>根据user部分完成后取用户名--<?php echo $model->id; ?></td>
        </tr>

        </tbody>
    </table>