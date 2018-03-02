<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

class Profile extends \yii\bootstrap\Widget {

    public static function widget($config = []) {
        return \Yii::$app->view->render('@common/components/views/profile/'.$config['file']);
    }

}
