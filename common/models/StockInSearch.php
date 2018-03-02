<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StockIn;

/**
 * StockInSearch represents the model behind the search form of `common\models\StockIn`.
 */
class StockInSearch extends StockIn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'initial_quantity', 'remaining_quantity', 'product_id', 'user_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['price'], 'number'],
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
        $query = StockIn::find();

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
            'id' => $this->id,
            'timestamp' => $this->timestamp,
            'initial_quantity' => $this->initial_quantity,
            'remaining_quantity' => $this->remaining_quantity,
            'price' => $this->price,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}
