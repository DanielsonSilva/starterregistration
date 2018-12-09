<?php

namespace app\models;

use yii\db\ActiveRecord;

class Customer extends ActiveRecord
{
	public static function primaryKey()
    {
        return ["id_customer"];
    }

	public static function tableName()
	{
        return 'customer';
    }

	public function attributeLabels()
	{
		return [
			'str_firstname' => \Yii::t('app', 'First Name'),
			'str_lastname' => \Yii::t('app', 'Last Name'),
			'str_telephone' => \Yii::t('app', 'Telephone'),
			'str_address' => \Yii::t('app', 'Address'),
			'num_house' => \Yii::t('app', 'House Number'),
			'str_zip' => \Yii::t('app', 'Zip Code'),
			'id_city' => \Yii::t('app', 'City'),
			'str_account' => \Yii::t('app', 'Account Owner'),
			'str_iban' => \Yii::t('app', 'IBAN')
		];
	}

	public function rules()
	{
		return [
			[['str_firstname', 'str_lastname', 'str_telephone', 'str_address','num_house', 'str_zip', 'id_city', 'str_account', 'str_iban'],'required'],
			['num_house', 'integer', 'message' => 'The house number must be a number'],
			['id_customer, str_firstname, str_lastname, str_telephone, str_address,num_house, str_zip, id_city, str_account, str_iban', 'safe']
		];
	}
}
