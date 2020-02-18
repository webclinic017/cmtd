<?php
namespace app\models;

use yii\base\Model;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;
use yii\helpers\ArrayHelper;
use app\models\Paper;

/*def execute(start, end,states_number):

	client = MongoClient()
	db = client.stock

	collection = db.papers



	cursor = collection.find(
	 	{"codneg": "ITUB4", 
	 	"tpmerc" : "010", 
	 		"date": {
	         "$gte": start,
	         "$lt": end
	        }
	    }
	    ).sort([("date", pymongo.ASCENDING)])

	cursor_by_price = collection.find(
	 	{"codneg": "ITUB4", 
	 	"tpmerc" : "010", 
	 		"date": {
	         "$gte": start,
	         "$lt": end
	        }
	    }
	    ).sort([("preult", pymongo.ASCENDING)])

	papers = list(cursor)
	papers_by_price = list(cursor_by_price)


	
	min_price = papers_by_price[0]["preult"]
	max_price = papers_by_price[-1]["preult"]

	print("Periodo executado: De %s a %s \n" % (start, end))
	
	print("Menor preco foi %s no dia %s" % (papers_by_price[0]["preult"], papers_by_price[0]["date"]))
	print("Maior preco foi %s no dia %s" % (papers_by_price[-1]["preult"], papers_by_price[-1]["date"]))
	

	#TODO intervalo nao esta exato, depois precisa ver esse arredondamento
	#inicialmente nao notei grandes diferencas na matrix gerada
	 
	interval = round((abs(min_price - max_price))/states_number, 2)
	print("Quantidade de Intervalos: %s \n" % (states_number))
    print("Tamanho do intervalo: %s \n" % (interval))*/
    
class PredicttModel extends Model{
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

    public function predict($start, /* TODO $states_number*/ $stock /* TODO $day*/)
    {

        $start = \DateTime::createFromFormat('d/m/Y', $start);
        $day = \DateTime::createFromFormat('d/m/Y', '04/01/2019');

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
        echo('O menor preço foi: ' . $premin['preult'] . ' em ' . (Paper::toDate($premin['date']))->format('d/m/Y H:i:s') . '<br>');
        echo('O maior preço foi: ' . $premax['preult'] . ' em ' . (Paper::toDate($premax['date']))->format('d/m/Y H:i:s'));
    }
}    