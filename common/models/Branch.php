<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "branch".
 *
 * @property string $id
 * @property int $sr
 * @property string $name
 * @property string $email
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $street
 * @property string $zip
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $company_id
 *
 * @property Account[] $accounts
 * @property Company $company
 * @property Category[] $categories
 * @property ChangeLog[] $changeLogs
 * @property Customer[] $customers
 * @property Gl[] $gls
 * @property Image[] $images
 * @property LevelPercentage[] $levelPercentages
 * @property Order[] $orders
 * @property PaymentDetail[] $paymentDetails
 * @property Postcode[] $postcodes
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
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
            [['id', 'company_id'], 'required'],
            [['sr'], 'integer'],
            [['id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'company_id'], 'string', 'max' => 64],
            [['name', 'email', 'country', 'state', 'city', 'street', 'zip'], 'string', 'max' => 45],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
            'street' => Yii::t('app', 'Street'),
            'zip' => Yii::t('app', 'Zip'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'company_id' => Yii::t('app', 'Company ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangeLogs()
    {
        return $this->hasMany(ChangeLog::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGls()
    {
        return $this->hasMany(Gl::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevelPercentages()
    {
        return $this->hasMany(LevelPercentage::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails()
    {
        return $this->hasMany(PaymentDetail::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostcodes()
    {
        return $this->hasMany(Postcode::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingAddresses()
    {
        return $this->hasMany(ShippingAddress::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIns()
    {
        return $this->hasMany(StockIn::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOuts()
    {
        return $this->hasMany(StockOut::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockStatuses()
    {
        return $this->hasMany(StockStatus::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProductLevels()
    {
        return $this->hasMany(UserProductLevel::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersLevels()
    {
        return $this->hasMany(UsersLevel::className(), ['branch_id' => 'id']);
    }
    public static  function createBranch($this,$companyDetail){
        $branch = new Branch();
        $branch->id =  \common\components\Constants::GUID();
        $branch->company_id = $companyDetail->id;
        $branch->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\Branch::tableName(), $companyDetail->id);
        $branch->name = $this['name'];
        $branch->email = $this['email'];
        $branch->country = $this['country'];
        $branch->state = $this['state'];
        $branch->city = $this['city'];
        $branch->zip = $this['zip'];
        return $branch->save() ? $branch : null;
    }
}
