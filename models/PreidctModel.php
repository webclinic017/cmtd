<?php

namespace app\models;

use yii\base\Model;

class Predict extends Model{
    private $matrix;
    private $vector;
    private $exponent;
    private $type;

    public function predict($matrix, $vector, $exponent){ //criar um novo model
        $matrixAux = $matrix;
        for($exponent; $exponent > 0; $exponent--){ // e para um dia a frente?
            $matrixAux = $matrixAux->multiply($matrix);
        } 

        /*for($i = 0; $i < 3; $i++)
            for($j = 0; $j < 3; $j++)
                echo $matrixAux[$i][$j].' ';*/ //visualização da matrix auxiliar
        
        echo $matrixAux->vectorMultiply($vector);
    }

}