
<?php

use yii\bootstrap\ActiveForm;

?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'type')->dropDownList([
		['prompt' => 'Selecione a Ação'],
		['Anos' => 'A'],
		['Meses' => 'M'],
		['Dias' => 'D']
]) ?>

<?php $form = ActiveForm::end() ?>
