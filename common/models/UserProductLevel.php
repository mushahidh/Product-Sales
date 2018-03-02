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
class UserProductLevel extends \yii\db\ActiveRecord
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
            [['product_id', 'units', 'user_level_id'], 'integer'],
            [['price'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => UsersLevel::className(), 'targetAttribute' => ['user_level_id' => 'id']],
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
            'units' => Yii::t('app', 'Units'),
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
}
