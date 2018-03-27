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
class StockStatus extends \common\components\ActiveRecord {

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
            [['user_id', 'product_id','id','sr','company_id', 'branch_id'], 'required'],
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
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' ) {
                $companyId = Yii::$app->user->identity->company_id;
                $branchId = Yii::$app->user->identity->branch_id;
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\StockStatus::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
                
            }
            return true;
        }
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

    public static function set_minimum_stock_level($model) {

        $stock_status = new StockStatus();
        $stock_status->beforeValidate();
        $stock_status->below_percentage = '20';
        $stock_status->product_id = $model->product_id;
        $stock_status->user_id = $model->id;
       $stock_status->save();
    }

}
