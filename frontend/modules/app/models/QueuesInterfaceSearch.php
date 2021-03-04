<?php

namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\app\models\QueuesInterface;

/**
 * QueuesInterfaceSearch represents the model behind the search form about `frontend\modules\app\models\QueuesInterface`.
 */
class QueuesInterfaceSearch extends QueuesInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['HN', 'VN', 'Fullname', 'doctor', 'lab', 'xray', 'SP', 'PrintTime', 'ArrivedTime', 'PrintBillTime', 'Time1', 'Time2', 'UpdateDate', 'UpdateTime', 'ArrivedTimeC', 'WTime', 'AppTime'], 'safe'],
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
    public function searchlab($params)
    {
        $query = QueuesInterface::find();

        $query->andFilterWhere([
            'ID' => $this->ID,
        ]);

        $query->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'VN', $this->VN])
            ->andFilterWhere(['like', 'Fullname', $this->Fullname])
            ->andFilterWhere(['like', 'doctor', $this->doctor])
            ->andFilterWhere(['like', 'lab', $this->lab])
            ->andFilterWhere(['like', 'xray', $this->xray])
            ->andFilterWhere(['like', 'SP', $this->SP])
            ->andFilterWhere(['like', 'PrintTime', $this->PrintTime])
            ->andFilterWhere(['like', 'ArrivedTime', $this->ArrivedTime])
            ->andFilterWhere(['like', 'PrintBillTime', $this->PrintBillTime])
            ->andFilterWhere(['like', 'Time1', $this->Time1])
            ->andFilterWhere(['like', 'Time2', $this->Time2])
            ->andFilterWhere(['like', 'UpdateDate', $this->UpdateDate])
            ->andFilterWhere(['like', 'UpdateTime', $this->UpdateTime])
            ->andFilterWhere(['like', 'ArrivedTimeC', $this->ArrivedTimeC])
            ->andFilterWhere(['like', 'WTime', $this->WTime])
            ->andFilterWhere(['like', 'AppTime', $this->AppTime]);

        return $query;
    }

    public function search($params)
    {
        $query = QueuesInterface::find();

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

        $query->andFilterWhere(['like', 'HN', $this->HN])
            ->andFilterWhere(['like', 'VN', $this->VN])
            ->andFilterWhere(['like', 'Fullname', $this->Fullname])
            ->andFilterWhere(['like', 'doctor', $this->doctor])
            ->andFilterWhere(['like', 'lab', $this->lab])
            ->andFilterWhere(['like', 'xray', $this->xray])
            ->andFilterWhere(['like', 'SP', $this->SP])
            ->andFilterWhere(['like', 'PrintTime', $this->PrintTime])
            ->andFilterWhere(['like', 'ArrivedTime', $this->ArrivedTime])
            ->andFilterWhere(['like', 'PrintBillTime', $this->PrintBillTime])
            ->andFilterWhere(['like', 'Time1', $this->Time1])
            ->andFilterWhere(['like', 'Time2', $this->Time2])
            ->andFilterWhere(['like', 'UpdateDate', $this->UpdateDate])
            ->andFilterWhere(['like', 'UpdateTime', $this->UpdateTime])
            ->andFilterWhere(['like', 'ArrivedTimeC', $this->ArrivedTimeC])
            ->andFilterWhere(['like', 'WTime', $this->WTime])
            ->andFilterWhere(['like', 'AppTime', $this->AppTime]);

        return $dataProvider;
    }
}
