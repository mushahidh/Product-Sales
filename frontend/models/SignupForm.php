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
            [['first_name','last_name'], 'safe'],
            ['email', 'trim'],
            [['profile'], 'file'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
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
          //upload image
          $photo = UploadedFile::getInstance($model, 'profile');
         
          if ($photo !== null) {
            $model->profile= $photo->name;
            $ext = end((explode(".", $photo->name)));
            $model->profile = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path =  Yii::getAlias('@app').'/web/uploads/'.$model->profile;
         //   $path = Yii::getAlias('@upload') .'/'. $model->payment_slip;
            $photo->saveAs($path);
          }
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
