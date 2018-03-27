<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $accout_type
 * @property string $account_name
 * @property string $account_description
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $created_at
 * @property int $user_id
 *
 * @property User $user
 * @property Gl[] $gls
 */
class Account extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id','id','sr','company_id', 'branch_id'], 'required'],
            [['account_type', 'account_name', 'account_description'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    public function behaviors() {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_type' => Yii::t('app', 'Account Type'),
            'account_name' => Yii::t('app', 'Account Name'),
            'account_description' => Yii::t('app', 'Account Description'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created On'),
            'created_at' => Yii::t('app', 'Updated On'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' ) {
                if(Yii::$app->user->isGuest){
                    $refferalUser = \common\models\User::findOne(['id'=>Yii::$app->request->get('id')]);
                    $companyId = $refferalUser->company_id;
                    $branchId = $refferalUser->branch_id;
                }else{
                    $companyId = Yii::$app->user->identity->company_id;
                    $branchId = Yii::$app->user->identity->branch_id;
              }
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\Account::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
                
            }
            return true;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGls()
    {
        return $this->hasMany(Gl::className(), ['account_id' => 'id']);
    }
    public static function create_accounts($model){
        for($i=1;$i<=2;$i++)
        {
            $account = new Account();
            $action = Yii::$app->controller->action->id;
            if($action == 'signup'){
                $account->id = \common\components\Constants::GUID();
                $account->company_id = $model->company_id;
                $account->branch_id = $model->branch_id;
                $account->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\Account::tableName(), $account->company_id);
            }else{
                $account->beforeValidate();
            }
            $account->account_type = ''.$i;
            $account->account_name =$model->username.'-receivable';    
            if($i==2)
            {
                $account->account_name =$model->username.'-payable';    
            }
            $account->account_description = 'Account to calculate profit';
            $account->user_id = $model->id;
           $account->save();
        }
        
    }
}
