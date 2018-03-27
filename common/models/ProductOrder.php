<?php

namespace common\models;

use Yii;
use common\components\Query;


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
class ProductOrder extends \common\components\ActiveRecord
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
            [['order_id','id','sr','company_id', 'branch_id'], 'required'],
            [['order_id', 'product_id'], 'safe'],
            [[ 'quantity', 'requested_quantity'], 'integer'],
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
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'update' ) {
                if(Yii::$app->user->isGuest){
                    $refferalUser = \common\models\User::findOne(['id'=>Yii::$app->request->get('id')]);
                    $companyId = $refferalUser->company_id;
                    $branchId = $refferalUser->branch_id;
                }else{
                    $companyId = Yii::$app->user->identity->company_id;
                    $branchId = Yii::$app->user->identity->branch_id;
              }
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\ProductOrder::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
                
            }
            return true;
        }
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
    public static function insert_order($model,$order_id)
    {
    
        $order_data = json_decode($model->product_order_info);
        foreach ($order_data->order_info as $single_order) {
            $product_order = new ProductOrder();
            $product_order->beforeValidate();
            $product_order->order_id = $order_id;
            $product_order->product_id = $single_order->product_id;
            $product_order->quantity = $single_order->unit;
            $product_order->order_price = $single_order->price;
            $product_order->requested_price = $single_order->price;
            $product_order->requested_quantity = $single_order->unit;
            return $product_order->save();
        }
    }

    public static function insertProductOrder($usermodel, $unit_price, $order)
    {
        $product_order = new ProductOrder();
        $product_order->beforeValidate();
        $product_order->order_id = $order->id;
        $product_order->product_id = $usermodel->product_id;
        $product_order->quantity = $usermodel->quantity;
        $product_order->order_price = $unit_price;
        $product_order->requested_price = $unit_price;
        $product_order->requested_quantity = $usermodel->quantity;
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
        $model->product_id = $productOrderrDetail->product_id;
       $model->total_price = $productOrderrDetail->order_price * $model->quantity;
        return $model;
        
    }
}
