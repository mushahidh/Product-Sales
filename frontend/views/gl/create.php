<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Gl */

$this->title = Yii::t('app', 'Create Gl');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
