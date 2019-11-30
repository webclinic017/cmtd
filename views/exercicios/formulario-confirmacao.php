<?php
/* @var $model app\models\ConsultaModel */

use app\models\Paper;
use app\models\ConsultaModel;

?>

<p> Você inseriu as seguintes informações: </p>

<ul>
	<li><label>Nome</label>: <?= $model->nome ?></li>
	<li><label>Data Inicial</label>: <?= $model->inicio ?></li>
	<li><label>Data Final</label>: <?= $model->final ?></li>
</ul>

<?php
	$d1 = \DateTime::createFromFormat('d/m/Y', $model->inicio);
	$d2 = \DateTime::createFromFormat('d/m/Y', $model->final);
	$d1 = \DateTime::createFromFormat('YmdHis', $d1->format('YmdHis'));
	$d2 = \DateTime::createFromFormat('YmdHis', $d2->format('YmdHis'));

	$dInicio = Paper::toIsoDate($d1->getTimestamp());
	$dFim = Paper::toIsoDate($d2->getTimestamp());
	
	$paper = Paper::find()->where(['codneg'=>$model->nome])->andWhere(['>=', 'date', $dInicio])->andWhere(['<=', 'date', $dFim])->all();

	foreach($paper as $p){
		$date = \DateTime::createFromFormat('U', $p->date);
		//$date->setTimestamp($p->date);
		$date->setTimestamp($date->getTimestamp());
		echo "Nome: ".$p->nomres." Preço de abertura: ".$p->modref.$p->preab." Preço de fechamento: ".$p->modref.$p->preult." Data: ".$date->format('d/m/Y')."<hr /><br />";
	}
	
?>