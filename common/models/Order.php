<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\web\UploadedFile;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_ref_no
 * @property string $shipper
 * @property string $cod
 * @property string $additional_requirements
 * @property string $file
 * @property int $user_id
 * @property int $status
 * @property int $order_request_id
 * @property string $requested_date
 *
 * @property Account[] $accounts
 * @property User $user
 * @property ProductOrder[] $productOrders
 */
class Order extends \yii\db\ActiveRecord
{

    public $order_type;
    public $all_level;
    public $parent_user;
    public $child_user;
    public $child_level;
    public $request_user_level;
    public $request_agent_name;
    public $rquest_customer;
    public $product_order_info;
    //customer address
    public $address;
    public $phone_no;
    public $mobile_no;
    public $postal_code;
    public $district;
    public $province;
    public $country;
    public $email;
    public $name;
    // stored value
    public $customer_id;
    public $single_price;
    public $total_price;
    public $product_id;
    public $quantity;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $total_stock;
    public $shipping_address_id;
    public $user_level_id;

    const SCENARIO_ORDER = 'order';
    const SCENARIO_REQUEST = 'request';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
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

    // public function scenarios()
    // {

    //     $scenarios = parent::scenarios();
    //     //$scenarios[self::SCENARIO_ORDER] = ['email', 'request_user_level', 'request_agent_name'];
    //     //$scenarios[self::SCENARIO_REQUEST] = ['all_level', 'parent_user', 'child_level', 'child_user'];
    //     return $scenarios;
    // }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'order_request_id', 'quantity', 'all_level', 'parent_user', 'child_user', 'child_level', 'request_user_level', 'rquest_customer', 'customer_id', 'quantity'], 'integer'],
            [['requested_date', 'order_type', 'request_agent_name', 'product_order_info', 'created_at', 'updated_at', 'created_by', 'updated_by', 'address', 'city', 'country', 'postal_code', 'district', 'province', 'mobile_no', 'phone_no', 'email', 'product_id', 'total_price', 'single_price', 'payment_method', 'total_stock', 'shipping_address_id', 'order_external_code', 'order_tracking_code', 'order_external_code', 'order_tracking_code'], 'safe'],
            [['payment_slip'], 'file'],
            [['order_ref_no', 'shipper', 'cod', 'additional_requirements'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['quantity', 'postal_code', 'name', 'all_level', 'parent_user', 'child_level', 'child_user', 'request_agent_name', 'request_user_level'], 'validate_order', 'skipOnEmpty' => false],
            ['payment_slip', 'file', 'extensions' => 'pdf, jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Limit is 2MB', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_ref_no' => Yii::t('app', 'Order Ref No'),
            'shipper' => Yii::t('app', 'Shipper'),
            'cod' => Yii::t('app', 'Cod'),
            'additional_requirements' => Yii::t('app', 'Additional Requirements'),
            'file' => Yii::t('app', 'File'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'order_request_id' => Yii::t('app', 'Order Request ID'),
            'quantity' => Yii::t('app', 'Quantity'),
            'requested_date' => Yii::t('app', 'Requested Date'),
            'total_price' => Yii::t('app', 'Total Price'),
            'single_price' => Yii::t('app', 'Unit Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['order_id' => 'id']);
    }

    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create') {
                $ref_no = (Order::find()->max('id')) + 1;
                $this->order_ref_no = '' . $ref_no;
                $this->requested_date = date('Y-m-d');
            }
            return true;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getShippingAddresses()
    {
        return $this->hasOne(ShippingAddress::className(), ['order_id' => 'id']);
    }

    public function username($id)
    {
        if (!empty($id)) {
            $users = \common\models\User::find()->where(['id' => $id])->one();
            return $users->username;
        }
        return '';
    }
    public function leveluser($id)
    {
        if (!empty($id)) {
            $users = \common\models\UsersLevel::find()->where(['id' => $id])->one();
            return $users->name;
        }
    }
    public static function getShippingDetail($model)
    {
        $shipping = \common\models\ShippingAddress::findOne(['order_id' => $model->id]);
        $model->shipping_address_id = $shipping->id;
        $model->address = $shipping->address;
        $model->phone_no = $shipping->phone_no;
        $model->mobile_no = $shipping->mobile_no;
        $model->postal_code = $shipping->postal_code;
        $model->district = $shipping->district;
        $model->province = $shipping->province;
        $model->country = $shipping->country;
        $model->email = $shipping->email;
        $model->name = $shipping->name;
        return $model;

    }
    public static function saveSlip($model, $photo)
    {
        $model->payment_slip = $photo->name;
        $array = explode(".", $photo->name);
        $ext = end($array);
        $model->payment_slip = Yii::$app->security->generateRandomString() . ".{$ext}";
        $path = Yii::getAlias('@app') . '/web/uploads/' . $model->payment_slip;
        $photo->saveAs($path);
    }
    public static function orderQuantity($user_id)
    {
        $order_quantity = (new Query())
            ->select('SUM(remaining_quantity) as remaning_stock')
            ->from('stock_in')
            ->where("user_id = '$user_id'")
            ->andWhere("product_id = '1'")
            ->groupby(['product_id'])
            ->one();
        return $order_quantity;
    }
    public static function CreateOrder($model)
    {
        $result = "";
        $transaction_failed = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->status = array_search('Pending', \common\models\Lookup::$status);
            if ($model->order_type == "Order") {
                $model->order_request_id = $model->request_agent_name;
                $model->user_id = $model->rquest_customer;

                if (!$model->email) {
                    $model->email = 'customer@gmail.com';
                }
                $check_user_already_exist = \common\models\User::find()->where(['email' => $model->email])->one();
                if ($check_user_already_exist) {
                    $model->user_id = $check_user_already_exist->id;
                } else {
                    $customer_user = \common\models\User::insert_user($model);
                    $auth = \Yii::$app->authManager;
                    $role = $auth->getRole('customer');
                    $auth->assign($role, $customer_user->id);
                    $model->user_id = $customer_user->id;
                }
            } else {
                if ($model->order_type == "Return") {
                    $model->status = array_search('Return Request', \common\models\Lookup::$status);
                } else if ($model->order_type == "Transfer") {
                    $model->status = array_search('Transfer Request', \common\models\Lookup::$status);
                }
                $model->order_request_id = $model->parent_user;
                $model->user_id = $model->child_user;
            }

            $paymentBank = array_search('Bank Transfer', \common\models\Lookup::$order_status);
            if ($model->payment_method == $paymentBank) {
                $photo = UploadedFile::getInstance($model, 'payment_slip');
                if ($photo !== null) {
                    $photo_save = Order::saveSlip($model, $photo);
                }
            }
            // $paymentCard = array_search('Credit Card', \common\models\Lookup::$payment_method);
            // if ($model->payment_method == $paymentCard) {
            //     $orderStatus = array_search('Payment Pending', \common\models\Lookup::$status);
            //     $model->status = $orderStatus;
            // }
            if ($model->save()) {
                $product_order = \common\models\ProductOrder::insertProductOrder($model->quantity, $model->single_price, $model);
                $shipping_address = \common\models\ShippingAddress::insertShippingAddress($model, $model->id);
                $transaction->commit();
                $result = "transaction_complete";
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $result = "transaction_failed";
        }
        return $result;
    }
    public static function updateSave($model)
    {
        if ($model->order_type == "Order") {
            $model->order_request_id = $model->request_agent_name;
        } else {
            $model->order_request_id = $model->parent_user;
            $model->user_id = $model->child_user;
        }
        $paymentBank = array_search('Bank Transfer', \common\models\Lookup::$order_status);
        if ($model->payment_method == $paymentBank) {
            $photo = UploadedFile::getInstance($model, 'payment_slip');
            if ($photo !== null) {
                $photo_save = Order::saveSlip($model, $photo);
            }
        }

        return $model->save();
    }
    public static function updateBeforeLoad($model)
    {
        // shipping detail for order
        $model = Order::getShippingDetail($model);
        // Order type and dropdown values for setting customer and agent
        $model = \common\models\User::RequestedUserDetail($model);
        // Product detail for price and quantity
        $model = \common\models\ProductOrder::productOrderDetail($model);
        // check status of order
        $orderReturn = array_search('Return Request', \common\models\Lookup::$status);
        $orderTransfer = array_search('Transfer Request', \common\models\Lookup::$status);
        // order type for return and transfer
        if ($orderReturn == $model->status) {
            $model->order_type = 'Return';
            $currentStock = \common\models\helpers\Statistics::CurrentStock($model->child_user);
        } else {
            $currentStock = \common\models\helpers\Statistics::CurrentStock($model->request_agent_name);
        }
        if ($orderTransfer == $model->status) {
            $model->order_type = 'Transfer';
        }
        $model->total_stock = $currentStock;
        return $model;
    }
    public static function insertOrder($user_model, $approve_order = false, $is_bonus = false, $validate = true, $for_customer_creation = false)
    {
        $order = new Order();
        $order->isNewRecord = true;
        $order->id = null;
        $order->user_id = $user_model->id;
        if (!$is_bonus) {
            $order->order_request_id = $user_model->parent_id;
            $pending_status = array_search('Pending', \common\models\Lookup::$status);
            $order->status = $pending_status;
        } else {

            $order->order_request_id = '1';
            $user_model->parent_id = '1';
            $bonus_status = array_search('Bonus', \common\models\Lookup::$status);
            $order->status = $bonus_status;
        }
        $order->save($validate);
        if ($order->id) {

            $product_order = \common\models\ProductOrder::insertProductOrder($user_model->quantity, $user_model->unit_price, $order);

            $shipping_address = \common\models\ShippingAddress::insertShippingAddress($user_model, $order->id, $for_customer_creation);
            if ($approve_order) {
                $stock_in = \common\models\StockIn::approve($order->id, $user_model->id, $user_model->parent_id);
            }
        }
        return $order;
    }

    public static function cancel_request($order_id)
    {
        $order_detail = Order::find()->where(['id' => $order_id])->one();
        $transfer_request = array_search('Transfer Request', \common\models\Lookup::$status);
        $transfer_cancel = array_search('Transfer Canceled', \common\models\Lookup::$status);
        $return_request = array_search('Return Request', \common\models\Lookup::$status);
        $return_cancel = array_search('Return Canceled', \common\models\Lookup::$status);
        $request_cancel = array_search('Request Canceled', \common\models\Lookup::$status);
        $status = null;
        if ($order_detail->status == $transfer_request) {
            $status = $transfer_cancel;
        } elseif ($order_detail->status == $return_request) {
            $status = $return_cancel;
        } else {
            $status = $request_cancel;
        }
        Yii::$app->db->createCommand()
            ->update('order', ['status' => $status], 'id =' . $order_id)
            ->execute();
        return true;
    }

    public static function update_status($id)
    {
        return Yii::$app->db->createCommand()
            ->update('order', ['status' => '1'], 'id =' . $id)
            ->execute();
    }

    public static function update_return_status($id)
    {
        return Yii::$app->db->createCommand()
            ->update('order', ['status' => '4'], 'id =' . $id)
            ->execute();
    }

    public static function update_transfer_status($id)
    {
        return Yii::$app->db->createCommand()
            ->update('order', ['status' => '6'], 'id =' . $id)
            ->execute();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['order_id' => 'id']);
    }

    public function productorder($id)
    {
        $i = 1;
        $product_order_data = "";
        $order = \common\models\ProductOrder::find()->where(['=', 'order_id', $id])->all();

        foreach ($order as $p_order) {
            $product_order_data .= '<div class="vehicle_grid"> ' . $i . '&nbsp;&nbsp;Quantity' . $p_order->quantity . '&nbsp;&nbsp;Price/Unit#' . $p_order->order_price . '<br></div>';
            $i++;
        }
        return $product_order_data;
    }
    public function validate_order($attribute, $params)
    {
        $user_id = Yii::$app->user->getId();
        $Role = Yii::$app->authManager->getRolesByUser($user_id);
        if ($this->payment_method == array_search('Bank Transfer', \common\models\Lookup::$payment_method)) {
            if (empty($_FILES['Order']['name']['payment_slip'])) {
                $this->addError('payment_slip', 'Payment slip is required.');
            }
        }
        if (empty($this->quantity)) {
            $this->addError('quantity', 'Quanity must be greater than 0.');
        }
        // Customer Order Validations
        if ($this->order_type == "Order") {
            $this->OrderTypeValidation($Role);
        }
        // Agent Request Validations
        if ($this->order_type == "Request") {
            $this->RequestTypeValidation($Role);
        }
        // Transfer Request Validations
        if ($this->order_type == "Return") {
            $this->ReturnTypeValidation($Role);
        }
        // Return Request Validations
        if ($this->order_type == "Transfer") {
            $this->TransferTypeValidation($Role);
        }

    }
    public function OrderTypeValidation($Role)
    {
        // $user_id = $this->user_id;
        // $RoleofRequester = Yii::$app->authManager->getRolesByUser($user_id);
        // if (isset($RoleofRequester['customer'])) {
        if (empty($this->postal_code)) {
            $this->addError('postal_code', 'Postal Code is required.');
        }
        if (empty($this->name)) {
            $this->addError('name', 'Name is required.');
        }
        if (empty($this->name)) {
            $this->addError('address', 'Address is required.');
        }
        if (empty($this->name)) {
            $this->addError('mobile_no', 'Phone no. is required.');
        }
        //}
        if (isset($Role['super_admin'])) {
            if (empty($this->request_user_level)) {
                $this->addError('request_user_level', 'User level is required.');
            }
            if (empty($this->request_agent_name)) {
                $this->addError('request_agent_name', 'Agent name is required.');
            }
        }

    }
    public function RequestTypeValidation($Role)
    {
        if (isset($Role['super_admin'])) {
            if (empty($this->all_level)) {
                $this->addError('all_level', 'User level is required.');
            }
            if (empty($this->parent_user)) {
                $this->addError('parent_user', 'Parent user is required.');
            }
            if (empty($this->child_level)) {
                $this->addError('child_level', 'Child level is required.');
            }
            if (empty($this->child_user)) {
                $this->addError('child_user', 'Child name is required.');
            }
        }
    }
    public function TransferTypeValidation($Role)
    {
        if (empty($this->child_user)) {
            $this->addError('child_user', 'Transfer to is required.');
        }
        if (isset($Role['super_admin'])) {
            if (empty($this->all_level)) {
                $this->addError('all_level', 'User level is required.');
            }
            if (empty($this->parent_user)) {
                $this->addError('parent_user', 'Transfer from is required.');
            }
        }
    }
    public function ReturnTypeValidation($Role)
    {
        if (isset($Role['super_admin'])) {
            if (empty($this->all_level)) {
                $this->addError('all_level', 'User level is required.');
            }
            if (empty($this->parent_user)) {
                $this->addError('parent_user', 'Parent user is required.');
            }
            if (empty($this->child_level)) {
                $this->addError('child_level', 'Child level is required.');
            }
            if (empty($this->child_user)) {
                $this->addError('child_user', 'Child name is required.');
            }
        }
    }
    public static function GetOrderHistory($order_external_id)
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
}
