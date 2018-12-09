<?php

namespace app\controllers;

use Yii;
use app\models\City;
use app\models\{Customer, CustomerPayment, LogCustomer};
use yii\base\Controller;

class RegistrationController extends Controller
{
	public function accessRules()
	{
		return [
			[
				'allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','customerform'),
                'users'=>array('*'),
            ]
		];
	}
	/**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorRegistration',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'wunderfleet' : null,
            ],
        ];
    }

	/**
     * Displays homepage.
     * @return string view from the welcome page to registration
     */
    public function actionIndex()
    {
		// $session = Yii::$app->session;
		// if (!$session->isActive) {
		// 	$session->open();
		// }
		// $session->destroy();
		$this->layout = 'registrationLayout';
        return $this->render('CustomerFormEntry');
    }

	/**
	 * Proceeds to the first part of the registration
	 * @return string view from the first page of registration
	 */
	public function actionCustomerform(): string
	{
		$this->layout = 'registrationLayout';
		$post = Yii::$app->request->post();
		$session = Yii::$app->session;
		if (!$session->isActive) {
			$session->open();
		}
		$customerModel = new Customer();

		if (isset($post['Customer'])) {
			foreach($post['Customer'] as $key => $data) {
				$session->set($key, $data);
			}
		}
		// Check if the first form has been submited
		if ($session->has('str_firstname')) {
			// check if the second form has been submited
			if ($session->has('str_address')) {
				// check if the final form has been submited
				if ($session->has('str_account')) {
					// save the customer to the database and log it
					$customerId = $this->saveCustomer();
					// Get the PaymentId from API
					$paymentId = $this->getPaymentId($customerId, $session->get('str_account'), $session->get('str_iban'));
					// Store the PaymentId
					$this->savePayment($customerId, $paymentId);
					// Destroy the data from session
					$session->destroy();
					// Return the Success Page
					return $this->successPage($paymentId);
				} else {
					// process final form
					return $this->finalForm($customerModel);
				}
			} else {
				// process the second form
				return $this->secondForm($customerModel);
			}
		} else {
			// process the first form
			return $this->render('CustomerFirstForm', [
				'customerModel' => $customerModel
			]);
		}
	}

	/**
	 * Proceeds to the second part of the registration
	 * @return string view from the second page of registration
	 */
	private function secondForm($customerModel): string
	{
		$this->layout = 'registrationLayout';
		$cityModel = new City();

        return $this->render('CustomerSecondForm', [
			'customerModel' => $customerModel,
			'cityModel' => $cityModel
		]);
	}

	/**
	 * Proceeds to the final part of the registration
	 * @return string view from the final page of registration
	 */
	private function finalForm($customerModel): string
	{
		$this->layout = 'registrationLayout';
		$cityModel = new City();

        return $this->render('CustomerFinalForm', [
			'customerModel' => $customerModel
		]);
	}

	/**
	 * Save the customer to the database and log it
	 * @return int The primary key assigned to the saved data
	 */
	private function saveCustomer(): int
	{
		// Models
		$customerModel = new Customer();
		$logCustomerModel = new LogCustomer();
		// Session
		$session = Yii::$app->session;
		if (!$session->isActive) {
			$session->open();
		}
		// Start Transaction
		$transaction = Customer::getDb()->beginTransaction();
		try {
			// Save the Customer data
			$customerModel->attributes = [
				'id_customer' => null,
				'str_firstname' => $session->get('str_firstname'),
				'str_lastname' => $session->get('str_lastname'),
				'str_telephone' => $session->get('str_telephone'),
				'str_address' => $session->get('str_address'),
				'num_house' => $session->get('num_house'),
				'str_zip' => $session->get('str_zip'),
				'id_city' => $session->get('id_city'),
				'str_account' => $session->get('str_account'),
				'str_iban' => $session->get('str_iban')
			];
			$customerModel->save();
			//save the log
			$logCustomerModel->attributes = [
				'id_customer' => $customerModel->getPrimaryKey(),
				'dt_activation' => date('Y-m-d H:i:s')
			];
			$logCustomerModel->save();
			$transaction->commit();
			$customerModel->refresh();
			var_dump($customerModel->getAttributes());die();
			return $primaryKey;
		} catch(\Exception $e) {
    		$transaction->rollBack();
    		throw $e;
		} catch(\Throwable $e) {
    		$transaction->rollBack();
    		throw $e;
		}
	}

	/**
	 * Get the payment Id
	 * @return string the Payment ID
	 */
	private function getPaymentId($customerId, $iban, $owner): string
	{
		//data to send
		$dataSend = [
			'customerId' => $customerId,
			'iban' => $iban,
			'owner' =>$owner
		];
		//The url to send the request
		$url = "https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data";

		//url-ify the data for the POST
		$fields_string = json_encode($dataSend);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars and POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($dataSend));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		// Return the contents of the cURL
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

		// execute post
		$result = curl_exec($ch);
		$resultDecoded = json_decode($result);
		$paymentId = $resultDecoded->paymentDataId;

		return $paymentId;
	}

	/**
	 * Save the payment data
	 * @return bool True if all goes weel and False otherwise
	 */
	private function savePayment($customerId, $paymentId): bool
	{
		try {
			$customerPayment = new CustomerPayment();
			$customerPayment->attributes = [
				'customer_id' => $customerId,
				'id_payment' => $paymentId,
				'dt_payment' => date('Y-m-d H:i:s')
			];
			$customerPayment->save();
			return true;
		} catch(\Exception $e) {
    		return false;
		}
	}

	/**
	 * Show the Success Page to the user
	 * @return string Success Page HTML code
	 */
	private function successPage($paymentId): string
	{
		$this->layout = 'registrationLayout';

        return $this->render('SuccessPage', [
			'paymentId' => $paymentId
		]);
	}
}
