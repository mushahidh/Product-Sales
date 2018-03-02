<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\models\order;
use kartik\file\FileInput;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- <h3><?php //echo Yii::t('app', 'Shipping Address') ?></h3> -->

<div class="row">
    <div class="col-md-2">
        <?= Yii::t('app', 'Name') ?>
    </div>
    <div class="col-md-10">
        <?= $form->field($model, 'name')->label(false) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-2">
        Mobile
    </div>
    <div class="col-md-10">

        <?= $form->field($model, 'mobile_no')->textInput()->label(false) ?>

    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <?= Yii::t('app', 'District') ?>
    </div>
    <div class="col-md-10">

        <?= $form->field($model, 'district')->textInput(['readonly' => true,])->label(false) ?>

    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <?= Yii::t('app', 'Province') ?>
    </div>
    <div class="col-md-10">
        <?= $form->field($model, 'province')->textInput(['readonly' => true,])->label(false) ?>
    </div>
</div>


<div class="row">
    <div class="col-md-2">
        <?= Yii::t('app', 'Address') ?>
    </div>
    <div class="col-md-10">


        <?= $form->field($model, 'address')->textInput()->label(false) ?>

    </div>
</div>

<div class="row">
    <div class="col-md-2">
        <?= Yii::t('app', 'Country') ?>
    </div>
    <div class="col-md-10">
        <?= $form->field($model, 'country')->textInput(['readonly' => true, 'value' => 'Thailand'])->label(false) ?>
    </div>
</div>

