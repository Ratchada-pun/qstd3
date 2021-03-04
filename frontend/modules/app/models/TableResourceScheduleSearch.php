<?php

namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TableResourceSchedule;

/**
 * TableResourceScheduleSearch represents the model behind the search form about `frontend\modules\app\models\TableResourceSchedule`.
 */
class TableResourceScheduleSearch extends TableResourceSchedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['Date', 'STime', 'ETime', 'DRCode', 'DRName', 'Dayyy', 'Loccode', 'UpdateDate', 'UpdateTime', 'ResourceText'], 'safe'],
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
        $query = TableResourceSchedule::find();

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
            'ID' => $this->ID,
        ]);

        $query->andFilterWhere(['like', 'Date', $this->Date])
            ->andFilterWhere(['like', 'STime', $this->STime])
            ->andFilterWhere(['like', 'ETime', $this->ETime])
            ->andFilterWhere(['like', 'DRCode', $this->DRCode])
            ->andFilterWhere(['like', 'DRName', $this->DRName])
            ->andFilterWhere(['like', 'Dayyy', $this->Dayyy])
            ->andFilterWhere(['like', 'Loccode', $this->Loccode])
            ->andFilterWhere(['like', 'UpdateDate', $this->UpdateDate])
            ->andFilterWhere(['like', 'UpdateTime', $this->UpdateTime])
            ->andFilterWhere(['like', 'ResourceText', $this->ResourceText]);

        return $dataProvider;
    }
}
