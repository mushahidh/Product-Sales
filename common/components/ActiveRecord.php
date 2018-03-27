<?php
namespace common\components;
use Yii;

use yii\db\ActiveRecord as BaseActiveRecord;

class ActiveRecord extends BaseActiveRecord{

    public static function find() {
        $query = parent::find();
      if(!Yii::$app->user->isGuest){
        $query->onCondition(['=',static::tableName().'.company_id',Yii::$app->user->identity->company_id]);
        $query->andOnCondition(['=',static::tableName().'.branch_id',Yii::$app->user->identity->branch_id]);
      }
     return $query;
    }
}
?>