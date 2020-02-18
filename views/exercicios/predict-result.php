<?php
/* @var $model app\models\PredictModel */

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

$m = [
    [0.3, 0.2, 0.5],
    [0.1, 0.8, 0.1],
    [0.0, 0.6, 0.4]
];

$matrix = MatrixFactory::create($m);

$vector = new Vector([1, 0, 0]);
?>

<h3><?=$model->predict($matrix, $vector, $model->exponent)?></h3>