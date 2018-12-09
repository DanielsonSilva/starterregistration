<?php
use yii\Helpers\{Html, ArrayHelper};
use yii\bootstrap\ActiveForm;
use backend\models\Standard;

$cityNames = ArrayHelper::map($cityModel->find()->orderBy('str_city')->asArray()->all(), 'id_city', 'str_city');
?>

<h2>Registration - Step 2/3</h2>
<hr />

<?php $form = ActiveForm::begin() ?>

	<?= $form->field($customerModel, 'str_address') ?>
	<?= $form->field($customerModel, 'num_house') ?>
	<?= $form->field($customerModel, 'str_zip') ?>
	<?= $form->field($customerModel, 'id_city')->dropDownList($cityNames,['prompt'=>'Select City']) ?>

	<div class="form-group">
		<?= Html::submitButton("Next Step", ['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end() ?>
