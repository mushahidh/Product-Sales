<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gl".
 *
 * @property integer $ID
 * @property integer $type
 * @property double $amount
 * @property string $chart_of_accounts_code
 * @property string $department_code
 * @property string $fund_branch_code
 * @property string $product_project_code
 *
 * @property Department $departmentCode
 * @property FundBranch $fundBranchCode
 * @property ProductProject $productProjectCode
 * @property ChartOfAccounts $chartOfAccountsCode
 */
class Upload extends \common\components\db\ActiveRecord
{
    use \common\models\base\BaseFilter;
    public static $BEFORE_QUERY = [];
    public $file;
    public $uiMessage;
    public $templateUri;
    public function setUiMessage($message){
        $this->uiMessage = $message;
    }
    public function getUiMessage(){
        return $this->uiMessage;
    }
    function __construct(){
        $this->uiMessage = "Import";
        $this->templateUri = "";
    }
    public function rules()
    {
        //'extensions' => 'jpg,jpeg,doc,xls,xlsx,docx,doc,txt,csv,zip'
        //gif|jpe?g|png|doc|xls|xlsx|docx|txt|csv
        return [
           [['file'],'required']
        ];
    }
}
