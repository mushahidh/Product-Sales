<?php
use yii\helpers\Html;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

   <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
    'id' => 'invoice-form',
    'enableClientValidation' => true,
    'options' => [
        'validateOnSubmit' => true,
        'class' => 'form'
    ],
    'layout' => 'horizontal',
    'fieldConfig' => [
      'horizontalCssClasses' => [
          'label' => 'col-sm-4',
         // 'offset' => 'col-sm-offset-2',
          'wrapper' => 'col-sm-8',
      ],
  ],
    ]); ?>

            <div class="row">
                <div class="col-md-6">
                <?=
$form->field($model, 'file')->widget(FileInput::classname(), [
                    
                    'pluginOptions' => [
                        //'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp','pdf','jpeg'],
                        'showUpload' => true,
                        'initialPreview' => [
                            //$model->upload_invoice ? Html::img(Yii::$app->request->baseUrl . '/uploads/' . $model->upload_invoice) : null, // checks the models to display the preview
                        ],
                        'overwriteInitial' => true,
                    ],
                ]);
                ?>
                </div>
                <div class="col-md-6">

                </div>
            </div>
     

            <div class="help-block help-block-error vehcle_not_found" style="color: #a94442;"></div>



<hr>
   <div class="col-md-offset-2 col-md-8">
                       <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary invoice-button']) ?>
    </div>

           

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


