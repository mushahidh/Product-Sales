<?php
namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "stock_in".
 *
 * @property int $id
 * @property string $timestamp
 * @property int $initial_quantity
 * @property int $remaining_quantity
 * @property double $price
 * @property int $product_id
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 * @property StockOut[] $stockOuts
 */
class StockIn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stock_in';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price','initial_quantity'], 'required'],
            [['timestamp'], 'safe'],
            [['initial_quantity', 'remaining_quantity', 'product_id', 'user_id'], 'integer'],
            [['price'], 'number'],
            [['product_id', 'user_id'], 'required'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'initial_quantity' => Yii::t('app', 'Initial Quantity'),
            'remaining_quantity' => Yii::t('app', 'Remaining Quantity'),
            'price' => Yii::t('app', 'Price'),
            'product_id' => Yii::t('app', 'Product ID'),
            'user_id' => Yii::t('app', 'User ID'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOuts()
    {
        return $this->hasMany(StockOut::className(), ['stock_in_id' => 'id']);
    }
    public static function getRemaningStock($id, $user_id)
    {
        $order_quantity = (new Query())
            ->select('SUM(remaining_quantity) as remaning_stock')
            ->from('stock_in')
            ->where("user_id = '$user_id'")
            ->andWhere("product_id = '$id'")
            ->groupby(['product_id'])
            ->one();
        return $order_quantity['remaning_stock'];
    }
    public static function getStocks($q, $type, $type_order)
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select('stock_in.id as id, stock_in.remaining_quantity AS text')
            ->from('stock_in')
            ->join('join product on stock_in.product_id=product.id', [])
            ->where(['=', 'stock_in.user_id', $type]);
        if (!is_null($q)) {
            $query->andWhere(['like', 'product.name', $q]);
        }

        $query->andWhere(['=', 'stock_in.product_id', $type_order])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
    }
    public static function Fullfillment($postal_code, $province, $district, $cust_name, $cust_addr, $mobile_no, $external_id, $amount, $quantity,$payment_method,$representative)
    {
        $curl = curl_init();
        $sku = "BEYDEY1"; 
        if (preg_match('/^10.{3}$/', $postal_code) || preg_match('/^11.{3}$/', $postal_code) || preg_match('/^12.{3}$/', $postal_code)) {
            $ship_method = "ALP";
        } else {
            $ship_method = "K2D";
        }
        $ship_method = "EMS";
        if ($payment_method != array_search('Cash on Delivery', \common\models\Lookup::$payment_method)) {
            $amount=0;
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oms.sokochan.com/api/1.0/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{ 	"external_id":"' . $external_id . '","order_number":"' . $external_id . '","representative":"' . $representative . '", "shipping":"' . $ship_method . '","cod_amount":"' . $amount . '","customer":{ "name":"' . $cust_name . '","address":"' . $cust_addr . '", "province":"' . $province . '", "district":"' . $district . '","postal_code":"' . $postal_code . '","mobile_no":"' . $mobile_no . '"}, 	"order_items":[{"item_sku":"' . $sku . '","item_code":"","item_qty":' . $quantity . '}]}',
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic QmV5QVBJOjZhOTZlMmUyMjQ1OWRjYjY5MDEzNmNmZTM2ZDgxYjgy",
                "cache-control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
            die("cURL Error #:" . $err);
        } else {

            $resp = json_decode($response, true);

            if ($resp['code'] == 200) {
                return $resp['order_code'];
            } else {
                return false;
            }
        }

    }
    public static function GetOrderExternalStatus($order_external_id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oms.sokochan.com/api/1.0/orders/".$order_external_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic QmV5QVBJOjZhOTZlMmUyMjQ1OWRjYjY5MDEzNmNmZTM2ZDgxYjgy",
                "cache-control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public static function approve($order_id, $user_id, $order_request_id)
    {
        $data = Yii::$app->request->post();

        $stock_available = true;
        $total_order_quantity = \common\models\ProductOrder::order_quantity($order_id);
        $order_detail = \common\models\Order::findOne(['id' => $order_id]);
        $shipping_detail = \common\models\ShippingAddress::findOne(['order_id' => $order_id]);
        $transaction_failed = false;
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            foreach ($total_order_quantity as $single_order) {
                $total_quantity = $single_order['quantity'];
                if ($transaction_failed) {
                    break;
                }
                while ($single_order['quantity'] > 0) {
                    $stockin_quantity = \common\models\StockIn::avilaible_quantity($single_order['product_id'], $order_request_id);
                    if ($stockin_quantity != null) {
                        //  subtract the quantiy
                        $single_order['quantity'] = $single_order['quantity'] - $stockin_quantity['remaining_quantity'];
                        // insert stock out and update stock in
                        if ($single_order['quantity'] > 0) {
                            \common\models\StockIn::update_quantity($stockin_quantity['id'], 0);
                            \common\models\StockOut::insert_quantity($single_order['id'], $stockin_quantity['id'], $stockin_quantity['remaining_quantity']);
                            \common\models\StockIn::insert_quantity($single_order['product_id'], $single_order['order_price'], abs($single_order['quantity']), $user_id);
                        } else {
                            \common\models\StockIn::update_quantity($stockin_quantity['id'], abs($single_order['quantity']));
                            $stock_out_quantity = $stockin_quantity['remaining_quantity'] + $single_order['quantity'];
                            \common\models\StockOut::insert_quantity($single_order['id'], $stockin_quantity['id'], $stock_out_quantity);
                            \common\models\StockIn::insert_quantity($single_order['product_id'], $single_order['order_price'], $stock_out_quantity, $user_id);
                        }

                    } else {
                        $transaction_failed = true;

                        break;
                    }

                }
                \common\models\StockIn::CalculateBonus($order_request_id, $user_id, $order_id, $total_quantity);
                $Role = Yii::$app->authManager->getRolesByUser($user_id);

                if (!$transaction_failed && isset($Role['customer'])) {
                    $fullfillmentResult = StockIn::Fullfillment($shipping_detail->postal_code, $shipping_detail->province, $shipping_detail->district, $shipping_detail->name, $shipping_detail->address, $shipping_detail->mobile_no, $order_id, $single_order['order_price'], $total_quantity,$order_detail->payment_method,$order_detail->username($order_detail->order_request_id));
                    if ($fullfillmentResult != false) {
                        Yii::$app->db->createCommand()
                            ->update('order', ['order_external_code' => $fullfillmentResult], 'id =' . $order_id)
                            ->execute();
                        $transaction_failed = false;
                    } else {
                        $transaction_failed = true;
                    }
                }

            }
        } catch (Exception $e) {
                  
            $transaction_failed = true;
        }
        if ($transaction_failed) {
            $transaction->rollBack();
            return false;
        } else {
           
            $total_amount = array_sum(array_map(create_function('$o', 'return $o["total_price"];'), $total_order_quantity));
            \common\models\Gl::create_gl($total_amount, $order_request_id, $user_id, $order_id, '1');
            \common\models\Order::update_status($order_id);
            $transaction->commit();
             
            return true;
        }
    }
    public static function CreateStock($model)
    {
        $model->remaining_quantity = $model->initial_quantity;
        $model->user_id = Yii::$app->user->identity->id;
        $model->product_id = '1';
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }
    public static function ChildStock($id)
    {
        $status_stock_child = (new Query())
            ->select('SUM(stock_in.remaining_quantity) as remaning_stock,SUM(stock_in.initial_quantity) as initial_stock,user.username as name,user.id as id')
            ->from('stock_in')
            ->innerJoin('user', 'stock_in.user_id = user.id')
            ->where(['=', 'user.parent_id', Yii::$app->user->identity->id])
            ->groupby(['stock_in.product_id', 'user.id'])
            ->all();
        $notificationDetail = array();
        $notificationDetail['detail'] = '';
        $notificationDetail['count'] = 0;
        $i = 0;
        if ($status_stock_child) {
            foreach ($status_stock_child as $status_stock) {
                $stock_remaning_percent = $status_stock['remaning_stock'] / $status_stock['initial_stock'];
                $stock_remaning_percent = $stock_remaning_percent * 100;
                $selected_percentage = \common\models\StockStatus::find()->where(['user_id' => $status_stock['id']])->one();
                $remaning_percent = '';
                if ($selected_percentage) {
                    if ($selected_percentage->below_percentage > $stock_remaning_percent) {
                        $i++;
                        $remaning_percent = round($stock_remaning_percent);
                        $notificationDetail['detail'] .= ' <li class="unread available">   <a href="javascript:;">
          <div class="notice-icon"> <i class="fa fa-check"></i> </div><div><span class="name">
          ' . $status_stock['name'] . ' has <strong> ' . $remaning_percent . '%</strong>
                                                             </span>   </div>
                                                             </a>
                                                         </li>';
                    }
                }
            }
            $notificationDetail['count'] = $i;
        }
        return $notificationDetail;
    }
    public static function CalculateBonus($order_request_id, $user_id, $order_id, $quantity)
    {
        $MR_Comission = 10;
        $MR_Level_Id = array_search('Management Team', \common\models\Lookup::$user_levels);
        $MRUsers = \common\models\User::find()->where(['user_level_id' => $MR_Level_Id])->all();

        if (count($MRUsers) > 0) {
            $MRComission = ($quantity * $MR_Comission) / count($MRUsers);

            foreach ($MRUsers as $parent_user) {
                \common\models\Gl::create_gl(strval($MRComission), $parent_user->id, 1, $order_id, '1');
            }
        }
        else
        {
            $MRComission = ($quantity * $MR_Comission) / 1;
            \common\models\Gl::create_gl(strval($MRComission), $user_id, 1, $order_id, '1');
        }
        $Super_Vip_Level_Id = array_search('Super Vip Team', \common\models\Lookup::$user_levels);
        $Super_VIP_Comission = 5;
        $Super_VIP = \common\models\User::find()->where(['user_level_id' => $Super_Vip_Level_Id])->andWhere(['id' => $order_request_id])->one();
        if (!empty($Super_VIP)) {
            \common\models\Gl::create_gl(strval(($quantity * $Super_VIP_Comission)), $Super_VIP->id, 1, $order_id, '1');
        }
        $VIP_Level_Id = array_search('VIP Team', \common\models\Lookup::$user_levels);
        $VIP_Comission = 3;
        $VIP = \common\models\User::find()->where(['user_level_id' => $VIP_Level_Id])->andWhere(['id' => $order_request_id])->one();
        if (!empty($VIP)) {
            \common\models\Gl::create_gl(strval(($quantity * $VIP_Comission)), $VIP->id, 1, $order_id, '1');
        }

    }
    public static function insert_quantity($product_id, $price, $quantity, $user_id)
    {
        $stockIn = new StockIn();
        $stockIn->isNewRecord = true;
        $stockIn->id = null;
        $stockIn->initial_quantity = $quantity;
        $stockIn->remaining_quantity = $quantity;
        $stockIn->price = $price;
        $stockIn->product_id = $product_id;
        $stockIn->user_id = $user_id;
        return $stockIn->save();
    }
    public static function update_quantity($id, $amount)
    {
        return Yii::$app->db->createCommand()
            ->update('stock_in', ['remaining_quantity' => $amount], 'id =' . $id)
            ->execute();
    }
    public static function avilaible_quantity($product_id, $request_user_id)
    {
        return $order_quantity = (new Query())
            ->select('*')
            ->from('stock_in')
            ->where("user_id = '$request_user_id'")
            ->andWhere("product_id = '$product_id'")
            ->andWhere("remaining_quantity > '0'")
            ->limit('1')
            ->one();

    }
    public static function getallproduct()
    {
        $data = Product::find()->all();
        $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($data, 'id', 'name'); //id = your ID model, name = your caption
        return $value;
    }
    public function stockInReport($user_id)
    {
        return $model = User::find()
            ->where(['user_id' => $user_id])

            ->all();
    }
}
