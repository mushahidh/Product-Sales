<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create" style="margin-bottom-20px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_customer', [
        'model' => $model,
    ]) ?>

</div>
