<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "postcode".
 *
 * @property int $id
 * @property string $province
 * @property string $district
 * @property string $zip
 */
class Postcode extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'postcode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province', 'district', 'zip','id','sr','company_id', 'branch_id'], 'required'],
            [['province', 'district', 'zip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'province' => Yii::t('app', 'Province'),
            'district' => Yii::t('app', 'District'),
            'zip' => Yii::t('app', 'Zip'),
        ];
    }
    public static function getCodes($q){
        $out = ['results' => ['id' => '', 'text' => '']];
        $query = new \yii\db\Query();
        $query->select(['zip as id, CONCAT(province, "-", district, "-", zip) AS text'])
                ->from('postcode');
                if (!is_null($q)) 
                    $query->where(['like', 'zip', $q]);
                $query->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);
        return $out;
       
    }
    public function beforeValidate()
    {
        $action = Yii::$app->controller->action->id;
        if (parent::beforeValidate()) {
            if ($action == 'create' || $action == 'import' ) {
                $companyId = Yii::$app->user->identity->company_id;
                $branchId = Yii::$app->user->identity->branch_id;
                $this->id = \common\components\Constants::GUID();
                $this->sr = \common\components\Constants::nextSr(Yii::$app->db, \common\models\Postcode::tableName(), $companyId);
                $this->company_id = $companyId;
                $this->branch_id = $branchId;
            }
            return true;
        }
    }
}
