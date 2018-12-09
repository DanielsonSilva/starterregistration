<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \supplyhog\ClipboardJs\ClipboardJsWidget;

$entryMessage = <<<MSG
	<p class="heading">
	Success!
	</p><br />
	<p>
	We are happy to announce that everything occurred well.
	</p>
	<p>
	You can copy the Payment Identification:\t
MSG;

$inputId = "<input id='paymentId' type='text' value='$paymentId' size='120' readonly />";

$copyClipboard = ClipboardJsWidget::widget([
	'inputId' => "#paymentId",
	'cut' => false, // Cut the text out of the input instead of copy?
	'label' => '<img src=".\..\resources\ico_copyclipboard.ico" width="20px" />',
	//'htmlOptions' => ['class' => 'btn btn-info'],
	//'tag' => 'button'
]);

$entryMessage .= $inputId . $copyClipboard . "</p><br />";

$buttonGoBack = Html::a('Start Again', ['/'],['class' => 'btn btn-primary']);

$entryMessage .= "<p>$buttonGoBack</p>";

echo $entryMessage;
