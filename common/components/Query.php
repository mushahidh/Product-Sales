<?php

namespace common\components;
use Yii;
use yii\db\Query as BaseQuery;

class Query extends BaseQuery {

    /**
     * 
     * @param type $condition
     * @param type $params
     * @return $this
     */

/***************** */
public function where( $condition , $params = array() ) {
    parent::where ( $condition , $params );

    $defaultConditionEmpty = !isset ( $this->where[$this->from[0] . '.company_id'] );

    if ( $defaultConditionEmpty ) {
        if ( is_array ( $this->where ) && isset ( $this->where[0] ) && strcasecmp ( $this->where[0] , 'and' ) === 0 ) {
            $this->where = array_merge ( $this->where , [ [ $this->from[0] . '.company_id' => Yii::$app->user->identity->company_id  ] , [ $this->from[0] . '.branch_id' => Yii::$app->user->identity->branch_id ] ] );
        } else {
            $this->where = [ 'and' , $this->where , [ $this->from[0] . '.company_id' => Yii::$app->user->identity->company_id  ] , [ $this->from[0] . '.branch_id' => Yii::$app->user->identity->branch_id  ] ];
        }
    }
    return $this;
}

/**
 * 
 * @param type $tables
 * @return $this
 */
public function from( $tables ) {
    parent::from ( $tables );
    $this->addDefaultWhereCondition ();

    return $this;
}

/**
 * Private method to add the default where clause 
 */
private function addDefaultWhereCondition() {
    if ( $this->from !== null ) {

        $this->where = [ 'and' ,
            [ $this->from[0] . '.company_id' => Yii::$app->user->identity->company_id  ] , [ $this->from[0] . '.branch_id' => Yii::$app->user->identity->branch_id  ]
        ];
    }
}
}