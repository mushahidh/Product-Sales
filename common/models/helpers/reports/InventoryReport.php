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
class InventoryReport extends \yii\base\Model
{

	public $user;
	public $date;
	public $quantity;
	public $type;
	public $product;
	
}