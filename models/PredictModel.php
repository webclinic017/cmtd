<?php
namespace app\models;

use yii\base\Model;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

class PredictModel extends Model{
    private $matrix;
    private $vector;
    public $exponent;
    public $type;

    public function predict($matrix, $vector, $days){ //criar um novo model
        $matrixAux = $matrix;
        for($days; $days > 0; $days--){ // e para um dia a frente?
            $matrixAux = $matrixAux->multiply($matrix);
        } 

        /*for($i = 0; $i < 3; $i++)
            for($j = 0; $j < 3; $j++)
                echo $matrixAux[$i][$j].' ';*/ //visualização da matrix auxiliar
        
        echo $matrixAux->vectorMultiply($vector);
    }
}