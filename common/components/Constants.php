<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

/**
 * Description of Constants
 *
 * @author k
 */
class Constants {

    public static function nextSr($db, $entity, $app, $branch = NULL) {
        if ($branch) {
            $q = "select * from " . $entity . " where application_ID='" . $app . "' and branch_ID='" . $branch . "' order by sr DESC";
        } else {
            $q = "select * from " . $entity . " where application_ID='" . $app . "' order by sr DESC";
        }
        $command = $db->createCommand($q);
        $id = $command->queryOne();

        if ($id) {
            return $id['sr'] + 1;
        } else {
            return 1;
        }
    }

    public static function nextSrGroup($db, $entity, $app, $branch = NULL, $group, $student) {
        if ($branch) {
            $command = $db->createCommand("select max(sr) as sr,fee_collection_ID,student_ID from " . $entity . " where application_ID='" . $app . "' and fee_collection_ID='" . $group . "' and branch_ID='" . $branch . "' group by student_ID,fee_collection_ID order by sr DESC");
        } else {
            $command = $db->createCommand("select max(sr) as sr,fee_collection_ID,student_ID from " . $entity . " where application_ID='" . $app . "' and fee_collection_ID='" . $group . "' group by student_ID,fee_collection_ID order by sr DESC");
        }
        $id = $command->queryOne();
        if ($id) {
            if ($id['student_ID'] == $student) {
                return $id['sr'];
            } else {
                return $id['sr'] + 1;
            }
        } else {
            return 1;
        }
    }
    public static function logNotification($attrib,$entity,$changedattributes=NULL){
        //$class = get_class($attrib);
        $template = \common\models\NotificationTemplates::findOne(['notification_source'=>$entity]);
        if($template){
            $notification = new \common\models\Notification();
            $message_raw = $template->notification_template;
            //$matches = [];
            preg_match_all("/\<<(.*?)\>>/", $message_raw, $matches);
            if(isset($matches[1])){
                foreach($matches[1] as $key=>$m){
                    $message_raw = str_replace($matches[0][$key],$attrib[$m],$message_raw);
                }
            }
            //var_dump($message_raw);
            $notification->message = $message_raw;
            $notification->notification_type = $template->notification_type;
            $string = $template->send_to_field;
            $array = explode(',',$string);
            $query = (new \yii\db\Query())->from($array[0])->where(['=',$array[2],$attrib[$array[1]]])->one();
            if( isset($array[3]) && !empty($query[$array[3]])){
                $notification->notification_phone = $query[$array[3]];
                if($notification->save()){
    
                }else{
                    var_dump($notification->getErrors());
                }
            }
        }
        
    }

    public static function logGlobal($db, $entity, $attributes, $action) {

        $attrib['ID'] = \common\components\Constants::GUID();
        $attrib['type'] = $entity;
        $attrib['application_ID'] = \Yii::$app->session['app_ID'];
        $attrib['branch_ID'] = \Yii::$app->session['branch_ID'];
        $attrib['user_id'] = \Yii::$app->user->ID;
        $attrib['timestamp'] = $attributes['action_on'];
        $attrib['action'] = $action;
        $attrib['ref_key'] = $attributes['PK'];
        
        $db->createCommand()->insert('global_audit', $attrib)->execute();
    }

    public static function logGlobalInsert($db, $entity, $attributes, $permission = NULL, $user = NULL) {
        $attrib['ID'] = \common\components\Constants::GUID();
        $attrib['type'] = $entity;
        $attrib['application_ID'] = \Yii::$app->session['app_ID'];
        $attrib['branch_ID'] = \Yii::$app->session['branch_ID'];
        $attrib['user_id'] = \Yii::$app->user->ID;
        $attrib['timestamp'] = date("Y-m-d H:i:s");
        if (empty($permission)) {
            $attrib['action'] = 'Add';
        } else {
            $attrib['action'] = 'Assigned Permission' . json_encode($permission) . " to " . $user->username;
        }

        if (isset($attributes->ID)) {
            $attrib['ref_key'] = $attributes->ID;
        }
        
        $db->createCommand()->insert('global_audit', $attrib)->execute();
    }

    public static function logDelete($db, $entity, $attributes, $permission = NULL, $user = NULL) {
        $attributes['PK'] = \common\components\Constants::GUID();
        $attributes['action'] = 'delete';
        $attributes['action_by'] = \Yii::$app->user->ID;
        $attributes['action_application_ID'] = \Yii::$app->session['app_ID'];
        $attributes['action_branch_ID'] = \Yii::$app->session['branch_ID'];
        $attributes['action_on'] = date("Y-m-d H:i:s");
        $entity = $entity . "_audit";
        $db->createCommand()->insert($entity, $attributes)->execute();
        if (empty($permission)) {
            self::logGlobal($db, $entity, $attributes, 'Delete');
        } else {
            $action = 'Revoke Permission' . json_encode($permission) . " from " . $user->username;
            self::logGlobal($db, $entity, $attributes, $action);
        }
    }

    public static function log($db, $entity, $attributes, $changedattributes) {
        $attributes['PK'] = \common\components\Constants::GUID();
        foreach ($changedattributes as $key => $value) {
            $attributes[$key] = $value;
        }
        $attributes['action'] = 'update';
        $attributes['action_by'] = \Yii::$app->user->ID;
        $attributes['action_application_ID'] = \Yii::$app->session['app_ID'];
        $attributes['action_branch_ID'] = \Yii::$app->session['branch_ID'];
        $attributes['action_on'] = date("Y-m-d H:i:s");
        $entity = $entity . "_audit";
        $db->createCommand()->insert($entity, $attributes)->execute();
        self::logNotification($attributes,$entity,$changedattributes);
        self::logGlobal($db, $entity, $attributes, 'Update');
    }

    public static function logInsert($db, $entity, $attributes, $permission = NULL, $user = NULL) {
        self::logNotification($attributes,$entity);
        self::logGlobalInsert($db, $entity, $attributes, $permission, $user);
    }

    public static function getApp($db, $user_id) {
        if ($user_id) {
            $command = $db->createCommand("select * from [user] where id=" . $user_id);
            $id = $command->queryOne()['application_ID'];
            self::$app_id = $id;
            return \common\models\Application::findOne(1);
        } else {
            return null;
        }
    }

    public static function setAppAndBranchRemoved($db, $user_id) {
        if ($user_id) {
            $command = $db->createCommand("select * from " . \common\models\User::tableName() . " where id='" . $user_id . "'");
            $id = $command->queryOne();
            if ($id) {
                $app = \common\models\Application::findOne($id['application_ID']);
                \Yii::$app->session['app_ID'] = $id['application_ID'];
                \Yii::$app->session['branch_ID'] = $id['branch_ID'];
                \Yii::$app->session['username'] = $id['username'];

                \Yii::$app->session['logo'] = $app->logo;
                \Yii::$app->session['name'] = $app->name;
                \Yii::$app->session['address'] = $app->address;
                \Yii::$app->session['organization_phone'] = $app->organization_phone;
            }
        }
    }

    public static function setAppAndBranch($db, $user_id) {
        global $voice;
        global $sajil;
        if (class_exists('DOTNET')) {
            $sajil = new \DOTNET("sajil,"
                    . "Version=1.0.0.0,"
                    . "Culture=neutral,"
                    . "PublicKeyToken=fe5acdc6e27e4bfb"
                    , "sajil.Class1");
        } else {
//exec('regsvr32 /s whatever.dll');
            $sajil = false;
        }
        if ($sajil && $user_id) {
            $voice = new \COM("SAPI.SpVoice");
            $voice->Speak("Welcome! Please wait.");
            $sajil->SayHello($user_id);
            \Yii::$app->session['url'] = "http://sajil.dev:8080";
            \Yii::$app->session['app_ID'] = $sajil->app;
            \Yii::$app->session['branch_ID'] = $sajil->branch;
            \Yii::$app->session['organization_phone'] = $sajil->organization_phone;
            \Yii::$app->session['logo'] = $sajil->logo;
            \Yii::$app->session['name'] = $sajil->name;
            \Yii::$app->session['username'] = $sajil->username;
            \Yii::$app->session['address'] = $sajil->address;
        } else {
            if ($user_id) {
                $command = $db->createCommand("select * from " . \common\models\User::tableName() . " where id='" . $user_id . "'");
                $id = $command->queryOne();
                if ($id) {
                    $app = \common\models\Application::findOne(['ID' => $id['application_ID']]);
                    \Yii::$app->session['app_ID'] = $id['application_ID'];
                    \Yii::$app->session['branch_ID'] = $id['branch_ID'];
                    \Yii::$app->session['username'] = $id['username'];

                    \Yii::$app->session['email'] = $app->email;
                    \Yii::$app->session['organization_phone'] = $app->organization_phone;
                    \Yii::$app->session['logo'] = $app->logo;
                    \Yii::$app->session['name'] = $app->name;
                    \Yii::$app->session['address'] = $app->address;
                }
            }
        }
    }

    public static function daysBetweenDates($to, $from) {
        $to = new \DateTime($to);
        $from = new \DateTime($from);
        return $from->diff($to);
    }

    public static function LicenseMessage() {
        return \Yii::$app->response->redirect('/index.php/app/license');
        
    }

    public static function MushNoKo() {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, base64_decode(\common\models\Lookup::$fareed));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $content = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            
        }
        return $content;
    }

    public static function LogKo() {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, base64_decode(\common\models\Lookup::$cent));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $content = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            
        }
        return $content;
    }

    public static function GUID() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    public static $app_logo = "http://societies.dev:8080/images/logo.png";
    public static $timezones = array(
        'Pacific/Midway' => "(GMT-11:00) Midway Island",
        'US/Samoa' => "(GMT-11:00) Samoa",
        'US/Hawaii' => "(GMT-10:00) Hawaii",
        'US/Alaska' => "(GMT-09:00) Alaska",
        'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
        'America/Tijuana' => "(GMT-08:00) Tijuana",
        'US/Arizona' => "(GMT-07:00) Arizona",
        'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
        'America/Chihuahua' => "(GMT-07:00) Chihuahua",
        'America/Mazatlan' => "(GMT-07:00) Mazatlan",
        'America/Mexico_City' => "(GMT-06:00) Mexico City",
        'America/Monterrey' => "(GMT-06:00) Monterrey",
        'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
        'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
        'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
        'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
        'America/Bogota' => "(GMT-05:00) Bogota",
        'America/Lima' => "(GMT-05:00) Lima",
        'America/Caracas' => "(GMT-04:30) Caracas",
        'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
        'America/La_Paz' => "(GMT-04:00) La Paz",
        'America/Santiago' => "(GMT-04:00) Santiago",
        'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
        'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
        'Greenland' => "(GMT-03:00) Greenland",
        'Atlantic/Stanley' => "(GMT-02:00) Stanley",
        'Atlantic/Azores' => "(GMT-01:00) Azores",
        'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
        'Africa/Casablanca' => "(GMT) Casablanca",
        'Europe/Dublin' => "(GMT) Dublin",
        'Europe/Lisbon' => "(GMT) Lisbon",
        'Europe/London' => "(GMT) London",
        'Africa/Monrovia' => "(GMT) Monrovia",
        'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
        'Europe/Belgrade' => "(GMT+01:00) Belgrade",
        'Europe/Berlin' => "(GMT+01:00) Berlin",
        'Europe/Bratislava' => "(GMT+01:00) Bratislava",
        'Europe/Brussels' => "(GMT+01:00) Brussels",
        'Europe/Budapest' => "(GMT+01:00) Budapest",
        'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
        'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
        'Europe/Madrid' => "(GMT+01:00) Madrid",
        'Europe/Paris' => "(GMT+01:00) Paris",
        'Europe/Prague' => "(GMT+01:00) Prague",
        'Europe/Rome' => "(GMT+01:00) Rome",
        'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
        'Europe/Skopje' => "(GMT+01:00) Skopje",
        'Europe/Stockholm' => "(GMT+01:00) Stockholm",
        'Europe/Vienna' => "(GMT+01:00) Vienna",
        'Europe/Warsaw' => "(GMT+01:00) Warsaw",
        'Europe/Zagreb' => "(GMT+01:00) Zagreb",
        'Europe/Athens' => "(GMT+02:00) Athens",
        'Europe/Bucharest' => "(GMT+02:00) Bucharest",
        'Africa/Cairo' => "(GMT+02:00) Cairo",
        'Africa/Harare' => "(GMT+02:00) Harare",
        'Europe/Helsinki' => "(GMT+02:00) Helsinki",
        'Europe/Istanbul' => "(GMT+02:00) Istanbul",
        'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
        'Europe/Kiev' => "(GMT+02:00) Kyiv",
        'Europe/Minsk' => "(GMT+02:00) Minsk",
        'Europe/Riga' => "(GMT+02:00) Riga",
        'Europe/Sofia' => "(GMT+02:00) Sofia",
        'Europe/Tallinn' => "(GMT+02:00) Tallinn",
        'Europe/Vilnius' => "(GMT+02:00) Vilnius",
        'Asia/Baghdad' => "(GMT+03:00) Baghdad",
        'Asia/Kuwait' => "(GMT+03:00) Kuwait",
        'Africa/Nairobi' => "(GMT+03:00) Nairobi",
        'Asia/Riyadh' => "(GMT+03:00) Riyadh",
        'Europe/Moscow' => "(GMT+03:00) Moscow",
        'Asia/Tehran' => "(GMT+03:30) Tehran",
        'Asia/Baku' => "(GMT+04:00) Baku",
        'Europe/Volgograd' => "(GMT+04:00) Volgograd",
        'Asia/Muscat' => "(GMT+04:00) Muscat",
        'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
        'Asia/Yerevan' => "(GMT+04:00) Yerevan",
        'Asia/Kabul' => "(GMT+04:30) Kabul",
        'Asia/Karachi' => "(GMT+05:00) Karachi",
        'Asia/Tashkent' => "(GMT+05:00) Tashkent",
        'Asia/Kolkata' => "(GMT+05:30) Kolkata",
        'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
        'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
        'Asia/Almaty' => "(GMT+06:00) Almaty",
        'Asia/Dhaka' => "(GMT+06:00) Dhaka",
        'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
        'Asia/Bangkok' => "(GMT+07:00) Bangkok",
        'Asia/Jakarta' => "(GMT+07:00) Jakarta",
        'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
        'Asia/Chongqing' => "(GMT+08:00) Chongqing",
        'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
        'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
        'Australia/Perth' => "(GMT+08:00) Perth",
        'Asia/Singapore' => "(GMT+08:00) Singapore",
        'Asia/Taipei' => "(GMT+08:00) Taipei",
        'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
        'Asia/Urumqi' => "(GMT+08:00) Urumqi",
        'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
        'Asia/Seoul' => "(GMT+09:00) Seoul",
        'Asia/Tokyo' => "(GMT+09:00) Tokyo",
        'Australia/Adelaide' => "(GMT+09:30) Adelaide",
        'Australia/Darwin' => "(GMT+09:30) Darwin",
        'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
        'Australia/Brisbane' => "(GMT+10:00) Brisbane",
        'Australia/Canberra' => "(GMT+10:00) Canberra",
        'Pacific/Guam' => "(GMT+10:00) Guam",
        'Australia/Hobart' => "(GMT+10:00) Hobart",
        'Australia/Melbourne' => "(GMT+10:00) Melbourne",
        'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
        'Australia/Sydney' => "(GMT+10:00) Sydney",
        'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
        'Asia/Magadan' => "(GMT+12:00) Magadan",
        'Pacific/Auckland' => "(GMT+12:00) Auckland",
        'Pacific/Fiji' => "(GMT+12:00) Fiji",
    );

}
