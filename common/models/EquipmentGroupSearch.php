<?php

namespace common\models;

use app\count;
use common\models\EquipmentGroup;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\helpers\Helper;

/**
 * EquipmentGroupSearch represents the model behind the search form about `common\models\EquipmentGroup`.
 */
class EquipmentGroupSearch extends EquipmentGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'user_id', 'is_display'], 'integer'],
            [['name', 'inspections', 'desc'], 'safe'],
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
        $query = EquipmentGroup::find();

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
            ->andFilterWhere(['like', 'inspections', $this->inspections])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }

    /**
     * 获取分组列表
     * @param $params
     * @return mixed
     */
    public function groupList($params)
    {
        //查出所有检查项供后面使用
        $inspections_arr = Inspection::find()
            ->select(['id', 'name'])
            ->indexBy('id')
            ->asArray()
            ->all();

        $query = self::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);

        if (isset($params['name'])) {
            $query = $query->andFilterWhere(['like', 'group.name', $params['name']]);
        }
        $querys = $query
            ->select([
                'group.id',
                'group.name',
                'group.inspections',
                'group.low_pass',
                'group.desc',
                'group.created_at',
                'group.updated_at',
                'group.user_id',
                'group.is_display',
                'user.username',
            ])
            ->from('{{%equipment_group}} as group')
            ->andFilterWhere(['group.is_display' => 1])
            ->leftJoin('{{%user}} as user', 'group.user_id=user.id')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();
        //没有找到合适的关联查询方法,因此在查询之后向数组添加元素作为页面显示的检查项(仅用作于显示)
        foreach ($querys as $k => $v) {
            $tmp_arr = explode(',', $v['inspections']);
            if ($v['inspections'] != "") {
                $tmp = '';
                foreach ($tmp_arr as $item) {
                    $tmp .= $inspections_arr[$item]['name'] . " | ";
                }
                $querys[$k]['ins_name'] = $tmp;
            }

        }


        $querys = Helper::UnixTimeConversion($querys);
        $model['total'] = $query->count();
        $model['rows'] = $querys;

        return $model;
    }

    /**
     * 添加分组
     * @param $params
     * @return array
     */
    public function addGroup($params)
    {
        $model = new EquipmentGroup();
        $model->name = isset($params['name']) ? $params['name'] : '暂无';
        $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';
        $model->low_pass = isset($params['low_pass']) ? $params['low_pass'] : 0;
        if (isset($params['inspections'])) {
            $model->inspections = count($params['inspections']) >= 1 ? implode(",", $params['inspections']) : $params['inspections'];
        } else {
            $model->inspections = '';
        }

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
            print_r($model->errors);
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return $return_arr;
    }

    /**
     * 修改分组
     * @param $params
     * @return array
     */
    public function updateGroup($params)
    {
        if (($model = EquipmentGroup::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->name = isset($params['name']) ? $params['name'] : '暂无';
                $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';
                $model->low_pass = isset($params['low_pass']) ? $params['low_pass'] : $model->low_pass;

                if (isset($params['inspections'])) {
                    $model->inspections = count($params['inspections']) > 1 ? implode(",", $params['inspections']) : $params['inspections'];
                } else {
                    $model->inspections = '';
                }

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
     * 删除分组
     * @param $params
     * @return array
     */
    public function deleteGroup($params)
    {
        if (($model = self::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                //判断是否有设备选择了该分组
                $used_numbers = EquipmentSearch::find()->select(['eq_group'])->andFilterWhere(['eq_group' => $params['id'], 'is_display' => 1])->count();
                if ($used_numbers > 0) {
                    $return_arr = array(
                        'status' => 0,
                        'msg' => '删除失败!请在检查设备中删除该分组再操作!',
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

    /**
     * 修改设备分组时计算--原分组--的设备数量和合格率等
     * @param $params
     * @return bool
     */
    public static function oldCount($params)
    {
        if (($model = self::findOne($params['old_eq_group'])) !== null) {
            //计算合格设备的数量和合格率
            //①.查询该组所有未删除设备的数量
            $old_total_number = EquipmentSearch::find()
                ->andFilterWhere([
                    'eq_group' => $params['old_eq_group'],
                    'is_display' => 1,
                ])
                ->count();
            //②.查询该组所有未删除的且合格的设备的数量
            $old_pass_number = EquipmentSearch::find()
                ->andFilterWhere([
                    'eq_group' => $params['old_eq_group'],
                    'final_status' => 1,
                    'is_display' => 1,
                ])
                ->count();
            $model->total_number = $old_total_number;
            $model->pass_number = $old_pass_number;
            $model->per_pass = round(($old_pass_number / $old_total_number), 2) * 100;
            $model->save();
            return true;
        }
    }


    /**
     * 修改或添加设备分组时计算--新分组--的设备数量和合格率等
     * @param $params
     * @return bool
     */
    public static function newCount($params)
    {
        if (($model = self::findOne($params['new_eq_group'])) !== null) {
            //计算合格设备的数量和合格率
            //①.查询该组所有未删除设备的数量
            $new_total_number = EquipmentSearch::find()
                ->andFilterWhere([
                    'eq_group' => $params['new_eq_group'],
                    'is_display' => 1,
                ])
                ->count();
            //②.查询该组所有未删除的且合格的设备的数量
            $new_pass_number = EquipmentSearch::find()
                ->andFilterWhere([
                    'eq_group' => $params['new_eq_group'],
                    'final_status' => 1,
                    'is_display' => 1,
                ])
                ->count();
            $model->total_number = $new_total_number;
            $model->pass_number = $new_pass_number;
            $model->per_pass = round(($new_pass_number / $new_total_number), 2) * 100;
            $model->save();
            return true;
        }
    }
}