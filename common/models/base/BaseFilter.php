<?php

namespace common\models\base;

trait BaseFilter {

    public static function find() {
        $obj = new static;
        $class = new \ReflectionClass($obj);
        $condition = [];
        $condition2 = ['=',self::tableName().'.application_ID',\Yii::$app->session['app_ID']];
        foreach ($class->getProperties(\ReflectionProperty::IS_STATIC) as $property) {
            if (strpos($property->getName(), 'BEFORE_QUERY') !== false && is_array($property->getValue($obj))) {
                $condition = array_merge($condition2,array_merge($condition, $property->getValue($obj)));
            }
            return parent::find()->andWhere($condition);
        }
    }

}
