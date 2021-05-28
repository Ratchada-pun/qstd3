<?php

namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TbDrugDispensing;

/**
 * TbDrugDispensingSearch represents the model behind the search form about `frontend\modules\app\models\TbDrugDispensing`.
 */
class TbDrugDispensingSearch extends TbDrugDispensing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dispensing_id', 'pharmacy_drug_id', 'rx_operator_id', 'dispensing_status_id', 'dispensing_by', 'created_by', 'updated_by'], 'integer'],
            [['pharmacy_drug_name', 'deptname', 'HN', 'pt_name', 'doctor_name', 'dispensing_date', 'created_at', 'updated_at', 'note'], 'safe'],
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
        $query = TbDrugDispensing::find()->orderBy('dispensing_id DESC');

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
            'dispensing_id' => $this->dispensing_id,
            'pharmacy_drug_id' => $this->pharmacy_drug_id,
            'rx_operator_id' => $this->rx_operator_id,
            'dispensing_date' => $this->dispensing_date,
            'dispensing_status_id' => $this->dispensing_status_id,
            'dispensing_by' => $this->dispensing_by,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'pharmacy_drug_name', $this->pharmacy_drug_name])
            ->andFilterWhere(['like', 'deptname', $this->deptname])
            ->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'pt_name', $this->pt_name])
            ->andFilterWhere(['like', 'doctor_name', $this->doctor_name])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
