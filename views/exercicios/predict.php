<?php
/* @var $predicttModel app\models\PredicttModel */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $consultaModel app\models\ConsultaModel */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Paper;

ini_set('max_execution_time', 0); //300 seconds = 5 minutes
ini_set('memory_limit', '-1');

$data = Paper::find()->where(['codneg' => 'AALR3'])->one();

?>

<h2>Previsão por CMTD</h2>
<hr>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']) ?>

<?= $form->field($consultaModel, 'nome')->dropDownList(
			//['Teste' => 'T']
			ArrayHelper::map(Paper::find()->where(['=', 'date', $data->date])->all(), 'codneg', 'codneg') //mudar para pegar do último dia do banco
)?>

<?= $form->field($consultaModel, 'inicio')->widget(DatePicker::className(), [
	    'language' => 'pt',
	    'dateFormat' => 'dd/MM/yyyy'
]) ?>

<?= $form->field($predicttModel, 'exponent')->textInput([]) ?>

<?= $form->field($predicttModel, 'type')->dropDownList([
		'A' => 'Anos',
		'M' => 'Meses',
		'D' => 'Dias'
]) ?>

<div class="form-group">
	<div class="col-lg-offset-2">
		<?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
	</div>
</div>

<?php $form = ActiveForm::end() ?>
