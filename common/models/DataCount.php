<?php
/**
 * Created by 天涯旅人.
 * User: @Skyline Traveler <547636416@qq.com>
 * Date: 2017/3/10
 * Time: 14:37
 */

namespace common\models;


use yii\db\ActiveRecord;
use yii\data\Pagination;
use common\helpers\Helper;
use Yii;

class DataCount extends ActiveRecord
{

    /**
     * 数据汇总统计列表
     * @param $params
     * @return mixed
     */
    public function dataList($params)
    {
        $query = EquipmentGroup::find();
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $params['rows']]);

        if (isset($params['group_name'])) {
            $query = $query->andFilterWhere(['like', 'group.name', $params['group_name']]);
        }
        $querys = $query
            ->select([
                'group.id',
                'group.name',
                'group.inspections',
                'group.low_pass',
                'group.total_number',
                'group.pass_number',
                'group.per_pass',
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
            //->joinWith('location')
            ->offset($params['offset'])
            ->limit($pages->limit)
            ->asArray()
            ->all();

        foreach ($querys as $k => $v) {
            $querys[$k]['per_pass'] .= '%';
        }

        $querys = Helper::UnixTimeConversion($querys);
        $model['total'] = $query->count();
        $model['rows'] = $querys;

        //为导出Excel在session中准备数据;
        Yii::$app->getSession()->setFlash('DataCountExport' . Yii::$app->getUser()->id, $model['rows']);


        return $model;
    }

}