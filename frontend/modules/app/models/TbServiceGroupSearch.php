<?php

namespace frontend\modules\app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TbServiceGroup;

/**
 * TbServiceGroupSearch represents the model behind the search form of `frontend\modules\app\models\TbServiceGroup`.
 */
class TbServiceGroupSearch extends TbServiceGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['servicegroupid', 'servicegroup_type_id', 'servicegroup_order', 'subservice_status', 'servicegroup_status', 'show_on_kiosk', 'show_on_mobile', 'servicestatus_default'], 'integer'],
            [['servicegroup_code', 'servicegroup_name', 'servicegroup_prefix', 'servicegroup_clinic', 'servicegroup_pic'], 'safe'],
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
        $query = TbServiceGroup::find();

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
            'servicegroupid' => $this->servicegroupid,
            'servicegroup_type_id' => $this->servicegroup_type_id,
            'servicegroup_order' => $this->servicegroup_order,
            'subservice_status' => $this->subservice_status,
            'servicegroup_status' => $this->servicegroup_status,
            'show_on_kiosk' => $this->show_on_kiosk,
            'show_on_mobile' => $this->show_on_mobile,
            'servicestatus_default' => $this->servicestatus_default,
        ]);

        $query->andFilterWhere(['like', 'servicegroup_code', $this->servicegroup_code])
            ->andFilterWhere(['like', 'servicegroup_name', $this->servicegroup_name])
            ->andFilterWhere(['like', 'servicegroup_prefix', $this->servicegroup_prefix])
            ->andFilterWhere(['like', 'servicegroup_clinic', $this->servicegroup_clinic])
            ->andFilterWhere(['like', 'servicegroup_pic', $this->servicegroup_pic]);

        return $dataProvider;
    }
}
