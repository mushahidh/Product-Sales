<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\db\Query;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'parent_id', 'user_level_id', 'city', 'country'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'link', 'phone_no', 'address'], 'safe'],
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
        $user_id = Yii::$app->user->getId();
        $Role =   Yii::$app->authManager->getRolesByUser($user_id);
        if(isset($Role['super_admin'])){ 
            $query = User::find();
        }else{
            try
            {
                $children_ids = (new Query())
                    ->select('GetFamilyTree(`id`) as children') //->select('`id`, `parent_id`,GetFamilyTree(`id`) as children ')
                    ->from('user')
                    ->where(['=', 'id', $user_id])->one()['children'];

                $children_ids = explode(',', $children_ids);
            } catch (\Exception $e) {
                $children_ids = []; 
            }
          
            $query = User::find()
               ->where(['in', 'id', $children_ids]);
        }

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent_id' => $this->parent_id,
            
            'user_level_id' => $this->user_level_id,
            'city' => $this->city,
            'country' => $this->country,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andWhere(['IS NOT','parent_id',null]);

        return $dataProvider;
    }
}
