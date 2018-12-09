<?php

namespace app\models;

use yii\db\ActiveRecord;

class LogCustomer extends ActiveRecord
{

	public static function tableName()
	{
        return 'log_customer';
    }

	public static function primaryKey() {
		return ['id_customer'];
	}
}
