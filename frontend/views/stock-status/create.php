<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StockStatus */

$this->title = Yii::t('app', 'Create Stock Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stock Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
