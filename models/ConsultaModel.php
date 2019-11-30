<?php
namespace app\models;

use yii\base\Model;

class ConsultaModel extends Model
{
    public $nome;
    public $inicio;
    //public $final;
    
    public function rules(){
        return [
        [['nome', 'inicio'/*, 'final'*/], 'required'],
        [['inicio'/*, 'final'*/], 'date', 'format'=>'dd/mm/yyyy']
            //['final', 'compare', 'compareValue' => 'inicio', 'operator' => '>']
        ];
        
    }
    
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'inicio' => 'Data Inicial'
            /*'final' => 'Data Final'*/
        ];
    }

}