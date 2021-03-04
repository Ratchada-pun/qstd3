<?php

namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\Register;

/**
 * RegisterSearch represents the model behind the search form about `frontend\modules\app\models\Register`.
 */
class RegisterSearch extends Register
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VN', 'HN', 'FullName', 'TEL', 'CareProvNo', 'CareProv', 'ServiceID', 'Time', 'AppTime', 'loccode', 'locdesc', 'UpdateDate', 'UpdateTime'], 'safe'],
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
        $query = Register::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'VN', $this->VN])
            ->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'FullName', $this->FullName])
            ->andFilterWhere(['like', 'TEL', $this->TEL])
            ->andFilterWhere(['like', 'CareProvNo', $this->CareProvNo])
            ->andFilterWhere(['like', 'CareProv', $this->CareProv])
            ->andFilterWhere(['like', 'ServiceID', $this->ServiceID])
            ->andFilterWhere(['like', 'Time', $this->Time])
            ->andFilterWhere(['like', 'AppTime', $this->AppTime])
            ->andFilterWhere(['like', 'loccode', $this->loccode])
            ->andFilterWhere(['like', 'locdesc', $this->locdesc])
            ->andFilterWhere(['like', 'UpdateDate', $this->UpdateDate])
            ->andFilterWhere(['like', 'UpdateTime', $this->UpdateTime]);

        return $dataProvider;
    }
}
