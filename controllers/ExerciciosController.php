<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\ConsultaModel;
use app\models\MetodosModel;

class ExerciciosController extends Controller
{
    
    public function actionHome(){
        $this->layout = 'home';
        
        return $this->render('home');
    }
    
   public function actionCmtd(){
       $this->layout = 'clean';
       
        $model = new ConsultaModel;
        $post = $_POST;
        
        if($model->load($post) && $model->validate()){
            
            return $this->render('formulario-confirmacao', [
                'model' => $model
            ]);
        }
        
        else{
            
            return $this->render('cmtd', [
                'model' => $model
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

