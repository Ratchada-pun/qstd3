<?php

namespace frontend\modules\kiosk\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\kiosk\models\TbPtVisitType;

/**
 * TbPtVisitTypeSearch represents the model behind the search form of `frontend\modules\kiosk\models\TbPtVisitType`.
 */
class TbPtVisitTypeSearch extends TbPtVisitType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pt_visit_type_id'], 'integer'],
            [['pt_visit_type'], 'safe'],
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
        $query = TbPtVisitType::find();

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
            'pt_visit_type_id' => $this->pt_visit_type_id,
        ]);

        $query->andFilterWhere(['like', 'pt_visit_type', $this->pt_visit_type]);

        return $dataProvider;
    }
}
