<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', 'Create Recieveable');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recieveables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1>Recieveables</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
