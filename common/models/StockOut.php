<?php

namespace common\models;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "stock_out".
 *
 * @property int $id
 * @property int $quantity
 * @property string $timestamp
 * @property int $stock_in_id
 * @property int $product_order_id
 *
 * @property ProductOrder $productOrder
 * @property StockIn $stockIn
 */
class StockOut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_out';
    }


    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'stock_in_id', 'product_order_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['stock_in_id', 'product_order_id'], 'required'],
            [['product_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductOrder::className(), 'targetAttribute' => ['product_order_id' => 'id']],
            [['stock_in_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockIn::className(), 'targetAttribute' => ['stock_in_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'stock_in_id' => Yii::t('app', 'Stock In ID'),
            'product_order_id' => Yii::t('app', 'Product Order ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrder()
    {
        return $this->hasOne(ProductOrder::className(), ['id' => 'product_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIn()
    {
        return $this->hasOne(StockIn::className(), ['id' => 'stock_in_id']);
    }
    public static function insert_quantity($product_order_id,$stock_in_id,$quantity){
       
         $stockOut = new StockOut();
         $stockOut->isNewRecord = true;
         $stockOut->id = null;
         $stockOut->product_order_id = $product_order_id;
         $stockOut->timestamp = new Expression('NOW()');
         $stockOut->stock_in_id = $stock_in_id;
         $stockOut->quantity = $quantity;
         
     return  $stockOut->save();
     
        
    }
}
