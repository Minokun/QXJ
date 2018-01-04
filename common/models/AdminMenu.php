<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_menu".
 *
 * @property integer $id
 * @property string $parent_id
 * @property string $menu_name
 * @property string $icon
 * @property string $url
 * @property integer $sort
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['menu_name', 'icon'], 'required'],
            [['menu_name'], 'string', 'max' => 10],
            [['icon', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'menu_name' => 'Menu Name',
            'icon' => 'Icon',
            'url' => 'Url',
            'sort' => 'Sort',
        ];
    }
}
