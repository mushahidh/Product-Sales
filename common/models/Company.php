<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "company".
 *
 * @property string $id
 * @property int $sr
 * @property string $name
 * @property string $email
 * @property string $logo
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $zip
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_on
 * @property string $updated_on
 *
 * @property Account[] $accounts
 * @property Branch[] $branches
 * @property Category[] $categories
 * @property Customer[] $customers
 * @property Gl[] $gls
 * @property Image[] $images
 * @property LevelPercentage[] $levelPercentages
 * @property Order[] $orders
 * @property PaymentDetail[] $paymentDetails
 * @property Product[] $products
 * @property ProductOrder[] $productOrders
 * @property ShippingAddress[] $shippingAddresses
 * @property StockIn[] $stockIns
 * @property StockOut[] $stockOuts
 * @property StockStatus[] $stockStatuses
 * @property User[] $users
 * @property UserProductLevel[] $userProductLevels
 * @property UsersLevel[] $usersLevels
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_on',
                'updatedAtAttribute' => 'updated_on',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['sr'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['id'], 'string', 'max' => 64],
            [['name', 'email', 'logo', 'country', 'state', 'city', 'zip', 'created_by', 'updated_by'], 'string', 'max' => 45],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sr' => Yii::t('app', 'Sr'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'logo' => Yii::t('app', 'Logo'),
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
            'zip' => Yii::t('app', 'Zip'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
        ];
    }
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGls()
    {
        return $this->hasMany(Gl::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevelPercentages()
    {
        return $this->hasMany(LevelPercentage::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(PaymentDetail::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingAddresses()
    {
        return $this->hasMany(ShippingAddress::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIns()
    {
        return $this->hasMany(StockIn::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOuts()
    {
        return $this->hasMany(StockOut::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockStatuses()
    {
        return $this->hasMany(StockStatus::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProductLevels()
    {
        return $this->hasMany(UserProductLevel::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersLevels()
    {
        return $this->hasMany(UsersLevel::className(), ['company_id' => 'id']);
    }
    public static function createCompany($this){
        $application = new Company();
        $application->id =  \common\components\Constants::GUID();
        $application->sr = \common\components\Constants::nextSrCompany(Yii::$app->db, \common\models\Company::tableName(), $application->id);
        $application->name = $this['name'];
        $application->email = $this['email'];
        $application->country = $this['country'];
        $application->state = $this['state'];
        $application->city = $this['city'];
        $application->zip = $this['zip'];
        $photo = UploadedFile::getInstance($this, 'logo');
        if ($photo !== null) {
            $application->logo= $photo->name;
            $array = explode(".", $photo->name);
            $ext=end($array);
            $application->logo = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path =  Yii::getAlias('@app').'/web/uploads/'.$application->logo;
         //   $path = Yii::getAlias('@upload') .'/'. $model->payment_slip;
            $photo->saveAs($path);
          }
          return $application->save() ? $application : null;
    }
}
