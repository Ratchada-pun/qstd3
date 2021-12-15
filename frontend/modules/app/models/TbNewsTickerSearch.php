<?php

namespace frontend\modules\app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\TbNewsTicker;

/**
 * TbNewsTickerSearch represents the model behind the search form of `frontend\modules\app\models\TbNewsTicker`.
 */
class TbNewsTickerSearch extends TbNewsTicker
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_ticker_id', 'news_ticker_status'], 'integer'],
            [['news_ticker_detail'], 'safe'],
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
        $query = TbNewsTicker::find();

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
            'news_ticker_id' => $this->news_ticker_id,
            'news_ticker_status' => $this->news_ticker_status,
        ]);

        $query->andFilterWhere(['like', 'news_ticker_detail', $this->news_ticker_detail]);

        return $dataProvider;
    }
}
