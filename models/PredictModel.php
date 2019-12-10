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

    public function rules()
    {
        return[
            [['exponent', 'type'], 'required'],
            ['exponent', 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Predição em:',
            'exponent' => 'Prever para:'
        ];
    }

    public function predict($matrix, $vector, $exponent){

        if($exponent == 1){
            return $matrix->vectorMultiply($vector);
        }

        else{
            $matrixAux = $matrix;
            for($exponent-=1; $exponent > 0; $exponent--){
                $matrixAux = $matrixAux->multiply($matrix);
            } 

            /*for($i = 0; $i < 3; $i++)
                for($j = 0; $j < 3; $j++)
                    echo $matrixAux[$i][$j].' ';*/ //visualização da matrix auxiliar
            
            return $matrixAux->vectorMultiply($vector);
        }
    }
}