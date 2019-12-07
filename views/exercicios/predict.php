<?php
/* @var $predictModel app\models\PredictModel */
/* @var $consultaModel app\models\ConsultaModel */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']) ?>

<?= $form->field($predictModel, 'exponent')->textInput([]) ?>

<?= $form->field($predictModel, 'type')->dropDownList([
		['prompt' => 'Selecione a Ação'],
		['A' => 'Anos'],
		['M' => 'Meses'],
		['D' => 'Dias']
]) ?>

<div class="form-group">
	<div class="col-lg-offset-2">
		<?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
	</div>
</div>

<?php $form = ActiveForm::end() ?>
