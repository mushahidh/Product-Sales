<?php
namespace frontend\models;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use Yii;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $profile;
    public $logo;
    public $country;
    public $state;
    public $city;
    public $zip;
    public $name;
    public $id;
    public $sr;
    public $company_id;
    public $branch_id;
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            [['first_name','last_name','country','state','city','zip','name','id','sr','company_id','branch_id'], 'safe'],
            ['email', 'trim'],
            [['profile','logo'], 'file'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'signup') {
                $companyDetail = \common\models\Company::createCompany($this);
                $branchDetail = \common\models\Branch::createBranch($this,$companyDetail);
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\User::tableName(), $companyDetail->id);
                $this->company_id = $companyDetail->id;
                $this->branch_id = $branchDetail->id;
            }
            return true;
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($model)
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->id = $this->id;
        $user->sr = $this->sr;
        $user->company_id = $this->company_id;
        $user->branch_id = $this->branch_id;
          //upload image
        $photo = UploadedFile::getInstance($model, 'profile');
        if ($photo !== null) {
            $user->profile= $photo->name;
            $array = explode(".", $photo->name);
            $ext=end($array);
            $user->profile = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path =  Yii::getAlias('@app').'/web/uploads/'.$user->profile;
         //   $path = Yii::getAlias('@upload') .'/'. $model->payment_slip;
            $photo->saveAs($path);
          }
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->user_level_id = $this->sr;
        $user->setPassword($this->password);
        $user->generateAuthKey();
      
        return $user->save() ? $user : null;
    }
    
}
