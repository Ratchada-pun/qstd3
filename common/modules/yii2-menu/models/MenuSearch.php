<?php

namespace homer\menu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use homer\menu\models\Menu;

/**
 * MenuSearch represents the model behind the search form about `homer\menu\models\Menu`.
 */
class MenuSearch extends Menu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'menu_category_id', 'parent_id', 'sort', 'created_at', 'created_by'], 'integer'],
            [['title', 'router', 'parameter', 'icon', 'status', 'item_name', 'target', 'protocol', 'home', 'language', 'assoc', 'items'], 'safe'],
        ];
    }
    public $items;
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
        $query = Menu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            
            return $dataProvider;
        }

        if(isset($this->items))
            $query->joinWith('menuAuths');
        $query->andFilterWhere([
            'id' => $this->id,
            'menu_category_id' => $this->menu_category_id,
            'parent_id' => $this->parent_id,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'menu_auth.item_name' => $this->items,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'router', $this->router])
            ->andFilterWhere(['like', 'parameter', $this->parameter])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'protocol', $this->protocol])
            ->andFilterWhere(['like', 'home', $this->home])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'assoc', $this->assoc]);
            
        return $dataProvider;
    }
}
