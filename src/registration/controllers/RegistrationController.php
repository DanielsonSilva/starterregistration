<?php

namespace app\controllers;

use Yii;
use app\models\City;
use app\models\Customer;
use app\models\CustomerPayment;
use app\models\LogCustomer;
use yii\base\Controller;

class RegistrationController extends Controller
{
	public function actionCustomer()
	{
		$customer = City::findOne(45);

		echo '<pre>' . $customer->id_city . ' ' . $customer->str_city;
	}
}
