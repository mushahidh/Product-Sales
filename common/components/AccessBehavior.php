<?php

/*
 * In configuration file
 * ...
 * 'as AccessBehavior' => [
 *      'class'         => 'app\components\AccessBehavior',
 *      'allowedRoutes' => [
 *          '/',
 *          ['/user/registration/register'],
 *          ['/user/registration/resend'],
 *          ['/user/registration/confirm'],
 *          ['/user/recovery/request'],
 *          ['/user/recovery/reset']
 *      ],
 *      //'redirectUri' => '/'
 *  ],
 *  ...
 *
 * (c) Artem Voitko <r3verser@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace common\components;

use yii\base\Behavior;
use yii\console\Controller;
use yii\helpers\Url;

/**
 * Redirects all users to defined page if they are not logged in
 *
 * Class AccessBehavior
 * @package app\components
 * @author  Artem Voitko <r3verser@gmail.com>
 */
class AccessBehavior extends Behavior {

    /**
     * @var string Yii route format string
     */
    protected $redirectUri;

    /**
     * @var array Routes which are allowed to access for none logged in users
     */
    protected $allowedRoutes = [];

    /**
     * @var array Urls generated from allowed routes
     */
    protected $allowedUrls = [];

    /**
     * @param $uri string Yii route format string
     */
    public function setRedirectUri($uri) {
        $this->redirectUri = $uri;
    }

    /**
     * Sets allowedRoutes param and generates urls from defined routes
     * @param array $routes Array of allowed routes
     */
    public function setAllowedRoutes(array $routes) {
        if (count($routes)) {
            foreach ($routes as $route) {
                $this->allowedUrls[] = Url::to($route);
            }
        }
        $this->allowedRoutes = $routes;
    }

    /**
     * @inheritdoc
     */
    public function init() {
        if (empty($this->redirectUri)) {
            $this->redirectUri = \Yii::$app->getUser()->loginUrl;
        }
    }

    /**
     * Subscribe for event
     * @return array
     */
    public function events() {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * On event callback
     */
    public function beforeAction() {
        exit();
        if (\Yii::$app->getUser()->isGuest &&
                \Yii::$app->getRequest()->url !== Url::to($this->redirectUri) &&
                !in_array(\Yii::$app->getRequest()->url, $this->allowedUrls)
        ) {
            \Yii::$app->getResponse()->redirect($this->redirectUri);
        }
    }

    public static function encrypt($decrypted, $password, $salt = '!kQm*fF3pXe1Kbm%9') {
// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt . $password, true);
// Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
        srand();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22)
            return false;
// Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
// We're done!
        return $iv_base64 . $encrypted;
    }

  public static  function decrypt($encrypted, $password, $salt = '!kQm*fF3pXe1Kbm%9') {
// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt . $password, true);
// Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');
// Remove $iv from $encrypted.
        $encrypted = substr($encrypted, 22);
// Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
// Retrieve $hash which is the last 32 characters of $decrypted.
        $hash = substr($decrypted, -32);
// Remove the last 32 characters from $decrypted.
        $decrypted = substr($decrypted, 0, -32);
// Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
        if (md5($decrypted) != $hash)
            return false;
// Yay!
        return $decrypted;
    }

}
