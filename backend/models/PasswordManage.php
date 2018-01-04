<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\helpers\CurrentUser;
use common\models\User;
use yii\base\Object;

/**
 * 修改密码表单 form
 * @author wuxiaokun
 * 
 */
class PasswordManage extends Model
{

    /**
     * 验证修改
     * 
     * @param $old_password 用户输入的原始密码
     * @param $new_password 新密码
     * @return array 
     */
    public function PasswordChange($old_password,$new_password)
    {      
        $new_password_hash = Yii::$app->security->generatePasswordHash($new_password);
        $user_obj = new CurrentUser();
        $user_password_hash = $user_obj::UserInfo()->password_hash;
        $validate_status = Yii::$app->security->validatePassword($old_password, $user_password_hash);
        if (!$validate_status) {
            return ['status' => -1 , 'error' => '密码错误!'];
        }else{
            $emp_obj = User::findOne(['id' => $user_obj::UserInfo()->id]);
            $emp_obj->password_hash = $new_password_hash;
            if(!$emp_obj->save()){
                return ['status' => -2 , 'error' => '修改失败，请联系管理员!'];
            }else{
                return ['status' => 1 , 'error' => '修改成功!'];
            }
        }
    }
}
