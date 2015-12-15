<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *  > This library is free software; you can redistribute it and/or modify it under 
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *  > any later version.
 *  > 
 *  > This library is distributed in the hope that it will be usefull, 
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *  > for more details.
 *  > 
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *  @file AvaluoCatastralInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class AvaluoCatastralInmueblesUrbanosController
 *  @brief Clase que permite controlar el avaluo catastral del inmueble urbano 
 *  
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  AvaluoCatastralInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\inmueble;
error_reporting(0);
session_start();
use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\ContribuyentesForm;
use backend\models\inmueble\AvaluoCatastralForm;

use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;
/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class AvaluoCatastralInmueblesUrbanosController extends Controller
{   

    public $conn;
    public $conexion;
    public $transaccion; 

     /**
     * Lists all Inmuebles models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $searchModel = new InmueblesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    }

    /**
     * Displays a single Inmuebles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    }


    /**
     *Metodo: CambioPropietarioInmuebles
     *Actualiza los datos del numero catastral del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/
    public function actionAvaluoCatastralInmuebles()
    { 
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        //$modelContribuyente = $this->findModelContribuyente($id_contribuyente);
        

        $model = $this->findModel($id_contribuyente); 

         //$modelavaluo = new AvaluoCatastralForm();
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null; 
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
         /*if ($modelavaluo->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelavaluo); 
         } */

         $datosCambio = Yii::$app->request->post("InmueblesUrbanosForm");
         $btn = Yii::$app->request->post();


         if ($model->load(Yii::$app->request->post())){
            //if ($modelContribuyente->load(Yii::$app->request->post())){

              //if($modelContribuyente->validate()){ 
           
                if($model->validate()){

                 //condicionales     
                  
                if (!\Yii::$app->user->isGuest){   
                     
                   
/*
CONTENIDO DEL AVALUO CATASTRAL
*/  
       
                if ($datosCambio["operacion"] == 2) {
                    //echo'<pre>'; var_dump($btn['Next']); echo '</pre>'; die();
                    if ($btn['NextBuyer']!=null) {
                        $contador = 1;

                        $datosVContribuyente = ContribuyentesForm::find()->where(['naturaleza'=>$datosCambio["naturalezaBuscar"]])
                                                                     ->andWhere(['cedula'=>$datosCambio["cedulaBuscar"]])
                                                                     ->andWhere(['tipo'=>$datosCambio["tipoBuscar"]])->asArray()->all();  

   
                    }
                    if ($btn['NextBuyer']!=null) {
                        
                        if ($datosCambio["datosVendedor"]!=null) {
                            
                     
                            $contador = $contador+1;
                            $datosVInmueble = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$datosCambio["datosVendedor"]])->asArray()->all(); 

                         
                        }
                    } 
                    if ($btn['AcceptBuyer']!=null) {
                        $id_contribuyenteComprador = $datosCambio["id_contribuyente"];

                        $ano_traspaso = $datosCambio["ano_traspaso"];
                        $id_contribuyenteVendedor = $datosCambio["datosVendedor"];
                        $id_impuestoVendedor = $datosCambio["inmuebleVendedor"];
                        //$id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];  


                        //--------------TRY---------------
                        $arrayDatos = [ 
                                        'id_contribuyente' => $id_contribuyenteComprador,
                                      ]; 

                        $tableName = 'inmuebles'; 

                        $arrayCondition = ['id_impuesto' => $id_impuestoVendedor,]; 
//echo'<pre>'; var_dump($datosCambio); echo '</pre>'; die('aqui buyer 1'); 
                        $conn = New ConexionController();  

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                        $this->conexion->open(); $nombre

                        $transaccion = $this->conexion->beginTransaction(); 

                        if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){

                            $transaccion->commit(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'SUCCESSFUL UPDATE DATA OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }else{ 

                            $transaccion->roolBack();  
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN UPDATE THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/index', 'id' => $model->id_contribuyente])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                        $this->conexion->close();                 

                        
                    } 
                }
/*
FIN AVALUO CATASTRAL DEL INMUEBLE
*/
                 }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                 } 

              }else{ 
                $model->getErrors();
                    //echo var_dump($btn);exit();
                    /*if ($model->tipo_naturaleza1 =="") {

                        $model->addError('tipo_naturaleza1', Yii::t('backend', 'prueba') ); 
                    }
                    if ($model->tipo_naturaleza =="") {

                        $model->addError('cedulaBuscar', Yii::t('backend', 'prueba') ); 
                    }*/
                   
              } 
            /*}else{ 

                   $model->getErrors(); 
              }
         }*/
        }
   
         return $this->render('avaluo-catastral-inmuebles', [
                'model' => $model, 'modelContribuyente' => $modelContribuyente, 'modelBuscar' =>$modelBuscar, 'datosVContribuyente'=>$datosVContribuyente,
                'datosVInmueble'=>$datosVInmueble, 'modelAvaluo' =>$modelAvaluo,
            ]); 
        
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 
    

    /**
     * Finds the Inmuebles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inmuebles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    { 
        if (($model = AvaluoCatastralForm::find()->where(['id_contribuyente'=>$_SESSION['idContribuyente']])->one()) !== null) {

            return $model; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
    } 
    
    /**
     * Finds the Contribuyentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contribuyente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   /* public function findModelContribuyente($id)
    {//echo'<pre>'; var_dump($_SESSION['idContribuyente']); echo '</pre>'; die('hola');
        if (($modelContribuyente = ContribuyentesForm::findOne($id)) !== null) {
            
            return $modelContribuyente; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
}

