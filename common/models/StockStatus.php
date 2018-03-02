<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock_status".
 *
 * @property int $id
 * @property string $below_percentage
 * @property int $user_id
 * @property int $product_id
 *
 * @property Product $product
 * @property User $user
 */
class StockStatus extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'stock_status';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
            [['below_percentage'], 'string', 'max' => 45],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'below_percentage' => Yii::t('app', 'Below Percentage'),
            'user_id' => Yii::t('app', 'User ID'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function CreateStatus($model) {
        $model->product_id = '1';
        $model->user_id = Yii::$app->user->identity->id;
        $model->save();
    }

    public static function set_minimum_stock_level($user_id) {

        $stock_status = new StockStatus();
        $stock_status->isNewRecord = true;
        $stock_status->id = Null;
        $stock_status->below_percentage = '20';
        $stock_status->product_id = 1;
        $stock_status->user_id = $user_id;
        $stock_status->save();
    }

}
