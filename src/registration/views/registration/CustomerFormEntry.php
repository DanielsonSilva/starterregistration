<?php
use yii\helpers\Html;
use yii\helpers\Url;

$entryMessage = <<<MSG
	<p class="heading">
	Welcome!
	</p><br />
	<p>
	We are happy to offer our services for you.
	</p>
	<p>
	Please, proceed to start the registration.
	</p><br />
MSG;

// name of the button to proceed
if ($continue) {
	$nameButton = "Continue Registration";
} else {
	$nameButton = "Start Registration";
}
$buttonProceed = Html::a($nameButton, ['/registration/customerform'],['class' => 'btn btn-primary']);

echo $entryMessage . $buttonProceed;
