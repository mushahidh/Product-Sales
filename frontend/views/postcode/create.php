<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Postcode */

$this->title = Yii::t('app', 'Create Postcode');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Postcodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="postcode-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
