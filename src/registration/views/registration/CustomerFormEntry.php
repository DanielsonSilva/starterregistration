<?php
use yii\helpers\Html;
use yii\helpers\Url;

// name of the button to proceed
if ($continue) {
	$nameButton = "Continue Registration";
	$nameDescription = "continue";
} else {
	$nameButton = "Start Registration";
	$nameDescription = "start";
}

$entryMessage = <<<MSG
	<p class="heading">
	Welcome!
	</p><br />
	<p>
	We are happy to offer our services for you.
	</p>
	<p>
	Please, proceed to $nameDescription the registration.
	</p><br />
MSG;

$buttonProceed = Html::a($nameButton, ['/registration/customerform'],['class' => 'btn btn-primary']);

$entryMessage .= $buttonProceed;

echo $entryMessage;
