<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_detail".
 *
 * @property int $id
 * @property int $payment_method
 *
 * @property Gl[] $gls
 */
class PaymentDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_method'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_method' => Yii::t('app', 'Payment Method'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGls()
    {
        return $this->hasMany(Gl::className(), ['payment_detail_id' => 'id']);
    }
}
