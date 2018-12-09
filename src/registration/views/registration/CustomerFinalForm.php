<?php
use yii\Helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<h2>Registration - Step 3/3</h2>
<hr />

<?php $form = ActiveForm::begin() ?>

	<?= $form->field($customerModel, 'str_account') ?>
	<?= $form->field($customerModel, 'str_iban') ?>

	<div class="form-group">
		<?= Html::submitButton("Next", ['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end() ?>
