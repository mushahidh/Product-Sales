<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "level_percentage".
 *
 * @property int $id
 * @property int $level_id
 * @property int $parent_id
 * @property bool $is_company_wide
 * @property string $percentage
 */
class LevelPercentage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'level_percentage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level_id', 'parent_id'], 'required'],
            [['level_id', 'parent_id'], 'integer'],
            [['is_company_wide'], 'boolean'],
            [['percentage'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level_id' => Yii::t('app', 'Level ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'is_company_wide' => Yii::t('app', 'Is Company Wide'),
            'percentage' => Yii::t('app', 'Percentage'),
        ];
    }
}
