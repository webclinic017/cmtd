
<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'type')->dropDownList([
		['prompt' => 'Selecione a Ação'],
		['A' => 'Anos'],
		['M' => 'Meses'],
		['D' => 'Dias']
]) ?>

<div class="form-group">
			<?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
</div>

<?php $form = ActiveForm::end() ?>
