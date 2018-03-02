<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $name
 * @property int $product_id
 *
 * @property Product $product
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['name'], 'string', 'max' => 450],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
    }
    public static function save_images($model_id,$photo){
     
       foreach($photo as $photo){
          $images = new Image();
           $images->isNewRecord = true;
           $images->id = null;
          $images->name= $photo->name;
          $ext = end((explode(".", $photo->name)));
          $images->name = Yii::$app->security->generateRandomString() . ".{$ext}";
          $path = Yii::getAlias('@app') . '/web/uploads/' . $images->name;
      
       //   $path = Yii::getAlias('@upload') .'/'. $model->payment_slip;
          $photo->saveAs($path);
          $images->product_id = $model_id;
          $images->save();
        
          
          }
   }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
