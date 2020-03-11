<?php
/* @var $predictModel app\models\PredictModel */
/* @var $consultaModel app\models\ConsultaModel */

?>

<h3><?=$predictModel->predict($consultaModel->inicio,  $consultaModel->nome, $consultaModel->final, $consultaModel->states_number)?></h3>