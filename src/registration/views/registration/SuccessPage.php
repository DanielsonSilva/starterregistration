<?php
use yii\helpers\Html;
use yii\helpers\Url;

$entryMessage = <<<MSG
	<p class="heading">
	Success!
	</p><br />
	<p>
	We are happy to announce that everything ocurred well.
	</p>
	<p>
	You can copy the Payment Identification: $paymentId
	</p><br />
MSG;

echo $entryMessage;
