<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 9;
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /*
     * 获取用户信息列表
     * @param 查询参数
     * @return array
     */
    public function UserList($post) 
    {
        $condition = " role <> 1";
        //*******************************构建查询条件*******************************
        //用户名
        if (!empty($post['username'])){
            $condition .= " and username like '%" . $post['username'] .  "%'";
        }
        //用户状态
        if (isset($post['status']) && $post['status'] == 9){
            $condition .= " and status = 9";
        }else{
            $condition .= " and status = 1";
        }
        
        //*******************************获取数据*******************************
        //查询数据
        $res = User::find()
        ->select('id,username,email,role,status,created_at,updated_at')
        ->where($condition)
        ->offset($post['offset'])
        ->orderBy([
            $post['sort'] => $post['yii2_order']
        ])
        ->limit($post['limit'])
        ->asArray()
        ->all();
        return $res;
    }
    
    /**
     * 添加新用户
     * @param $data 各个参数(key为字段名，value为需要改的参数)
     * @return array
     * @author wuxiaokun
     */
    public function AddNewUser($post)
    {
        //如果工号已存在，则返回false
        if ($this->findOne(['username' => $post['username']])){
            return 0;
        }
        //添加数据
        foreach ($post as $k => $v){
            $this->$k = $v;
        }
        $this->setPassword('12345678');
        $status = $this->save();
        return $status ? 1 : 0;
    }
    
    /*
     * 修改用户信息
     * @post 修改数据
     * @return array
     */
    public function updateUser($post) 
    {
        $user_obj = User::findOne(['username' => $post['username']]);
        foreach ($post as $k => $v){
            $user_obj->$k = $v;
        }
        if(!$user_obj->save()){
            return -1;
        }else{
            return 1;
        }
        //file_put_contents('a.txt', json_encode($post));
    }
    
}
