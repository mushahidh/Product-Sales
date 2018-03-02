<?php
namespace common\models\helpers;

use Yii;

class ArrayUtils
{
    public static function MaxAttributeInAssociativeArray($array, $index)
    {
        $max_value = 0;
        $max_index=0;
        foreach( $array as $k => $v )
        {
          if($v[$index]>$max_value)
          {
            $max_value=$v[$index];
            $max_index=$k;
          }
        }
        return $max_index;
    }
}