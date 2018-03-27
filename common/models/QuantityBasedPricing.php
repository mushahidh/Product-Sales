<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use Yii;
use common\components\Query;

/**
 * This is the model class for table "quantity_based_pricing".
 *
 * @property string $id
 * @property int $quantity
 * @property double $price
 * @property string $company_id
 * @property string $branch_id
 * @property string $created_by
 * @property string $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $sr
 *
 * @property Branch $branch
 * @property Company $company
 */
class QuantityBasedPricing extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quantity_based_pricing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'branch_id', 'product_id'], 'required'],
            [['quantity', 'sr'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'company_id', 'branch_id', 'created_by', 'updated_by'], 'string', 'max' => 64],
            [['id'], 'unique'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['branch_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'quantity' => Yii::t('app', 'Quantity(<=)'),
            'price' => Yii::t('app', 'Price'),
            'company_id' => Yii::t('app', 'Company ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'sr' => Yii::t('app', 'Sr'),
        ];
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'import' ) {
                $companyId = Yii::$app->user->identity->company_id;
                $branchId = Yii::$app->user->identity->branch_id;
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\QuantityBasedPricing::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
                
            }
            return true;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
    public function getProduct() 
    { 
        return $this->hasOne(Product::className(), ['id' => 'product_id']); 
    } 
    public static function pricingDatabackup($id, $user_level, $product_id, $type = null, $check_units = true){
        
        $productDetail = \common\models\Product::findOne(['id'=>$product_id]);
        $detai_item['pname'] = $productDetail->name;
        $detai_item['pid'] = $product_id;
        if ($type != null) {
            if ($type == "Return") {
                $unit_price = QuantityBasedPricing::find()->select(['min(price) as price'])->where(['product_id' => $product_id])->one();
               
                $detai_item['price'] = $unit_price['price'];
                return json_encode($detai_item);
            }
        }
        $price_query = (new Query())
        ->select('price')
        ->from('quantity_based_pricing')
        ->where(['=','product_id' , $product_id])
        ->andWhere(['<=', 'quantity', $id])
        ->orderBy(['price' => SORT_DESC])
        ->one();
      
        var_dump($price_query);
        exit();
        $detai_item['price'] = $query->price;
        return json_encode($detai_item);
    }
    public static function pricingData($id, $user_level, $product_id, $type = null, $check_units = true){
        
        $productDetail = \common\models\Product::findOne(['id'=>$product_id]);
        $detai_item['pname'] = $productDetail->name;
        $detai_item['pid'] = $product_id;
        if ($type != null) {
            if ($type == "Return") {
                $unit_price = QuantityBasedPricing::find()->select(['min(price) as price'])->where(['product_id' => $product_id])->one();
               
                $detai_item['price'] = $unit_price['price'];
                return json_encode($detai_item);
            }
        }
        $query = QuantityBasedPricing::find()->where(['product_id' => $product_id]);
        // if ($type != 'Request') {
        //     $query->andWhere(['user_level_id' => $user_level]);
        // }
        $query->andWhere(['<=', 'quantity', $id]);
       
        if ($check_units == 'false') {
            $price_query = new \yii\db\Query();
            $price_query->select('min(price) as min_price,max(price) as max_price')
                ->from('quantity_based_pricing')
                ->where(['product_id' => $product_id]);
            $price_query = $price_query->one();
        }
        $query->orderBy(['price' => SORT_DESC]);
        $one_unit = $query->one();
          
      
        if ($one_unit) {
            $detai_item['price'] = $one_unit->price;
            
            return json_encode($detai_item);
        } else if ($price_query != null) {

            if (QuantityBasedPricing::find()->where(['product_id' => $product_id])->andWhere(['<', 'quantity', $id])->count() > 1) {
                $detai_item['price'] = $price_query['min_price'];
            } else {
                $detai_item['price'] = $price_query['max_price'];
            }
            return json_encode($detai_item);
        } else {
            $one_unit = QuantityBasedPricing::find()->where(['product_id' => $product_id])->min('quantity');
            if ($one_unit) {
                $detai_item['units'] = $one_unit;
                return json_encode($detai_item);
            } else {
                $product = \common\models\Product::find()->where(['id' => $product_id])->one();
                $detai_item['price'] = $product['price'];
                return json_encode($detai_item);
            }
        }
    }
}
