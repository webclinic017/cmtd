<?php
    /* @var $model app\models\PredictModel */

?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']) ?>

	<?=
		$form->field($model, 'type')->dropDownList([
            ['prompt' => 'Selecione a Ação'],
            ['Anos' => 'A'],
            ['Meses' => 'M'],
            ['Dias' => 'D']
	])?>

<?php ActiveForm::end() ?>