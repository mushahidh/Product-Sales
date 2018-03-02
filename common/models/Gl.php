<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "gl".
 *
 * @property int $id
 * @property string $amount
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $payment_detail_id
 * @property int $order_id
 * @property int $account_id
 *
 * @property Account $account
 * @property Order $order
 * @property PaymentDetail $paymentDetail
 */
class Gl extends \yii\db\ActiveRecord
{
    public $user_level;
    public $receivable_user;
    public $payable_user;
    public $recieveable_amount;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gl';
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
            [['created_by', 'updated_by', 'payment_detail_id', 'order_id', 'account_id'], 'integer'],
            [['payment_detail_id', 'account_id', 'hit_account_id'], 'required'],
            [['amount'], 'string', 'max' => 45],
            [['created_at', 'updated_at', 'receivable_user', 'payable_user', 'payment_slip'], 'safe'],

            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['account_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['payment_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentDetail::className(), 'targetAttribute' => ['payment_detail_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'amount' => Yii::t('app', 'Amount'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'payment_detail_id' => Yii::t('app', 'Payment Detail ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'account_id' => Yii::t('app', 'Account ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
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
    public function getPaymentDetail()
    {
        return $this->hasOne(PaymentDetail::className(), ['id' => 'payment_detail_id']);
    }
    public static function slipSave($photo, $model)
    {
        $model->payment_slip = $photo->name;
        $array = explode(".", $photo->name);
        $ext = end($array);
        $model->payment_slip = Yii::$app->security->generateRandomString() . ".{$ext}";
        $path = Yii::getAlias('@app') . '/web/uploads/' . $model->payment_slip;
        $photo->saveAs($path);
        return $model;
    }
    public static function create_gl($amount, $user_id, $requester_id, $order_id, $payment_method, $payment_slip = null)
    {
        if ($amount > 0) {
           
            $receivable_account = \common\models\Account::findOne(['user_id' => $user_id, 'account_type' => 1]);
            $payable_account = \common\models\Account::findOne(['user_id' => $requester_id, 'account_type' => 2]);
           // if (!empty($receivable_account) && !empty($payable_account)) {
                for ($i = 0; $i < 2; $i++) {
                    $gl = new Gl();
                    $gl->isNewRecord = true;
                    $gl->id = null;
                    $gl->amount = '' . $amount;
                    $gl->order_id = $order_id;
                    if ($i == 0) {
                        $gl->account_id = $receivable_account->id;
                        $gl->hit_account_id = $payable_account->id;
                    } else {
                        $gl->account_id = $payable_account->id;
                        $gl->hit_account_id = $receivable_account->id;
                    }
                    $gl->payment_detail_id = $payment_method;
                    $gl->payment_slip = $payment_slip;
                    $gl->save();

                    if ($i == 0) {
                        $recievable_id = $gl->id;
                    }
                }

            //}

            return true;
        }
        return null;
    }
    public static function totalRecieveable($receivable_account_id, $payable_account_id)
    {
        $gl = (new Query())
            ->select('sum(amount) as amount')
            ->from('gl')
            ->where(['account_id' => $receivable_account_id])
            ->andWhere(['hit_account_id' => $payable_account_id])
            ->one();
        return $gl['amount'];

    }
    public static function get_filtered_result($user_id, $fromDate, $toDate, $account_type)
    {
        $logged_in_user = Yii::$app->user->getId();
        $Role = Yii::$app->authManager->getRolesByUser($logged_in_user);
        $gl = (new Query())
            ->select('gl.*,hit_user.username as name')
            ->from('gl')
            ->innerJoin('account as org_acc', 'org_acc.id = gl.account_id')
            ->innerJoin('user as org_user', 'org_user.id = org_acc.user_id')
            ->innerJoin('account as hit_acc', 'hit_acc.id = gl.hit_account_id')
            ->innerJoin('user as hit_user', 'hit_user.id = hit_acc.user_id')
            ->where(['org_user.id' => $user_id]);
            if (!isset($Role['super_admin'])) {
                $gl->andWhere(['hit_user.id' => $logged_in_user]); // show only result for logged in user and not all payable/receivables
            }
            $gl->andWhere(['org_acc.account_type' => $account_type]);
        if (!empty($fromDate)) {
            $gl->andWhere(['>=', 'DATE(gl.created_at)', $fromDate]);
        }

        if (!empty($toDate)) {
            $gl->andWhere(['<=', 'DATE(gl.created_at)', $toDate]);
        }

        $gl = $gl->all();
        return $gl;
    }
    public static function get_amount_by_type($user_id, $date, $account_type)
    {
        $gl = (new Query())
            ->select('sum(gl.amount) as amount')
            ->from('gl')
            ->innerJoin('account', 'account.id = gl.account_id')
            ->innerJoin('user', 'user.id = account.user_id')
            ->where(['user.id' => $user_id])
            ->andWhere(['account.account_type' => $account_type]);
        $gl->andWhere(['<=', 'DATE(gl.created_at)', $date]);
        $gl = $gl->all();
        return (int) $gl[0]['amount'];
    }
}
