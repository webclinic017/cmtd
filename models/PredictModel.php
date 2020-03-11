<?php
namespace app\models;

use yii\base\Model;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;
use yii\helpers\ArrayHelper;
use app\models\Paper;
use yii\console\widgets\Table;
    
class PredictModel extends Model{

    public function transitionMatrix($paper, $states, $states_number){
        $matrix = [[]];
        for($i = 0; $i <= $states_number; $i++)
            for($j = 0; $j <= $states_number; $j++)
                $matrix[$i][$j] = 0;

        for($i = 0; $i < count($paper)-1; $i++){
            if($paper[$i]['state'] != 0){
                $j = $i+1;
                while($paper[$j]['state'] == 0){
                    $j++;
                }
                $matrix[$paper[$i]['state']-1][$paper[$j]['state']-1] += 1;
            }
        }

        for($i = 0; $i < $states_number; $i++)
            for($j = 0; $j <= $states_number; $j++){
                $matrix[$i][$j] /= $states[$i];
            }

        foreach($matrix as $m){
            print_r($m);
            echo '<br>';
        }
    }
    
    public function predict($start, $stock, $day, $states_number){ 

        $start = \DateTime::createFromFormat('d/m/Y', $start);
        $day = \DateTime::createFromFormat('d/m/Y', $day);

        $start = Paper::toIsoDate($start->getTimestamp());
        $day = Paper::toIsoDate($day->getTimestamp()); 
        
        $cursor_by_price = Paper::find()->where(
            ['=', 'codneg', $stock], 
            ['=', 'tpmerc', '010'],
            ['>=', 'date', $start]
            )->andWhere(['<', 'date', $day])->all();

        $premin = $cursor_by_price[0];

        foreach($cursor_by_price as $cursor){
            if($cursor['preult'] < $premin['preult'])
                $premin = $cursor;
        }    

        $premax = $cursor_by_price[0];

        foreach($cursor_by_price as $cursor){
            if($cursor['preult'] > $premax['preult'])
                $premax = $cursor;
        }    

        echo('Período análisado de ' . (Paper::toDate($start))->format('d/m/Y') . ' até ' . (Paper::toDate($day))->format('d/m/Y') . '<br>');
        echo('O menor preço foi: R$' . $premin['preult'] . ' em ' . (Paper::toDate($premin['date']))->format('d/m/Y') . '<br>');
        echo('O maior preço foi: R$' . $premax['preult'] . ' em ' . (Paper::toDate($premax['date']))->format('d/m/Y') . '<br>');
    
        $average = Paper::movingAverage($cursor_by_price, 3);
        asort($average);

        $interval = round(abs($average[0] - $average[count($average)-1])/$states_number, 2);
        echo("<br> Quantidade de intervalos $states_number <br>");
        echo("O tamanho do intervalo é $interval" . '<br>');

        echo('<br>Intervalos: <br>');

        for($i = 0; $i<$states_number; $i++){
            $price = $average[0] + $interval * ($i);
            echo ('Estado ' . ($i+1) . ' de ' . round($price, 2) . ' até ' . round(($price+$interval), 2) . '<br>');
        }

        $states = [];
        for($i=0; $i<$states_number; $i++){
            $states[$i] = 0;
        }
        
        foreach($cursor_by_price as $cursor){
            $state = Paper::getState($cursor['preult'], $average[0], $average[count($average)-1], $interval, $states_number);
            $cursor['state'] = $state;
            if($cursor['state'] != 0)
                $states[$cursor['state']-1] += 1;

            //echo($cursor['preult'] . ' -> ' . $cursor['state'] . " " . (Paper::toDate($cursor['date']))->format('d/m/Y') . '<br>');
        }

        echo('<br>Estado x Quantidade de elementos:<br>');
        foreach($states as $i => $s){
            echo('Estado ' . ($i+1) . ' tem ' . $s . ' elementos<br>');
        }

        echo '<br>';

        PredictModel::transitionMatrix($cursor_by_price, $states, $states_number);
    }

}    