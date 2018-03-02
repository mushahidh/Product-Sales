<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
            // echo $form->field($model, 'category_id')->widget(Select2::classname(), [
            //     'data' => common\models\Category::getallcategory(),
            //     'theme' => Select2::THEME_BOOTSTRAP,
            //     'options' => ['placeholder' => 'Select Category  ...'],
            //     //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
            //     'theme' => Select2::THEME_BOOTSTRAP,
            //     'pluginOptions' => [
            //     'allowClear' => true,
            //     ],

            // ]);
            ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>
    <?php if($model->isNewRecord){
$all_images = '';
    }
    ?>
    <?=
                $form->field($model, 'image[]')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*','multiple' => true],
                    'pluginOptions' => [
                        'showUpload' => true,
                        'initialPreview'=>$all_images,
                        'overwriteInitial' => true,
                    ],
                ]);
                ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
