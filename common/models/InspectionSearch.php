<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\helpers\Helper;
use common\models\Inspection;

/**
 * InspectionSearch represents the model behind the search form about `common\models\Inspection`.
 */
class InspectionSearch extends Inspection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'user_id', 'is_display'], 'integer'],
            [['name', 'desc'], 'safe'],
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
        $query = Inspection::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'is_display' => $this->is_display,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }

    /**
     * 获取检查项列表
     * @param $params
     * @return mixed
     */
    public function insList($params)
    {
        $query = self::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);

        if (isset($params['name'])) {
            $query = $query->andFilterWhere(['like', 'ins.name', $params['name']]);
        }
        $querys = $query
            ->select([
                'ins.id',
                'ins.name',
                'ins.desc',
                'ins.created_at',
                'ins.updated_at',
                'ins.user_id',
                'ins.is_display',
                'user.username',
            ])
            ->from('{{%inspection}} as ins')
            ->andFilterWhere(['ins.is_display' => 1])
            ->leftJoin('{{%user}} as user', 'ins.user_id=user.id')
            //->joinWith('location')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        $querys = Helper::UnixTimeConversion($querys);
        $model['total'] = $query->count();
        $model['rows'] = $querys;

        return $model;
    }

    /**
     * 添加检查设备
     * @param $params
     * @return array
     */
    public function addIns($params)
    {
        $model = new Inspection();
        $model->name = isset($params['name']) ? $params['name'] : '暂无';
        $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';

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
     * 修改检查项
     * @param $params
     * @return array
     */
    public function updateIns($params)
    {
        if (($model = Inspection::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->name = isset($params['name']) ? $params['name'] : '暂无';
                $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';
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
     * 删除检查项
     * @param $params
     * @return array
     */
    public function deleteIns($params)
    {
        if (($model = Inspection::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                //判断是否有分组选择了该项
                $inspections = EquipmentGroupSearch::find()->select(['inspections'])->andFilterWhere(['is_display' => 1])->asArray()->all();
                $tmp = array_column($inspections, 'inspections');
                $ins = array_unique(explode(',', implode(',', $tmp)));

                if (in_array($params['id'], $ins)) {
                    $return_arr = array(
                        'status' => 0,
                        'msg' => '删除失败!请在分组中删除该项再操作!',
                    );
                    return $return_arr;
                }
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
}
