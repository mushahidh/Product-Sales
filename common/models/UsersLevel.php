<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users_level".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $max_user
 *
 * @property UserProductLevel[] $userProductLevels
 */
class UsersLevel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users_level';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent_id', 'max_user'], 'integer'],
            [['name','display_name'], 'string', 'max' => 450],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'User Type'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'max_user' => Yii::t('app', 'Max User'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProductLevels() {
        return $this->hasMany(UserProductLevel::className(), ['user_level_id' => 'id']);
    }
    public static function getVipChild() {
        $vip_level_id =  array_search('VIP Team', \common\models\Lookup::$user_levels);
            $data = UsersLevel::find()->where(['=','parent_id',$vip_level_id])->all();
       $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($data, 'id', 'dsplay_name'); //id = your ID model, name = your caption
        return $value;
    }
    public static function getAllLevels($show_parent=false) {
        $user_id = Yii::$app->user->getId();
        $user_level_id = Yii::$app->user->identity->user_level_id;
        $parent_level_id = UsersLevel::find()->where(['id'=>$user_level_id])->one()['parent_id'];
        $data=null;
        $Role =   Yii::$app->authManager->getRolesByUser($user_id);
        if(isset($Role['super_admin']))
        {
           // $data = UsersLevel::find()->where(['!=','max_user','-1'])->all();
            $data = UsersLevel::find()->all();
            
        }
        else
        {
            if($show_parent)
            {
                $data = UsersLevel::find()->where(['or',['parent_id'=>$user_level_id],['id'=>$user_level_id],['id'=>$parent_level_id]])->all();
            }
            else
            {
                $data = UsersLevel::find()->where(['or',['parent_id'=>$user_level_id],['id'=>$user_level_id]])->all();
            }
           // $data = UsersLevel::find()->where(['!=','max_user','-1'])->andWhere(['or',['parent_id'=>$user_level_id],['id'=>$user_level_id]])->all();
        }
        
        $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($data, 'id', 'display_name'); //id = your ID model, name = your caption
        return $value;
    }

    public static function getLevels($q,$parent_id=null,$max_user=null,$include_parent=false,$include_all_child = false) {
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select('id as id, display_name AS text')
                ->from('users_level')
                ->where('true');
        if (!is_null($q))
            $query->andWhere(['like', 'display_name', $q]);
        if (!is_null($max_user))
            $query->andWhere(['=', 'max_user', $max_user]);
        if (!is_null($parent_id) && $include_all_child == false)
            {
                if($include_parent)
                    $query->andWhere(['or',['parent_id'=>$parent_id],['id'=>$parent_id]]);
                else
                    $query->andWhere(['=', 'parent_id', $parent_id]);
            }
            if (!is_null($include_all_child))
            {
                  $query->andWhere(['>', 'id', $parent_id]);
            }
        $query->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
    }
    public static function getSellerLevels($q,$parent_level_id) {
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select('id as id, display_name AS text')
                ->from('users_level')
                ->where('true')
                ->andWhere(['=', 'users_level.parent_id', $parent_level_id])
                ->andWhere(['=', 'users_level.max_user', -1]);
        if (!is_null($q))
            $query->andWhere(['like', 'display_name', $q]);
        $query->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
    }

  public function parentName($id){
      $levelDetail = UsersLevel::findOne(['id'=>$id]);
      return $levelDetail['display_name'];
  }

}
