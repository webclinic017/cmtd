<?php
namespace app\models;

use yii\base\Model;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

class ConsultaModel extends Model
{
    public $nome;
    public $inicio;
    public $final;
    
    public function rules(){
        return [
            [['nome', 'inicio', 'final'], 'required'],
            [['inicio', 'final'], 'date', 'format'=>'dd/mm/yyyy']
            //['final', 'compare', 'compareValue' => 'inicio', 'operator' => '>']
        ];
        
    }
    
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'inicio' => 'Data Inicial',
            'final' => 'Data Final'
        ];
    }

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