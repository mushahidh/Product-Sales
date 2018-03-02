<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StockOut */

$this->title = Yii::t('app', 'Create Stock Out');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stock Outs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-out-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
