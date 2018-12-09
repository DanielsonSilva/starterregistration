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
		$session = Yii::$app->session;
		if (!$session->isActive) {
			$session->open();
		}
		$this->layout = 'registrationLayout';
        return $this->render('CustomerFormEntry', [
			'continue' => $session->has('str_firstname')
		]);
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
		try {
			// Save the Customer data
			$customerModel->attributes = [
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
			$customerId = (int) Yii::$app->db->getLastInsertId();
			// save the log data
			Yii::$app->db->createCommand('INSERT INTO log_customer (id_customer, dt_activation) VALUES (:id, :dt)', [
				':id' => $customerId,
				':dt' => date('Y-m-d H:i:s')
				])->execute();
			return $customerId;
		} catch(\Exception $e) {
    		throw $e;
		} catch(\Throwable $e) {
    		throw $e;
		}
	}

	/**
	 * Get the payment Id
	 * @return string the Payment ID
	 */
	private function getPaymentId($customerId, $owner, $iban): string
	{
		//data to send
		$dataSend = [
			'customerId' => $customerId,
			'iban' => $iban,
			'owner' => $owner
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
			Yii::$app->db->createCommand('INSERT INTO customer_payment (customer_id, id_payment, dt_payment)
			VALUES (:idcustomer, :idpayment, :dt)', [
				':idcustomer' => $customerId,
				':idpayment' => $paymentId,
				':dt' => date('Y-m-d H:i:s')
				])->execute();
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
