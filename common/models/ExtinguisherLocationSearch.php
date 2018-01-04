<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExtinguisherLocation;
use yii\data\Pagination;
use common\helpers\Helper;

/**
 * ExtinguisherLocationSearch represents the model behind the search form about `common\models\ExtinguisherLocation`.
 */
class ExtinguisherLocationSearch extends ExtinguisherLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'floor', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['building', 'location'], 'safe'],
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
     * 关联user表
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        /**
         * 第一个参数为要关联的字表模型类名称，
         *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
         */
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
        $query = ExtinguisherLocation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'floor' => $this->floor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }

    /**
     * 添加位置信息
     * @param $params
     * @return array
     */
    public function addLocation($params)
    {
        $model = new ExtinguisherLocation();
        $model->building = $params['building'];
        $model->floor = $params['floor'];
        $model->location = $params['location'];
        $model->location_detail = $params['location_detail'];
        $model->user_id = Yii::$app->getUser()->id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->status = 1;
        if ($model->save()) {
            $return_arr = array(
                'status' => 1,
                'msg' => '保存成功!',
            );
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return $return_arr;
    }

    /**
     * 修改位置信息
     * @param $params
     * @return array
     */
    public function updateLocation($params)
    {
        if (($model = ExtinguisherLocation::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->updated_at = time();

                if ($model->save()) {
                    $return_arr = array(
                        'status' => 1,
                        'msg' => '修改成功!',
                    );
                }
            }
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '修改失败!',
            );
        }
        return $return_arr;

    }

    /**
     * 删除位置(软删除)
     * @param $params
     * @return array
     */
    public function deleteLocation($params)
    {
        if (($model = ExtinguisherLocation::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $used_numbers = ExtinguisherSearch::find()->andWhere(['location_number' => $params['id'], 'is_display' => 1])->count('id');
                if ($used_numbers > 0) {
                    $return_arr = array(
                        'status' => 0,
                        'msg' => '删除失败!请删除该位置下对应的灭火器再操作!',
                    );
                    return $return_arr;
                }
                $model->user_id = Yii::$app->getUser()->id;
                $model->updated_at = time();
                $model->status = 9;
                if ($model->save()) {
                    $return_arr = array(
                        'status' => 1,
                        'msg' => '删除成功!',
                    );
                }
            }
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '删除失败!',
            );
        }
        return $return_arr;
    }

    /**
     * 获取位置列表
     * @param $params
     * @return mixed
     */
    public static function locationList($params)
    {
        $query = self::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);
        if (isset($params['id'])) {
            $query = $query->andFilterWhere(['like', 'location.id', $params['id']]);
        }
        $querys = $query
            ->select([
                'location.id',
                'location.building',
                'location.floor',
                'location.location',
                'location.location_detail',
                'location.created_at',
                'location.updated_at',
                'location.user_id',
                'location.status',
                'user.username',
            ])
            ->from('{{%extinguisher_location}} as location')
            ->andFilterWhere(['location.status' => 1])
            ->leftJoin('{{%user}} as user', 'location.user_id = user.id')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();
        $querys = Helper::UnixTimeConversion($querys);
        $model['total'] = $query->count();
        $model['rows'] = $querys;

        return $model;
    }
}
