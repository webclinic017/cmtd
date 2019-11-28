<?php
namespace app\controllers;

require 'C:/xampp/php/vendor/autoload.php';
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Paper;
use app\models\DownloadJob;
use yii\mongodb\Exception;
use yii\queue\Queue;
use ZipArchive;
use Datetime;
use DateInterval;
use DatePeriod;
use vendor;

class SiteController extends Controller
{
    
    
    public function actionImport($startDate, $endDate, $type)
    {
        
        ini_set('max_execution_time', 0); //300 seconds = 5 minutes
        ini_set('memory_limit', '-1');
        Yii::debug("[IMPORT] start");
        
        $begin =  DateTime::createFromFormat('dmY',$startDate);
        $end =  DateTime::createFromFormat('dmY',$endDate);
        if($type == 'day') {
            $format = 'dmY';
            $typeFromDownload = 'D';
        } else {
            $format = 'Y';
            $typeFromDownload = 'A';
        }
        
        
        for($i = $begin; $i <= $end; $i->modify('+1 ' . $type)){
            
            $dateFormatted = $i->format($format);
            
            try {
                //$this->downloadData($dateFormatted, $typeFromDownload);
                //$this->extractData($dateFormatted);
                $this->parseDataAndSaveInDatabase($dateFormatted, $typeFromDownload);
                Yii::debug("[IMPORT]sucesso na data " . $dateFormatted);
                
            } catch(\Exception $e) {
                Yii::debug("[IMPORT]falha na data " . $dateFormatted . " " . $e->getMessage());
                
            }
            
        }
        return "importado " ;
    }
    /*public function actionBackground($startDate, $endDate) {
        $begin =  DateTime::createFromFormat('dmY',$startDate);
        $end =  DateTime::createFromFormat('dmY',$endDate);
        for($i = $begin; $i <= $end; $i->modify('+1 day')){
            $dateFormatted = $i->format("dmY");
            $id = Yii::$app->queue->push(new DownloadJob([
                'dateFormatted' => $dateFormatted
            ]));
        }
        Yii::$app->queue->on(Queue::EVENT_AFTER_ERROR, function ($event) {
            Yii::debug($event->error);
            echo($event->error);
            return $event->error;
        });
            return "enfileirado";
    }*/
    public function downloadData($date, $type) {
        Yii::debug("[IMPORT] start download data from " . $date);
        
        
        $file_path = 'C:/Users/riqui/Downloads/bovespa/' . $date . '.zip';
        $fh = fopen($file_path, 'w');
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        
        $response = $client->createRequest()
        ->setMethod('GET')
        ->setUrl('http://bvmf.bmfbovespa.com.br/InstDados/SerHist/COTAHIST_' . $type . $date . '.zip')
        ->setOutputFile($fh)
        ->send();
        Yii::debug("[IMPORT] end download data from " . $date);
        
    }
    public function extractData($date) {
        Yii::debug("[IMPORT] start extractData data from " . $date);
        
        $file_path = 'C:/Users/riqui/Downloads/bovespa/' . $date . '.zip';
        $zip = new ZipArchive;
        $res = $zip->open($file_path);
        if ($res === TRUE) {
            echo 'ok';
            
            $zip->extractTo('C:/Users/riqui/Downloads/');
            $zip->close();
        } else {
            echo 'failed, code:' . $res;
            
        }
        Yii::debug("[IMPORT] end extractData data from " . $date);
        
    }
    public function parseDataAndSaveInDatabase($date, $type) {
        Yii::debug("[IMPORT] start parseDataAndSaveInDatabase data from " . $date);
        
        
        if($type == 'D') {
            $extension = '.TXT';
            $typeWithSeparator = "_D";
        } else {
            $extension = '.TXT';
            $typeWithSeparator = "_A";
        }
        
        $file = fopen("C:/Users/riqui/Downloads/COTAHIST" . $typeWithSeparator . $date . $extension,"r");
        if ($file) {
            $header = fgets($file);
            while (($line = fgets($file)) !== false) {
                if(substr($line,0,10) == "99COTAHIST") {
                    return "FIM";
                }
                try {
                    $line = mb_convert_encoding($line, 'US-ASCII', 'UTF-8');
                    $paper = new Paper();
                    // 2 parametro do substr - posicao inicial a ser lida na linha
                    //(1 numero a menos do que especidicado no manual)
                    // 3 parametro do substr - qtd caracteres a ser lido a partir da posicao inicial
                    
                    //DATA DO PREGÃO
                    /*$dateTime = \DateTime::createFromFormat('Ymd', substr($line,2,8));
                    
                    
                    $paper->date = $dateTime->format("Y-m-d");*/
                    
                    $dateTime = \DateTime::createFromFormat('YmdHis', substr($line,2,8).'000000')->modify('+1 day');
                    
                    
                    
                    $paper->date = new \MongoDB\BSON\UTCDateTime($dateTime);
                                       
                    //CODBDI - CÓDIGO BDI
                    //UTILIZADO PARA CLASSIFICAR OS PAPÉIS NA EMISSÃO DO BOLETIM DIÁRIO DE INFORMAÇÕES
                    $paper->codbdi = substr($line,10,2);
                    //CODNEG - CÓDIGO DE NEGOCIAÇÃO DO PAPEL
                    $paper->codneg = trim(substr($line,12,12));
                    //TPMERC - TIPO DE MERCADO
                    //CÓD. DO MERCADO EM QUE O PAPEL ESTÁ CADASTRADO
                    $paper->tpmerc = substr($line,24,03);
                    //NOMRES - NOME RESUMIDO DA EMPRESA EMISSORA DO PAPEL
                    $paper->nomres = trim(substr($line,27,12));
                    //ESPECI - ESPECIFICAÇÃO DO PAPEL
                    $paper->especi = substr($line,39,10);
                    //PRAZOT - PRAZO EM DIAS DO MERCADO A TERMO
                    $paper->prazot = substr($line,49,3);
                    //MODREF - MOEDA DE REFERÊNCIA
                    $paper->modref = trim(substr($line,52,4));
                    //PREABE - PREÇO DE ABERTURA DO PAPEL- MERCADO NO PREGÃO
                    $paper->preab = (float) substr_replace(substr($line,56,13), ".", 11, 0 );
                    //PREMAX - PREÇO MÁXIMO DO PAPEL- MERCADO NO PREGÃO
                    $paper->premax = (float) substr_replace(substr($line,69,13), ".", 11, 0 );
                    //PREMIN - PREÇO MÍNIMO DO PAPEL- MERCADO NO PREGÃO
                    $paper->premin = (float) substr_replace(substr($line,82,13), ".", 11, 0 );           
                    //PREMED - PREÇO MÉDIO DO PAPEL- MERCADO NO PREGÃO
                    $paper->premed = (float) substr_replace(substr($line,95,13), ".", 11, 0 );                           
                    //PREULT - PREÇO DO ÚLTIMO NEGÓCIO DO PAPEL-MERCADO NO PREGÃO
                    $paper->preult = (float) substr_replace(substr($line,108,13), ".", 11, 0 );
                    //PREOFC - PREÇO DA MELHOR OFERTA DE COMPRA DO PAPEL- MERCADO
                    $paper->preofc = (float) substr_replace(substr($line,121,13), ".", 11, 0 );
                    //PREOFV - PREÇO DA MELHOR OFERTA DE VENDA DO PAPEL- MERCADO
                    $paper->preofv = (float) substr_replace(substr($line,134,13), ".", 11, 0 );
                    //TOTNEG - NEG. -NÚMERO DE NEGÓCIOS EFETUADOS COM O PAPEL- MERCADO NO PREGÃO
                    $paper->totneg = substr($line,147,05);
                    //QUATOT -QUANTIDADE TOTAL DE TÍTULOS NEGOCIADOS NESTE PAPEL- MERCADO
                    $paper->quatot = substr($line,152,18);
                    //VOLTOT - VOLUME TOTAL DE TÍTULOS NEGOCIADOS NESTE PAPEL- MERCADO
                    $paper->voltot = substr($line,170,16);
                    //PREEXE - PREÇO DE EXERCÍCIO PARA O MERCADO DE OPÇÕES OU VALOR DO CONTRATO PARA O MERCADO DE TERMO SECUNDÁRIO
                    $paper->preexe = substr($line,188,11);
                    //INDOPC - INDICADOR DE CORREÇÃO DE PREÇOS DE EXERCÍCIOS OU VALORES  E CONTRATO PARA OS MERCADOS DE OPÇÕES OU TERMO SECUNDÁRIO
                    $paper->indopc = substr($line,201,1);
                    //DATVEN - DATA DO VENCIMENTO PARA OS MERCADOS DE OPÇÕES OU TERMO SECUNDÁRIO
                    $paper->datven = substr($line,202,8);
                    //FATCOT - FATOR DE COTAÇÃO DO PAPEL
                    $paper->fatcot = substr($line,210,7);
                    //PTOEXE - PREÇO DE EXERCÍCIO EM PONTOS PARA OPÇÕES REFERENCIADAS EM DÓLAR OU VALOR DE CONTRATO EM PONTOS PARA TERMO SECUNDÁRIO
                    $paper->ptoexe = substr($line,217,7);
                    //CODISI - CÓDIGO DO PAPEL NO SISTEMA ISIN OU CÓDIGO INTERNO DO PAPEL
                    $paper->codisi = substr($line,230,12);
                    //DISMES - NÚMERO DE DISTRIBUIÇÃO DO PAPEL
                    $paper->dismes = substr($line,242,3);
                    $paper->created_at = date("Y-m-d H:i:s");
                    
                    $paper->save();
                } catch(Exception $e) {
                    Yii::debug("[IMPORT] error");
                }
                
            }
            fclose($file);
        } else {
            Yii::debug("[IMPORT] file not found " . $date);
            
        }
        Yii::debug("[IMPORT] end parseDataAndSaveInDatabase data from " . $date);
        
        return "OK";
    }
    public function actionFind() {
        Yii::info("hi there");
        $paper = Paper::find()->where(["nomres"=>"APPLE       "])->all();

        foreach($paper as $p)
            echo "Nome: ".$p->nomres." Preço de abertura: ".$p->preab." Data: ".$p->date;
    }

    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}