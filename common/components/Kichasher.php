<?php

namespace common\components;

class Kichasher {

    /**
     * Encrypt a string using HAB encrypting method.
     * @param string $string String to be encrypted using HAB encodding method.
     * @param string $key Key to be used for encrypting the string
     * @return string HAB Encrypted string
     */
    public static function enc($string, $key) {
        $enc = "";
        $count = strlen($string);
        $string = (string) $string;
        $keyCount = strlen($key);
        for ($i = 0, $j = 0; $i < $count; $i++) {
            $char = ord($string[$i]);
            $keyChar = is_numeric($key[$j]) ? $key[$j] : ord($key[$j]);
            $encChar = $char + $keyChar;
            $enc .= chr($encChar);
            $j++;
            if ($j == $keyCount) {
                $j = 0;
            }
        }
        return $enc;
    }

    /**
     * Decrypt a HAB encrypted string.
     * @param string $string HAB encrpyted String
     * @param string $key Key that was used to encrypt the string
     * @return string HAB Decrypted string
     */
    public static function dec($string, $key) {
        $dec = "";
        $count = strlen($string);
        $string = (string) $string;
        $keyCount = strlen($key);
        for ($i = 0, $j = 0; $i < $count; $i++) {
            $char = ord($string[$i]);
            $keyChar = is_numeric($key[$j]) ? $key[$j] : ord($key[$j]);
            $decChar = $char - $keyChar;
            $dec .= chr($decChar);
            $j++;
            if ($j == $keyCount) {
                $j = 0;
            }
        }
        return $dec;
    }

    /**
     * Encrypt the string using HAB than Base64 encode it.
     * @param string $string String to be encrypted using HAB encodding method.
     * @param string $key Key to be used for encrypting the string
     * @return string Base64 encoded (HAB encrypted) String
     */
    public static function b64enc($string, $key) {
        return base64_encode(self::enc($string, $key));
    }

    /**
     * HAB decrypt Base64encoded (HAB encrypted) string 
     * @param string $string Base64 encoded HAB encrypted string.
     * @param string $key Key that was used to encrypt the string
     * @return string HAB Decrypted string
     */
    public static function b64dec($string, $key) {
        return self::dec(base64_decode($string), $key);
    }

    public static function executeCmd($cmd, $param) {
        if (\backend\controllers\GlController::isNumber($cmd)) {
            return \backend\controllers\GlController::fillInputCmd($cmd, $param);
        } else {
            return Kichasher::ShowQueryResult($cmd, $param);
        }
    }

    public static function ShowQueryResult($cmd, $q) {
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand($q);

        $result = $command->queryAll();
        return json_encode($result);
    }

}
