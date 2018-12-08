<?php

namespace app\models;

use yii\db\ActiveRecord;

class CustomerPayment extends ActiveRecord
{
	public static function tableName()
	{
        return 'customer_payment';
    }
}
