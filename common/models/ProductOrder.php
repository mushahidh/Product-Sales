<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product_order".
 *
 * @property int $id
 * @property int $order_id
 * @property int $quantity
 * @property double $order_price
 * @property int $requested_amount
 * @property double $requested_price
 *
 * @property Order $order
 * @property StockOut[] $stockOuts
 */
class ProductOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'product_id', 'quantity', 'requested_quantity'], 'integer'],
            [['order_price', 'requested_price'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'order_price' => Yii::t('app', 'Order Price'),
            'requested_quantity' => Yii::t('app', 'Requested Quantity'),
            'requested_price' => Yii::t('app', 'Requested Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOuts()
    {
        return $this->hasMany(StockOut::className(), ['product_order_id' => 'id']);
    }
    public static function insert_order($model)
    {
        $order_data = json_decode($model->product_order_info);
        foreach ($order_data->order_info as $single_order) {
            $product_order = new ProductOrder();
            $product_order->isNewRecord = true;
            $product_order->id = null;
            $product_order->order_id = $model->id;
            $product_order->product_id = $model->product_id;
            $product_order->quantity = $single_order->unit;
            $product_order->order_price = $single_order->price;
            $product_order->requested_price = $single_order->price;
            $product_order->requested_quantity = $single_order->unit;
            $product_order->save();

        }
    }

    public static function insertProductOrder($quantity, $unit_price, $order)
    {
        $product_order = new ProductOrder();
        $product_order->isNewRecord = true;
        $product_order->id = null;
        $product_order->order_id = $order->id;
        $product_order->product_id = '1';
        $product_order->quantity = $quantity;
        $product_order->order_price = $unit_price;
        $product_order->requested_price = $unit_price;
        $product_order->requested_quantity = $quantity;
        $product_order->save();
      

    }
    public static function updateProductOrder($model)
    {
        $product_order = ProductOrder::findOne(['order_id' => $model->id]);
        $product_order->order_id = $model->id;
        $product_order->product_id = $model->product_id;
        $product_order->quantity = $model->quantity;
        $product_order->order_price = $model->single_price;;
        $product_order->requested_price = $model->single_price;;
        $product_order->requested_quantity = $model->quantity;;
        return  $product_order->save();

    }
    public static function order_quantity($order_id)
    {
        return $order_quantity = (new Query())
            ->select('*,(quantity * order_price) as total_price')
            ->from('product_order')
            ->where("order_id = '$order_id'")
            ->all();

    }
    public static function productOrderDetail($model){
        $productOrderrDetail = ProductOrder::findOne(['order_id'=>$model->id]);
        $model->quantity = $productOrderrDetail->quantity;
        $model->single_price = $productOrderrDetail->order_price;
        $model->total_price = $productOrderrDetail->order_price * $model->quantity;
        return $model;
        
    }
}
