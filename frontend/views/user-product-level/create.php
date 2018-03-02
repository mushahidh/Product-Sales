<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserProductLevel */

$this->title = Yii::t('app', 'Create User Product Level');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Product Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-product-level-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
