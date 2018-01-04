<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Extinguisher;
use yii\data\Pagination;
use common\helpers\Helper;

/**
 * ExtinguisherSearch represents the model behind the search form about `common\models\Extinguisher`.
 */
class ExtinguisherSearch extends Extinguisher
{
    public static $status_text = [
        1 => '在用',
        2 => '已替换',
        3 => '缺失',
        6 => '损坏',
        7 => '遗失',
        8 => '过期',
        9 => '未检验',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'eid', 'manufacture_date', 'effective_date', 'last_checkout_date', 'next_checkout_date', 'location_number', 'created_at', 'updated_at', 'user_id', 'status', 'is_display'], 'integer'],
            [['brand', 'model', 'status_desc'], 'safe'],
        ];
    }


    /**
     * 关联灭火器位置表
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        /**
         * 第一个参数为要关联的字表模型类名称，
         *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
         */
        return $this->hasOne(ExtinguisherLocation::className(), ['id' => 'location_number']);
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
        $query = Extinguisher::find();

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
            'eid' => $this->eid,
            'manufacture_date' => $this->manufacture_date,
            'effective_date' => $this->effective_date,
            'last_checkout_date' => $this->last_checkout_date,
            'next_checkout_date' => $this->next_checkout_date,
            'location_number' => $this->location_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'is_display' => $this->is_display,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'status_desc', $this->status_desc]);
        return $dataProvider;
    }

    /**
     * 添加新灭火器
     * @param $params
     * @return array
     */
    public function addExt($params)
    {
        $model = new Extinguisher();
        $model->eid = time();
        $model->brand = isset($params['brand']) ? $params['brand'] : '暂无';
        $model->model = isset($params['model']) ? $params['model'] : '暂无';
        $model->manufacture_date = isset($params['manufacture_date']) ? strtotime($params['manufacture_date']) : 1;
        $model->effective_date = isset($params['effective_date']) ? strtotime($params['effective_date']) : 1;
        $model->last_checkout_date = isset($params['last_checkout_date']) ? strtotime($params['last_checkout_date']) : 1;
        $model->next_checkout_date = isset($params['next_checkout_date']) ? strtotime($params['next_checkout_date']) : 1;
        $model->status = isset($params['status']) ? $params['status'] : 1;
        $model->status_text = isset($params['status']) ? self::$status_text[$params['status']] : '暂无';
        $model->status_desc = isset($params['status_desc']) ? $params['status_desc'] : '暂无';
        $model->location_number = isset($params['location_number']) ? $params['location_number'] : 1;

        $model->user_id = Yii::$app->getUser()->id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->is_display = 1;

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
     * 修改灭火器信息
     * @param $params
     * @return array
     */
    public function updateExt($params)
    {
        if (($model = Extinguisher::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->status_text = isset($params['status']) ? self::$status_text[$params['status']] : '暂无';
                $model->manufacture_date = isset($params['manufacture_date']) ? strtotime($params['manufacture_date']) : 1;
                $model->effective_date = isset($params['effective_date']) ? strtotime($params['effective_date']) : 1;
                $model->last_checkout_date = isset($params['last_checkout_date']) ? strtotime($params['last_checkout_date']) : 1;
                $model->next_checkout_date = isset($params['next_checkout_date']) ? strtotime($params['next_checkout_date']) : 1;
                $model->updated_at = time();
                if ($model->save()) {
                    $return_arr = array(
                        'status' => 1,
                        'msg' => '修改成功!',
                    );
                } else {
                    print_r($model->errors);
                    $return_arr = array(
                        'status' => 0,
                        'msg' => '修改失败!',
                    );
                }
            }
        }
        return $return_arr;
    }

    /**
     * 删除灭火器(软删除)
     * @param $params
     * @return array
     */
    public function deleteExt($params)
    {
        if (($model = Extinguisher::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->updated_at = time();
                $model->is_display = 9;

                if ($model->save()) {
                    $return_arr = array(
                        'status' => 1,
                        'msg' => '删除成功!',
                    );
                } else {
                    $return_arr = array(
                        'status' => 0,
                        'msg' => '删除失败!',
                    );
                }
            }
        }
        return $return_arr;

    }

    /**
     * 关联user表和extinguisher_location表查询数据
     */
    public function extList($params)
    {
        $query = self::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);

        if (isset($params['eid'])) {
            $query = $query->andFilterWhere(['like', 'extin.eid', $params['eid']]);
        }
        if (isset($params['search_status'])) {
            $query = $query->andFilterWhere(['extin.status' => $params['search_status']]);
        }

        $querys = $query
            ->select([
                'extin.id',
                'extin.eid',
                'extin.brand',
                'extin.model',
                'extin.manufacture_date',
                'extin.effective_date',
                'extin.last_checkout_date',
                'extin.next_checkout_date',
                'extin.status',
                'extin.status_text',
                'extin.status_desc',
                'extin.location_number',
                'extin.created_at',
                'extin.updated_at',
                'extin.user_id',
                'extin.is_display',
                'location.id as l_id',
                'location.location',
                'user.username',
            ])
            ->from('{{%extinguisher}} as extin')
            ->andFilterWhere(['extin.is_display' => 1])
            ->leftJoin('{{%extinguisher_location}} as location', 'extin.location_number=location.id')
            ->leftJoin('{{%user}} as user', 'extin.user_id=user.id')
            //->joinWith('location')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        $querys = Helper::UnixTimeConversion($querys);
        $model['total'] = $query->count();
        $model['rows'] = $querys;

        //为导出Excel在session中准备数据;
        Yii::$app->getSession()->setFlash('ExtinDataTotalExport' . Yii::$app->getUser()->id, $model['rows']);

        return $model;

    }


}
