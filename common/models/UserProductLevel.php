<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_product_level".
 *
 * @property int $id
 * @property int $product_id
 * @property int $units
 * @property double $price
 * @property int $user_level_id
 *
 * @property Product $product
 * @property UsersLevel $userLevel
 */
class UserProductLevel extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_product_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'user_level_id'], 'required'],
            [['product_id', 'units', 'user_level_id'], 'safe'],
            [['price'], 'number'],
            // [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            // [['user_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersLevel::className(), 'targetAttribute' => ['user_level_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product Name'),
            'units' => Yii::t('app', 'Units(<=)'),
            'price' => Yii::t('app', 'Price'),
            'user_level_id' => Yii::t('app', 'User Level Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevel()
    {
        return $this->hasOne(UsersLevel::className(), ['id' => 'user_level_id']);
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'import' ) {
                $companyId = Yii::$app->user->identity->company_id;
                $branchId = Yii::$app->user->identity->branch_id;
                $this->id = \common\components\Constants::GUID();
                // $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\UserProductLevel::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
            }
            return true;
        }
    }
    public static function pricingData($id, $user_level, $product_id, $type = null, $check_units = true){
      
        $productDetail = \common\models\Product::findOne(['id'=>$product_id]);
        $detai_item['pname'] = $productDetail->name;
        $detai_item['pid'] = $product_id;
        if ($type != null) {
            if ($type == "Return") {
                $unit_price = UserProductLevel::find()->select(['min(price) as price'])->where(['product_id' => $product_id])->one();
               
                $detai_item['price'] = $unit_price['price'];
                return json_encode($detai_item);
            }
        }
        $query = UserProductLevel::find()->where(['product_id' => $product_id]);
        if ($type != 'Request') {
            $query->andWhere(['user_level_id' => $user_level]);
        }
        $query->andWhere(['<=', 'units', $id]);
        if ($check_units == 'false') {
            $price_query = new \common\components\Query();
            $price_query->select('min(price) as min_price,max(price) as max_price')
                ->from('user_product_level')
                ->where(['product_id' => $product_id]);
            if ($type != 'Request') {
                $price_query->andWhere(['user_level_id' => $user_level]);
            }
            $price_query = $price_query->one();
        }
        $query->orderBy(['price' => SORT_DESC]);
        $one_unit = $query->one();
        if ($one_unit) {
            $detai_item['price'] = $one_unit->price;
            return json_encode($detai_item);
        } else if ($price_query != null) {

            if (UserProductLevel::find()->where(['user_level_id' => $user_level])->andWhere(['product_id' => $product_id])->andWhere(['<', 'units', $id])->count() > 1) {
                $detai_item['price'] = $price_query['min_price'];
            } else {
                $detai_item['price'] = $price_query['max_price'];
            }
            return json_encode($detai_item);
        } else {
            $one_unit = UserProductLevel::find()->where(['user_level_id' => $user_level])->andWhere(['product_id' => $product_id])->min('units');
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
