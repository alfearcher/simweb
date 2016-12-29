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
 *  @file DesintegracionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class DesintegracionInmueblesUrbanosController
 *  @brief Clase que permite controlar la desintegracion del inmueble urbano, 
 *  
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  DesintegracionInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble;
error_reporting(0);
session_start();
use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\CambioPropietarioInmueblesForm;
use backend\models\ContribuyentesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;

use backend\models\buscargeneral\BuscarGeneralForm;
use backend\models\buscargeneral\BuscarGeneral;
use frontend\models\inmueble\DesintegracionInmueblesForm;


/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class DesintegracionInmueblesUrbanosController extends Controller
{   
public $layout="layout-main";
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
     *Metodo: DesintegracionInmuebles
     *Realiza la desintegracion del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model trae los datos del formulario 
     **/
    public function actionDesintegracionInmuebles()
    { 
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $modelContribuyente = $this->findModelContribuyente($_SESSION['idContribuyente']);
        

        $model = $this->findModel(96534); 


         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null; 
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
         if ($modelContribuyente->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelContribuyente); 
         } 

         $datosCambio = Yii::$app->request->post("DesintegracionInmueblesForm");
         $btn = Yii::$app->request->post(); 


         if ($model->load(Yii::$app->request->post())){
            //if ($modelContribuyente->load(Yii::$app->request->post())){

              //if($modelContribuyente->validate()){ 
           
                if($model->validate()){

                 //condicionales     
                die('llegue al proceso desintegracion');
                if (!\Yii::$app->user->isGuest){ 
                     
     
              
/*
CONTENIDO DESINTEGRACION DEL INMUEBLE
-dueños unicos en las parcelas
-aledaños (parcelas vecinas)
-exista en el SIM
-solvencia hasta la fecha (trimestre, mes, año)
-no aplica para propiedad horizontal
-en el catastro solo se edita el numero de parcela
*/

                if ($datosCambio["operacion"] == 1) {
                                    
                    if ($datosCambio["tipo_naturaleza1"] == 0) {
                        $tipo = $datosCambio["tipoBuscar1"];
                    } else { 
                        $tipo = 0; 
                    } 

                    $modelParametros = ContribuyentesForm::find()->where(['naturaleza'=>$datosCambio["naturalezaBuscar1"]])
                                                                 ->andWhere(['cedula'=>$datosCambio["cedulaBuscar1"]])
                                                                 ->andWhere(['tipo'=>$tipo])->asArray()->all();                                         


                    if ($btn['AcceptSeller']!=null) {

                        $id_contribuyenteVendedor = $datosCambio["id_contribuyente"];
                        $id_impuestoVenta = $datosCambio["direccion"];
                        $ano_traspaso = $datosCambio["ano_traspaso"];

                        $id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];


                        //--------------TRY---------------
                        $arrayDatos = [ 
                                        'id_contribuyente' => $id_contribuyenteComprador,
                                      ]; 
                        

                        $tableName = 'inmuebles';  

                        $arrayCondition = ['id_impuesto' => $id_impuestoVenta,]; 

//echo'<pre>'; var_dump($datosCambio); echo '</pre>'; die('hola seller 2'); 
                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                        $this->conexion->open(); 

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
FIN SELLER
*/


/*
CONTENIDO DEL COMPRADOR (BUYER)
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
                        $this->conexion->open(); 

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
FIN BUYER
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
   
         return $this->render('desintegracion-inmuebles', [
                'model' => $model, 'modelContribuyente' => $modelContribuyente, 'modelBuscar' =>$modelBuscar, 'datosVContribuyente'=>$datosVContribuyente,
                'datosVInmueble'=>$datosVInmueble,
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
        if (($model = DesintegracionInmueblesForm::findOne($id)) !== null) {

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
    public function findModelContribuyente($id)
    {//echo'<pre>'; var_dump($_SESSION['idContribuyente']); echo '</pre>'; die('hola');
        if (($modelContribuyente = ContribuyentesForm::findOne($id)) !== null) {
            
            return $modelContribuyente; 
        } else { 
            throw new NotFoundHttpException('The requested page does not exist.');
        } 
    }
    
}

