<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\QuantityBasedPricing */

$this->title = Yii::t('app', 'Create Quantity Based Pricing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quantity Based Pricings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quantity-based-pricing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
