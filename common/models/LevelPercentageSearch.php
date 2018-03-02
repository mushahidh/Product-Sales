<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LevelPercentage;

/**
 * LevelPercentageSearch represents the model behind the search form of `common\models\LevelPercentage`.
 */
class LevelPercentageSearch extends LevelPercentage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'level_id', 'parent_id'], 'integer'],
            [['is_company_wide'], 'boolean'],
            [['percentage'], 'safe'],
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
        $query = LevelPercentage::find();

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
            'level_id' => $this->level_id,
            'parent_id' => $this->parent_id,
            'is_company_wide' => $this->is_company_wide,
        ]);

        $query->andFilterWhere(['like', 'percentage', $this->percentage]);

        return $dataProvider;
    }
}
