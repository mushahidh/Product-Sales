<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * User model
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property string $link
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $parent_id
 * @property integer $user_level_id
 * @property string $phone_no
 * @property string $address
 * @property string $order_type
 * @property integer $city
 * @property integer $country
 *
 * @property Order[] $orders
 * @property StockIn[] $stockIns
 */
class User extends ActiveRecord implements IdentityInterface
{

    public $password;
    public $all_level;
    public $parent_user;
    public $stock_in;
    public $quantity;
    public $product_order_info;
    public $price;
    public $unit_price;
    public $total_price;
    public $product_id;
    public $name;
    public $order_type;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getPassword()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['username', 'password', 'email', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['username', 'email', 'first_name', 'last_name', 'user_level_id'], 'required'],
            ['password', 'required', 'on' => 'insert'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['status', 'created_at', 'updated_at', 'parent_id', 'user_level_id'], 'integer'],
            [['created_at', 'updated_at', 'phone_no', 'address', 'city', 'country', 'all_level', 'parent_user', 'stock_in', 'quantity', 'product_order_info', 'price', 'unit_price', 'total_price', 'company_user', 'product_id', 'name', 'order_type','province','district','postal_code'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['profile'], 'file'],
            [['link'], 'string', 'max' => 450],
            [['phone_no'], 'string', 'max' => 45],
            [['address'], 'string', 'max' => 5000],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getOrders()
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public function getStockIns()
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function getProfile()
    {
        $user = User::find()
            ->where(['id' => Yii::app()->user->id])
            ->one();
    }

    public static function insert_user($model)
    {
        $user = new User();
        $user->isNewRecord = true;
        $user->id = null;
        $user->password = '12345';
        $user->setPassword($user->password);
        $user->generateAuthKey();
        $user->username = 'customer';
        $user->first_name = 'customer';
        $user->last_name = 'customer';
        $user->user_level_id = '30';
        $user->parent_id = null;
        $user->parent_user = '1';
        $user->email = $model->email;
        $user->mobile_no = $model->mobile_no;
        $user->address = $model->address;
        $user->postal_code = $model->postal_code;
        $user->district = $model->district;
        $user->province = $model->province;
        $user->country = $model->country;
        $user->quantity = 0;
        if ($user->save()) {
            \common\models\Account::create_accounts($user);
            return $user;
        }
    }

    public static function getUsers($q, $parent_id = null, $user_id = null, $user_level = null, $company_user = null, $include_parent = false, $include_self = true)
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select('id as id, username AS text')
            ->from('user')
            ->where('true');
        if (!is_null($q)) {
            $query->andWhere(['like', 'username', $q]);
        }

        if (!$include_self) {
            $query->andWhere(['!=', 'id', $user_id]);
        }
        if (!is_null($parent_id)) {
            if ($include_parent) {
                $query->andWhere(['or', ['parent_id' => $parent_id], ['id' => $parent_id]]);
            } else {
                $query->andWhere(['=', 'parent_id', $parent_id]);
            }

        }
        if (!is_null($user_level)) {
            $query->andWhere(['=', 'user_level_id', $user_level]);
        }

        if (!is_null($company_user)) {
            $query->andWhere(['=', 'company_user', $company_user]);
        }

        $query->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
    }

    public static function profile_save($photo, $model)
    {
        $model->profile = $photo->name;
        $array = explode(".", $photo->name);
        $ext = end($array);
        $model->profile = Yii::$app->security->generateRandomString() . ".{$ext}";
        $path = Yii::getAlias('@app') . '/web/uploads/' . $model->profile;
        $photo->saveAs($path);
    }

    public static function CreateUser($model)
    {
        $result = "Success";
        $transaction_failed = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //upload image
            $model->order_type = "Order";
            $photo = UploadedFile::getInstance($model, 'profile');
            if ($photo !== null) {
                $profile_save = User::profile_save($photo, $model);
            }

            $current_level = \common\models\UsersLevel::findOne($model->user_level_id);
            if ($model->parent_user) {
                $model->parent_id = $model->parent_user;
            } else {
                $model->parent_id = Yii::$app->user->identity->id;
            }
            // check seller or general
            if ($current_level->max_user == '-1') {
                $auth = \Yii::$app->authManager;
                $role = $auth->getRole('seller');
            } else {
                $auth = \Yii::$app->authManager;
                $role = $auth->getRole('general');
            }

            //   check the limit of user
            $total_parent_user_current_level = User::find()->where(['=', 'parent_id', $model->parent_id])->count();

            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->getpassword();

            //    check not company user and not seller and user space remain
            if ($current_level->max_user != '-1' && $total_parent_user_current_level > $current_level->max_user && $model->company_user != '1') {
                $result = array("error" => "Max users reached.");
            } else {
                if ($model->save()) {
                    \common\models\Account::create_accounts($model);
                    \common\models\StockStatus::set_minimum_stock_level($model->id);
                    $order = \common\models\Order::insertOrder($model, true, false, false, true);
                    //bonus for super vip or vip
                    $super_vip_level = array_search('Super Vip Team', \common\models\Lookup::$user_levels);
                    $vip_level = array_search('VIP Team', \common\models\Lookup::$user_levels);
                    /*-----------Removed  on the request of client 
                    if ($model->user_level_id == $super_vip_level || $model->user_level_id == $vip_level) {
                        $model->unit_price = '0';
                        if ($model->user_level_id == $super_vip_level) {
                            $model->quantity = '50';
                            $order = \common\models\Order::insertOrder($model, true, true, false, true);
                        } else if ($model->user_level_id == $vip_level) {
                            $model->quantity = '20';
                            $order = \common\models\Order::insertOrder($model, true, true, false, true);
                        }
                    }  ---------*/
                    $auth->assign($role, $model->id);
                    $transaction->commit();
                    $result = $model->id;

                } else {
                    if (isset($model->getErrors()['username'])) {
                        $result = array("username" => $model->getErrors()['username']);
                    } else {
                        $result = array("error" => "Some error occured, please try again later.");
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $result = array("error" => "Some error occured, please try again later.");
        }
        return $result;
    }

    public function username($id)
    {
        $users = \common\models\User::find()->where(['id' => $id])->one();
        return $users['username'];
    }
    public function getUserLevel()
    {
        return $this->hasOne(UsersLevel::className(), ['id' => 'user_level_id']);
    }
    public static function updateUser($model, $oldmodel)
    {
        //upload image
        $photo = UploadedFile::getInstance($model, 'profile');
        if ($photo !== null) {
            $profile_save = User::profile_save($photo, $model);
        }
        if ($model->user_level_id != $oldmodel->user_level_id) {
            $changelog_entry = \common\models\ChangeLog::insertData($oldmodel);
        }
        if (!empty($model->password)) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
        }
        $model->save();
    }
    public function userDetail($id)
    {
        $user_level_name = (new Query())
            ->select('users_level.name,users_level.id,users_level.parent_id')
            ->from('user')
            ->innerJoin('users_level', 'user.user_level_id = users_level.id')
            ->where(['=', 'user.id', $id])
            ->one();
        return $user_level_name;
    }
    public static function getParent($q, $type, $parent)
    {
        if (empty($type)) {
            return [];
        }

        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select('id as id, username AS text')
            ->from('user')
            ->where(['=', 'user_level_id', $type]);
        if (!is_null($q)) {
            $query->andWhere(['like', 'username', $q]);
        }

        $query->andWhere(['=', 'parent_id', $parent])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
    }

    public static function CreateCustomer($model)
    {
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole('customer');
        $model->setPassword($model->password);
        $model->generateAuthKey();
        if ($model->save()) {
            $auth->assign($role, $model->id);
            return true;
        } else {
            return false;
        }
    }
    public static function RequestedUserDetail($model)
    {
        $model->order_type = 'Order';
        $requstedUserDetail = User::findOne(['id' => $model->order_request_id]);
        $model->request_user_level = $requstedUserDetail->user_level_id;
        $model->request_agent_name = $requstedUserDetail->id;
        $model->all_level = $requstedUserDetail->user_level_id;
        $model->parent_user = $requstedUserDetail->id;
        $Role = Yii::$app->authManager->getRolesByUser($model->user_id);
        if (!isset($Role['customer'])) {
            $model->order_type = 'Request';
            $UserDetail = User::findOne(['id' => $model->user_id]);
            $model->child_level = $UserDetail->user_level_id;
            $model->child_user = $UserDetail->id;
        }
        return $model;
    }

}
