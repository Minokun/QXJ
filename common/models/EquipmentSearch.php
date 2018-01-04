<?php

namespace common\models;

use app\count;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Equipment;
use yii\data\Pagination;
use common\helpers\Helper;

/**
 * EquipmentSearch represents the model behind the search form about `common\models\Equipment`.
 */
class EquipmentSearch extends Equipment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'eq_id', 'created_at', 'updated_at', 'user_id', 'is_display', 'final_status'], 'integer'],
            [['name', 'desc', 'reason'], 'safe'],
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
        $query = Equipment::find();

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
            'eq_id' => $this->eq_id,
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
     * 获取检查设备列表
     * @param $params
     * @return mixed
     */
    public function equList($params)
    {
        $query = self::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);

        if (isset($params['eq_name'])) {
            $query = $query->andFilterWhere(['like', 'eq.name', $params['eq_name']]);
        }
        $querys = $query
            ->select([
                'eq.id',
                'eq.eq_id',
                'eq.eq_group',
                'group.name as group_name',
                'group.inspections as group_ins',
                'eq.name',
                'eq.desc',
                'eq.pass_ins',
                'eq.no_pass',
                'eq.reason',
                'eq.per_pass',
                'eq.final_status',
                'eq.created_at',
                'eq.updated_at',
                'eq.user_id',
                'eq.is_display',
                'user.username',
            ])
            ->from('{{%equipment}} as eq')
            ->andFilterWhere(['eq.is_display' => 1])
            ->leftJoin('{{%user}} as user', 'eq.user_id=user.id')
            ->leftJoin('{{%equipment_group}} as group', 'eq.eq_group=group.id')
            //->joinWith('location')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        foreach ($querys as $k => $v) {
            $querys[$k]['final_status'] = ($v['final_status'] == 1) ? '是' : '否';
            $querys[$k]['per_pass'] .= '%';
        }

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
    public function addEqu($params)
    {
        $model = new Equipment();
        $model->eq_id = time();
        $model->eq_group = isset($params['eq_group']) ? $params['eq_group'] : 0;
        $model->name = isset($params['name']) ? $params['name'] : '暂无';
        $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';
        $model->pass_ins = '';
        $model->no_pass = '';
        $model->reason = '';
        $model->per_pass = 0;
        $model->final_status = 0;

        $model->user_id = Yii::$app->getUser()->id;
        $model->created_at = time();
        $model->updated_at = time();
        $model->is_display = 1;

        if ($model->save()) {
            //保存当前设备后再调用方法更新该组的合格数据等
            $params_arr = [
                'eq_id' => $model->id,
                'new_eq_group' => $model->eq_group,
            ];
            $new_count = EquipmentGroupSearch::newCount($params_arr);
            if ($new_count == true) {
                $return_arr = array(
                    'status' => 1,
                    'msg' => '保存成功!',
                );
            }
        } else {
            $return_arr = array(
                'status' => 0,
                'msg' => '保存失败!',
            );
        }
        return $return_arr;
    }

    /**
     * 操作检查项
     * @param $params
     * @return array
     */
    public function checkEqu($params)
    {
        if (($model = Equipment::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->no_pass = '';
                //post过来的是合格的项,需要查出该设备所在分组有哪些项然后再从中减去合格项
                $ins = isset($params['ins']) ? $params['ins'] : [];
                $model->pass_ins = implode(",", $ins);
                $group = EquipmentGroupSearch::find()->andFilterWhere(['id' => $params['group_id']])->asArray()->one();
                $group_ins = $group['inspections'];
                $ins_arr = explode(",", $group_ins);
                $all_count = count($ins_arr);//总数
                //当选中一部分或者全部选中时
                if (count($ins) > 0) {
                    foreach ($ins_arr as $k => $v) {
                        foreach ($ins as $ik => $iv) {
                            if ($iv == $v) {
                                unset($ins_arr[$k]);//数组中删除合格的
                            }
                        }
                    }
                    //此处剩下不合格项
                    $no_pass_count = count($ins_arr);//不合格数
                    $ins_names = InspectionSearch::find()->select(['id', 'name'])->andFilterWhere(['in', 'id', $ins_arr])->asArray()->all();
                    foreach ($ins_names as $i => $j) {
                        $model->no_pass .= $j['name'] . " | ";
                    }
                    $model->reason = isset($params['reason']) ? $params['reason'] : $model->reason;
                    //全部通过时
                    if ($no_pass_count == 0) {
                        $model->no_pass = "无";
                        $model->reason = "";
                    }
                    $pass = ($all_count - $no_pass_count) / $all_count;
                    $model->per_pass = (round($pass, 2)) * 100;//该设备合格率
                    $low_pass = $group['low_pass'];//分组最低合格率
                    $model->final_status = (($pass * 100) >= $low_pass) ? 1 : -1;//最终合格与否
                } else {//全部没选中(都不通过)
                    $ins_names = InspectionSearch::find()->select(['id', 'name'])->andFilterWhere(['in', 'id', $ins_arr])->asArray()->all();
                    foreach ($ins_names as $i => $j) {
                        $model->no_pass .= $j['name'] . " | ";
                    }
                    $model->per_pass = 0;//该设备合格率
                    $model->reason = "";
                    $model->final_status = -1;//最终合格与否
                }
                $model->updated_at = time();
                if ($model->save()) {
                    //保存当前设备后再调用方法更新该组的合格数据等
                    $params_arr = [
                        'eq_id' => $model->id,
                        'new_eq_group' => $model->eq_group,
                    ];
                    $new_count = EquipmentGroupSearch::newCount($params_arr);
                    if ($new_count == true) {
                        $return_arr = array(
                            'status' => 1,
                            'msg' => '修改成功!',
                        );
                    }
                } else {
                    //print_r($model->errors);
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
     * 修改检查设备
     * @param $params
     * @return array
     */
    public function updateEqu($params)
    {
        if (($model = Equipment::findOne($params['id'])) !== null) {
//            if ($model->load($params, '')) {
            $model->user_id = Yii::$app->getUser()->id;
            /*
            $old_eq_group = $model->eq_group;
            $model->eq_group = isset($params['eq_group']) ? $params['eq_group'] : $model->eq_group;
            $new_eq_group = $model->eq_group;
            //当修改了分组,需要将未通过项和原因清空,状态和通过率改为 0
            if ($new_eq_group != $old_eq_group) {
                $model->no_pass = "";
                $model->pass_ins = '';
                $model->final_status = 0;
                $model->reason = "";
                $model->per_pass = 0;
            }
            */
            $model->name = isset($params['name']) ? $params['name'] : '暂无';
            $model->desc = isset($params['desc']) ? $params['desc'] : '暂无';
            $model->updated_at = time();
            if ($model->save()) {
                /*
                //如果修改了分组,需要调用分组模型的修改设备原来分组的合格方面的数据
                if ($new_eq_group != $old_eq_group) {
                    $params_arr = [
                        'eq_id' => $params['id'],
                        'old_eq_group' => $old_eq_group,
                        'new_eq_group' => $new_eq_group,
                    ];
                    EquipmentGroupSearch::oldCount($params_arr);
                }


                //保存当前设备后再调用方法更新该组的合格数据等
                $params_arr = [
                    'eq_id' => $params['id'],
                    'new_eq_group' => $new_eq_group,
                ];
                $new_count = EquipmentGroupSearch::newCount($params_arr);
                */
//                if ($new_count == true) {
                $return_arr = array(
                    'status' => 1,
                    'msg' => '修改成功!',
                );
//                }
            } else {
                print_r($model->errors);
                $return_arr = array(
                    'status' => 0,
                    'msg' => '修改失败!',
                );
            }
        }
        return $return_arr;
    }

    /**
     * 删除检查设备
     * @param $params
     * @return array
     */
    public function deleteEqu($params)
    {
        if (($model = Equipment::findOne($params['id'])) !== null) {
            if ($model->load($params, '')) {
                $model->user_id = Yii::$app->getUser()->id;
                $model->updated_at = time();
                $model->is_display = 9;

                if ($model->save()) {
                    //保存当前设备后再调用方法更新该组的合格数据等
                    $params_arr = [
                        'eq_id' => $model->id,
                        'new_eq_group' => $model->eq_group,
                    ];
                    $new_count = EquipmentGroupSearch::newCount($params_arr);
                    if ($new_count == true) {
                        $return_arr = array(
                            'status' => 1,
                            'msg' => '删除成功!',
                        );
                    }
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
