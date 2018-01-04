<?php
namespace console\controllers;
use yii\console\Controller;
use common\helpers\Helper;
use common\models\FileManage;
use yii\base\Object;
/**
 * Test controller
 */
class WordController extends Controller 
{
  public function actionIndex() {
    //取出所有未转换的文档路径
    $file_manage_obj = new FileManage();
    $files_arr = $file_manage_obj
    ->find()
    ->where([
      'and',
        ['or' , 
            ['is' , 'html_path' , null] , 
            ['=' , 'html_path' , '']
        ],
        ['or' , 
            ['=' , 'ext_name' , 'doc'] , 
            ['=' , 'ext_name' , 'docx']
        ],
      ])
    ->asArray()
    ->all();
    //循环转换文件
    if (count($files_arr) > 0){
        foreach ($files_arr as $k => $v){
            $res = Helper::WordToHtml($v['file_path']);
            $file_obj = FileManage::findOne($v['id']);
            $file_obj->html_path = $res;
            $status = $file_obj->save();
        }
    }
    print_r("OK");
  }
  public function actionMail($to) {
    echo "Sending mail to " . $to;
  }
}