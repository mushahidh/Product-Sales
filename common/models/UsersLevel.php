<?php

namespace common\models;
use yii\helpers\ArrayHelper;
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
class UsersLevel extends \common\components\ActiveRecord {

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
            [['id','sr','company_id', 'branch_id','parent_id'], 'required'],
            [[ 'max_user'], 'integer'],
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
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'import'  ) {
                $companyId = Yii::$app->user->identity->company_id;
                $branchId = Yii::$app->user->identity->branch_id;
                $this->id = \common\components\Constants::GUID();
              //   $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\Postcode::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
            }
            return true;
        }
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
    // show leve for admin to create level only
    public static function getAllLevelsRole() {
        $data = UsersLevel::find()->select('parent_id')->all();
     
        $data =   ArrayHelper::map($data, 'parent_id', 'parent_id');
        $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map(UsersLevel::find()->andWhere(['not in', 'sr', $data])->all(), 'id', 'display_name'); //id = your ID model, name = your caption
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
        if(!empty($parent_id))
        {
            $patentDetail =  \common\models\UsersLevel::findOne(['id'=>$parent_id]);
            $parent_id = $patentDetail->sr;
        }
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \common\components\Query();
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
        $query = new \common\components\Query();
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
public static function createSuperAdminLevel($user){
$usersLevel = new UsersLevel();
$usersLevel->id = \common\components\Constants::GUID();
$usersLevel->company_id = $user->company_id;
$usersLevel->branch_id = $user->branch_id;
$usersLevel->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\UsersLevel::tableName(), $usersLevel->company_id);

$usersLevel->name = \common\models\Lookup::$user_levels['1'];
$usersLevel->display_name = \common\models\Lookup::$user_levels['1'];
$usersLevel->parent_id = '0';
$usersLevel->max_user = '1';
return $usersLevel->save() ? $usersLevel : null;



}
}
