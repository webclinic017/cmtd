<?php
namespace app\models;

use yii\base\Model;

class ConsultaModel extends Model
{
    public $nome;
    public $inicio;
    public $final;
    public $states_number;
    
    public function rules(){
        return [
        [['nome', 'inicio', 'final', 'states_number'], 'required'],
        ['states_number', 'integer'],
        [['inicio', 'final'], 'date', 'format'=>'dd/mm/yyyy']
        //['final', 'compare', 'compareValue' => 'inicio', 'operator' => '>']
        ];
        
    }
    
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'inicio' => 'Data Inicial',
            'final' => 'Data Final',
            'states_number' => 'Quantidade de intervalos'
        ];
    }

}