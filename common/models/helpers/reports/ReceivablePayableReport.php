<?php

namespace common\models\helpers\reports;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property Product[] $products
 */
class ReceivablePayableReport extends \yii\base\Model
{

	public $user;
	public $date;
	public $type;
	public $amount;
	public $order_id;
	
}