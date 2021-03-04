<?php

namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TbQuequ;

/**
 * TbQuequSearch represents the model behind the search form about `frontend\modules\app\models\TbQuequ`.
 */
class TbQuequSearch extends TbQuequ
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'q_ids',
                    'q_arrive_time',
                    'q_appoint_time',
                    'pt_id',
                    'pt_visit_type_id',
                    'pt_appoint_sec_id',
                    'serviceid',
                    'servicegroupid',
                    'quickly',
                    'q_status_id',
                    'doctor_id',
                    'counterserviceid',
                ],
                'integer',
            ],
            [
                [
                    'q_num',
                    'q_timestp',
                    'q_vn',
                    'q_hn',
                    'pt_name',
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
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
        $query = TbQuequ::find()->orderBy([
            'serviceid' => SORT_ASC,
            'q_appoint_time' => SORT_ASC,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'q_ids' => $this->q_ids,
            'q_timestp' => $this->q_timestp,
            'q_arrive_time' => $this->q_arrive_time,
            'q_appoint_time' => $this->q_appoint_time,
            'pt_id' => $this->pt_id,
            'pt_visit_type_id' => $this->pt_visit_type_id,
            'pt_appoint_sec_id' => $this->pt_appoint_sec_id,
            'serviceid' => $this->serviceid,
            'servicegroupid' => $this->servicegroupid,
            'quickly' => $this->quickly,
            'q_status_id' => $this->q_status_id,
            'doctor_id' => $this->doctor_id,
            'counterserviceid' => $this->counterserviceid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'q_num', $this->q_num])
            ->andFilterWhere(['like', 'q_vn', $this->q_vn])
            ->andFilterWhere(['like', 'q_hn', $this->q_hn])
            ->andFilterWhere(['like', 'pt_name', $this->pt_name]);

        return $dataProvider;
    }
}
