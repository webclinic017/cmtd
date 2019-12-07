<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\ConsultaModel;
use app\models\MetodosModel;
use app\models\PredictModel;

class ExerciciosController extends Controller
{
    
    public function actionHome(){
        $this->layout = 'home';
        
        return $this->render('home');
    }
    
   public function actionCmtd(){
       $this->layout = 'clean';
       
        $model2 = new ConsultaModel;
        $model = new PredictModel;
        $post = $_POST;
        
        if($model2->load($post) && $model2->validate()){
            
            return $this->render('predict', [
                'predictModel' => $model,
                'consultaModel' => $model2
            ]);
        }
        
        else{
            
            return $this->render('cmtd', [
                'model' => $model2
            ]);
       }
    }
    
    public function actionMetodos() {
        $this->layout = 'clean';
        
        $metodosModel = new MetodosModel;
        $post = $_POST;
        
        if($metodosModel->load($post) && $metodosModel->validate()){
           
            if($metodosModel->metodo == 'CMTD'){
                
                return $this->redirect(['cmtd']);
            }
            
            else{
                
                return $this->render('cmo');
            }
            
        }
        
        else{
            return $this->render('metodos', [
                'model' => $metodosModel
            ]);
        }
    }
    
    public function actionLogin(){
        
    }
    
    public function actionLogout(){
        
    }
    
}

