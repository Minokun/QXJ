<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FileManage;
use yii\helpers\ArrayHelper;

/**
 * FileManageSearch represents the model behind the search form about `app\models\FileManage`.
 */
class FileManageSearch extends FileManage
{
    /**
     * 将栏目组合成key-value形式
     */
    public static function get_type()
    {
        $cat = FileCategory::find()->all();
        $cat = ArrayHelper::map($cat, 'id', 'name');
        return $cat;
    }

    /**
     * 通过id获得名称
     * @param unknown $id
     * @return Ambigous <unknown>
     */

    public static function get_type_text($id)
    {
        $datas = FileCategory::find()->all();
        $datas = ArrayHelper::map($datas, 'id', 'name');
        return $datas[$id];

    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'file_category', 'created_time'], 'integer'],
            [['file_path', 'html_path', 'file_name', 'ext_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FileManage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination->defaultPageSize = 10;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'file_category' => $this->file_category,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'html_path', $this->html_path])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'ext_name', $this->ext_name]);

        return $dataProvider;
    }
}
