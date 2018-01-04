<?php
/**
 * Created by Zendstudio.
 * User: wuxiaokun
 * Date: 16-11-11
 * Time: 上午2:32
 */

namespace common\helpers;

use Yii;

/*
 * 当前用户信息类
 */
class CurrentUser {
    
    /*
     * 获取当前用户的信息
     * 
     * @return object 返回用户信息的对象
     * @author wuxiaokun
     */
    public static function UserInfo() {
        return Yii::$app->user->identity;
    }
}