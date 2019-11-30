<?php
/* @var $model app\models\MetodosModel */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<h2>Métodos de Previsão</h2>
<hr>

<?php $form = ActiveForm::begin() ?>

	<?= $form->field($model, 'metodo')->radioList([
	    'CMTD' => 'CMTD',
	    'CMO' => 'CMO'
	]) ?>
	
	<div class="form-group">
		<?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end() ?>
