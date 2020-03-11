<?php
/* @var $predictModel app\models\PredictModel */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $consultaModel app\models\ConsultaModel */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Paper;

ini_set('max_execution_time', 0); //300 seconds = 5 minutes
ini_set('memory_limit', '-1');

$dateAux = \DateTime::createFromFormat('d/m/Y', '09/03/2020');
$dateAux = Paper::toIsoDate($dateAux->getTimestamp());
//$data = Paper::find()->where(['>=', 'date', $dateAux])->all();

?>

<h2>Previsão por CMTD</h2>
<hr>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']) ?>

<?= $form->field($consultaModel, 'nome')->dropDownList(
			//['Teste' => 'T']
			ArrayHelper::map(Paper::find()->where(['>=', 'date', $dateAux])->all(), 'codneg', 'codneg') //mudar para pegar do último dia do banco
)?>

<?= $form->field($consultaModel, 'inicio')->widget(DatePicker::className(), [
	    'language' => 'pt-BR',
	    'dateFormat' => 'dd/MM/yyyy'
]) ?>


<?= $form->field($consultaModel, 'final')->widget(DatePicker::className(), [
	    'language' => 'pt-BR',
	    'dateFormat' => 'dd/MM/yyyy'
]) ?>

<?= $form->field($consultaModel, 'states_number')->textInput() ?>

<div class="form-group">
	<div class="col-lg-offset-2">
		<?= Html::submitButton('Enviar', ['class'=>'btn btn-primary']) ?>
	</div>
</div>

<?php $form = ActiveForm::end() ?>
