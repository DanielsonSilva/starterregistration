<?php
use yii\Helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<h2>Registration - Step 1/3</h2>
<hr />

<?php $form = ActiveForm::begin() ?>

	<?= $form->field($customerModel, 'str_firstname') ?>
	<?= $form->field($customerModel, 'str_lastname') ?>
	<?= $form->field($customerModel, 'str_telephone') ?>

	<div class="form-group">
		<?= Html::submitButton("Next Step", ['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end() ?>
