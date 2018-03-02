<img src="<?= Yii::$app->request->baseUrl; ?>/store/<?= \Yii::$app->session['logo'] ?>" onError="this.onerror=null;this.src='/images/no-image.png';" style="width:40px;height:35px;padding: 5px;" />
<?php
if (isset($this->context->module->controller))
    $layout = $this->context->module->controller->layout;
else {
    $layout = 'main';
}
?>
<?php if (Yii::$app->user->can('Financial Management',null,null,true)) { ?>
    <a href="/site/ap" class="<?= $layout == 'main' ? 'active-menu' : "" ?> btn btn-brand-primary-invert">
        <span class="glyphicon glyphicon-credit-card top-i"></span>
        Financial Management
    </a>
<?php } ?>
<?php if (Yii::$app->user->can('Supply Chain Management',null,null,true)) { ?>
    <a href="/site/supply"  class="<?= $layout == 'supply' ? 'active-menu' : "" ?> btn btn-brand-primary-invert">
        <span class="glyphicon glyphicon-globe top-i"></span>
        Supply Chain Management
    </a>
<?php } ?>
<?php if (Yii::$app->user->can('Financial Institution Management',null,null,true)) { ?>
    <a href="/site/society" style="" class="<?= $layout == 'bank' ? 'active-menu' : "" ?> btn btn-brand-primary-invert">
        <span class="glyphicon glyphicon-briefcase top-i"></span>
        Financial Institution Management
    </a>
<?php } ?>
<?php if (Yii::$app->user->can('School Management',null,null,true)) { ?>
    <a href="/site/school" style="" class="<?= $layout == 'school' ? 'active-menu' : "" ?> btn btn-brand-primary-invert">
        <span class="glyphicon glyphicon-book top-i"></span>
        School Management</a>
<?php } ?>
<?php if (Yii::$app->user->can('HRM',null,null,true)) { ?>
    <a href="/site/hrm" style="" class="<?= $layout == 'hrm' ? 'active-menu' : "" ?> btn btn-brand-primary-invert">
        <span class="glyphicon glyphicon-user top-i"></span>
        Human Resource Management
    </a>
<?php } ?>