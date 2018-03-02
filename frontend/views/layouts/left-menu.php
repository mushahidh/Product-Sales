<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php $this->beginContent('@app/views/layouts/topbar.php'); ?>
            <?php $this->endContent(); ?>

    <div class="page-container row-fluid">
    <?php  if (!Yii::$app->user->isGuest) {?>
   <?php $this->beginContent('@app/views/layouts/sidebar.php'); ?>
            <?php $this->endContent(); 
    }
            ?>
            <section id="main-content">
                 <section class="wrapper main-wrapper" style=''>
                 <?php
    //$user_id = Yii::$app->user->getId();
   // $Role =   Yii::$app->authManager->getRolesByUser($user_id);
  //  if(isset($Role['super_admin'])){?>
     <div class="col-md-12">
        <div id="manager-menu" class="list-group">
        <?php
$controller = $this->context;
$menus = $controller->module->menus;
$route = $controller->route;
foreach ($menus as $i => $menu) {
    $menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
}
$this->params['nav-items'] = $menus;
?>
            <?php

            foreach ($menus as $menu) {
                $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                    Html::tag('span', Html::encode($menu['label']), []);
                $active = $menu['active'] ? ' active' : '';
                echo Html::a($label, $menu['url'], [
                    'class' => 'list-group-item' . $active,
                ]);
            }
            ?>
        </div>
    </div>
        <?php // } ?>

        <?= $content ?>
 </section>
            </section>
               <?php $this->beginContent('@app/views/layouts/rightside.php'); ?>
            <?php $this->endContent(); ?>

    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
