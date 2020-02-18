<?php
/* @var $predicttModel app\models\PredicttModel */
/* @var $consultaModel app\models\ConsultaModel */

?>

<h3><?=$predicttModel->predict($consultaModel->inicio, $consultaModel->nome)?></h3>