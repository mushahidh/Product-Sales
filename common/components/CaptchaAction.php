<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

class CaptchaAction extends \yii\captcha\CaptchaAction
{
    protected function renderImage($code)
    {
        return $this->renderImageByGD($code);
    }
}
?>