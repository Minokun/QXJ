<?php
namespace backend\controllers;

use common\helpers\CurrentMenu;
use backend\models\PasswordManage;
use Yii;

class PasswordManageController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('password');
    }
    
    /*
     * 修改密码
     * 
     */
    public function actionPasswordChange()
    {
        //接受数据
        $post = yii::$app->request->post();
        $password_manage_obj = new PasswordManage();
        $res = $password_manage_obj->PasswordChange($post['orginal_password'], $post['new_password']);
        return json_encode($res);
    }
    
}
