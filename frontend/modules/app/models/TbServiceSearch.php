<?php

namespace frontend\modules\app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TbService;

/**
 * TbServiceSearch represents the model behind the search form of `frontend\modules\app\models\TbService`.
 */
class TbServiceSearch extends TbService
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serviceid', 'service_groupid', 'prn_profileid', 'prn_profileid_quickly', 'prn_copyqty', 'service_numdigit', 'service_md_name_id', 'print_by_hn', 'quickly', 'show_on_kiosk', 'show_on_mobile', 'service_type_id'], 'integer'],
            [['service_name', 'service_route', 'service_prefix', 'service_status', 'btn_kiosk_name', 'main_dep', 'service_pic'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TbService::find();

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
            'serviceid' => $this->serviceid,
            'service_groupid' => $this->service_groupid,
            'prn_profileid' => $this->prn_profileid,
            'prn_profileid_quickly' => $this->prn_profileid_quickly,
            'prn_copyqty' => $this->prn_copyqty,
            'service_numdigit' => $this->service_numdigit,
            'service_md_name_id' => $this->service_md_name_id,
            'print_by_hn' => $this->print_by_hn,
            'quickly' => $this->quickly,
            'show_on_kiosk' => $this->show_on_kiosk,
            'show_on_mobile' => $this->show_on_mobile,
            'service_type_id' => $this->service_type_id,
        ]);

        $query->andFilterWhere(['like', 'service_name', $this->service_name])
            ->andFilterWhere(['like', 'service_route', $this->service_route])
            ->andFilterWhere(['like', 'service_prefix', $this->service_prefix])
            ->andFilterWhere(['like', 'service_status', $this->service_status])
            ->andFilterWhere(['like', 'btn_kiosk_name', $this->btn_kiosk_name])
            ->andFilterWhere(['like', 'main_dep', $this->main_dep])
            ->andFilterWhere(['like', 'service_pic', $this->service_pic]);

        return $dataProvider;
    }
}
