<?php 
namespace common\models;
use Yii;
use yii\db\ActiveQuery;

class Addfindcondition extends ActiveQuery
{
    // conditions appended by default (can be skipped)
    public function init()
    {

        $this->andOnCondition([$this->modelClass::tableName() . '.branch_id' => Yii::$app->user->identity->branch_id ]);
        parent::init();
    }


    // ... add customized query methods here ...

  
}