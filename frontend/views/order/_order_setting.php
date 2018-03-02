<!-- <h3><?php //echo Yii::t('app', 'ORDER SETTING');?></h3> -->
<?php
if ($type == "Order") {
    echo  Yii::$app->controller->renderPartial('_customer_order', [
        'model' => $model,
        'form' => $form,
        'user_id' => $user_id,
        'Role' => $Role,
    ]);

} else if($type == "Request" || $type == "Return"){
    echo Yii::$app->controller->renderPartial('_agent_request', [
        'model' => $model,
        'form' => $form,
        'user_id' => $user_id,
        'Role' => $Role,
    ]);
}
else
{
    echo Yii::$app->controller->renderPartial('_transfer_request', [
        'model' => $model,
        'form' => $form,
        'user_id' => $user_id,
        'Role' => $Role,
    ]);
}
?>

