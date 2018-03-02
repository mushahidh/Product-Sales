<?php

namespace common\models;
use yii\helpers\Html;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property double $price
 *
 * @property Image[] $images
 * @property Category $category
 * @property StockIn[] $stockIns
 * @property UserProductLevel[] $userProductLevels
 */
class Product extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['category_id'], 'required'],
            [['category_id'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['image'], 'file', 'maxFiles' => 30],
            [['name'], 'string', 'max' => 45],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockIns()
    {
        return $this->hasMany(StockIn::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProductLevels()
    {
        return $this->hasMany(UserProductLevel::className(), ['product_id' => 'id']);
    }
    public static function getallproduct()
    {
        $data = Product::find()->all();

        $value = (count($data) == 0) ? ['' => ''] : \yii\helpers\ArrayHelper::map($data, 'id', 'name'); //id = your ID model, name = your caption

        return $value;
    }
    public static function CreateProduct($model)
    {
        if ($model->save()) {
            $photo = UploadedFile::getInstances($model, 'image');
            if ($photo !== null) {
                $save_images = \common\models\Image::save_images($model->id, $photo);
            }
        }
    }
  
    public static function updateProduct($model,$product_old_images){
        $photo = UploadedFile::getInstances($model, 'image');
        if ($photo) {
            $command = Yii::$app->db->createCommand()
            ->delete('image', 'product_id = '.$model->id)
            ->execute();
            $save_images = \common\models\Image::save_images($model->id,$photo);
        }
        return true;
    }
    public static function imgaesGallery($product_old_images){
        foreach ($product_old_images as $image) {
            $baseurl = \Yii::$app->request->BaseUrl;
            $image_url = $baseurl.'/uploads/'.$image->name;    
            $all_images[] = Html::img("$image_url",  ['class'=>'file-preview-image']);
        }
        return $all_images;
    }
}
