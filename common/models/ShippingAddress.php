<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shipping_address".
 *
 * @property int $id
 * @property int $order_id
 * @property string $email
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $postal_code
 * @property string $district
 * @property string $province
 * @property string $country
 *
 * @property Order $order
 */
class ShippingAddress extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','id','sr','company_id', 'branch_id'], 'required'],
            [['email', 'phone_no', 'mobile_no', 'postal_code', 'district', 'province', 'country', 'name'], 'string', 'max' => 100],
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
            'email' => Yii::t('app', 'Email'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'district' => Yii::t('app', 'District'),
            'province' => Yii::t('app', 'Province'),
            'country' => Yii::t('app', 'Country'),
        ];
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'import' ) {
                if(Yii::$app->user->isGuest){
                    $refferalUser = \common\models\User::findOne(['id'=>Yii::$app->request->get('id')]);
                    $companyId = $refferalUser->company_id;
                    $branchId = $refferalUser->branch_id;
                }else{
                    $companyId = Yii::$app->user->identity->company_id;
                    $branchId = Yii::$app->user->identity->branch_id;
              }
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\ShippingAddress::tableName(), $companyId);
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
    public static function insertShippingAddress($model, $order_id, $for_user_creation = false)
    {
        $shipping_address = new ShippingAddress();
        $shipping_address->beforeValidate();
        $shipping_address->order_id = $order_id;
        // $shipping_address->email = $model->email;
        $shipping_address->phone_no = $model->phone_no;
        if ($for_user_creation) {
            $shipping_address->name = $model->first_name . ' ' . $model->last_name;
        } else {
            if (isset($model->name) && !empty($model->name)) { //For Customer
                $shipping_address->name = $model->name;
            } else // For Agent
            {
                if ($model->order_type == "Transfer") {
                    $shipping_address->name = $model->username($model->user_id);
                } else {
                    $shipping_address->name = $model->username($model->order_request_id);
                }
            }
        }
        $shipping_address->mobile_no = $model->mobile_no;
        $shipping_address->postal_code = $model->postal_code;
        $shipping_address->district = $model->district;
        $shipping_address->province = $model->province;
        $shipping_address->address = $model->address;
        $shipping_address->country = $model->country;
        return $shipping_address->save();

    }
    public static function updateShippingAddress($model)
    {
        $shipping = \common\models\ShippingAddress::findOne(['order_id' => $model->id]);
        // $shipping_address->email = $model->email;
        $shipping->phone_no = $model->phone_no;
        if (isset($model->name) && !empty($model->name)) { //For Customer
            $shipping->name = $model->name;
        } else // For Agent
        {
            if ($model->order_type == "Transfer") {
                $shipping_address->name = $model->username($model->user_id);
            } else {
                $shipping_address->name = $model->username($model->order_request_id);
            }
        }
        $shipping->mobile_no = $model->mobile_no;
        $shipping->postal_code = $model->postal_code;
        $shipping->district = $model->district;
        $shipping->province = $model->province;
        $shipping->address = $model->address;
        $shipping->country = $model->country;
        return $shipping->save();
    }

}
