<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
echo $form->field($model, 'account_type')->widget(Select2::classname(), [
        'data' => common\models\Lookup::$account_types,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a Status  ...'],
        //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
        ],

    ]);
    ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'account_description')->textInput(['maxlength' => true]) ?>
<?php
 echo $form->field($model, 'user_id')->widget(Select2::classname(), [
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder' => 'Select a Parent User ...'],
    'pluginOptions' => [
        'allowClear' => true,
        //'autocomplete' => true,
        'ajax' => [
            'url' => Url::base() . '/user/get-users',
            'dataType' => 'json',
            'data' => new \yii\web\JsExpression('function(params) {
    return {
        q:params.term};
    }'),
        ],
    ],
]);


?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
