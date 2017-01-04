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
 *  @file CambioAPropiedadHorizontalInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class CambioAPropiedadHorizontalInmueblesUrbanosController
 *  @brief Clase que permite controlar el cambio de otros datos del inmueble urbano, 
 *  el cambio ha propiedad horizontal
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  CambioAPropiedadHorizontalInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\inmueble;

use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\CambioAPropiedadHorizontalInmueblesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\conexionController;
session_start();
/**************************************************************************************************
 * CambioAPropiedadHorizontalInmueblesUrbanosController implements the actions for Inmuebles model.
 **************************************************************************************************/
class CambioAPropiedadHorizontalInmueblesUrbanosController extends Controller
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
        $_SESSION['id'] = 3;
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
     *Metodo: CambioAPropiedadHorizontalInmuebles
     *Actualiza los datos de propiedad horizontal del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/
    public function actionCambioAPropiedadHorizontalInmuebles($id_impuesto)
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        $model = $this->findModel($id_impuesto);

    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
   
         if ($model->load(Yii::$app->request->post())){ 

              if($model->validate()){

                 //condicionales     
                  
                if (!\Yii::$app->user->isGuest){

                     $datosCambio = Yii::$app->request->post('CambioAPropiedadHorizontalInmueblesForm');


                     $id_impuesto = $datosCambio["id_impuesto"];                   
                     $id_contribuyente = $datosCambio["id_contribuyente"];        
                     $propiedad_horizontal = $datosCambio["propiedad_horizontal"];
                     

                     //cambios a propiedad horizontal
                    if ($propiedad_horizontal == 0) {

                          $parcela_catastro = $datosCambio["parcela_catastro"];                                            //Parcela catastro
                          $subparcela_catastro = 0;                                                                //Sub parcela catastro
                          $nivel_catastro = 0;                                                                     //Nivel catastro
                          $unidad_catastro = 0;                                                                    //Unidad catastro     
                     }else{ 

                          $parcela_catastro = $datosCambio["parcela_catastro"];                                            //Parcela catastro
                          $subparcela_catastro = $datosCambio["subparcela_catastro"];                                      //Sub parcela catastro
                          $nivel_c1 = $datosCambio["nivela"];
                          $nivel_c2 = $datosCambio["nivelb"];
                          $nivel_catastro1 = array(['nivela' =>$nivel_c1 , 'nivelb'=>$nivel_c2 ]);                 //Nivel catastro
                          $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";
                          $unidad_catastro = $datosCambio["unidad_catastro"];                                              //Unidad catastro  
                     }  
                    

                        //--------------TRY---------------
                        $arrayDatos = [
                                        //datos a guardar de propiedad horizontal
                                       'propiedad_horizontal' => $propiedad_horizontal,
                                       'parcela_catastro' => $parcela_catastro,
                                       'subparcela_catastro' => $subparcela_catastro,
                                       'nivel_catastro' => $nivel_catastro,
                                       'unidad_catastro' => $unidad_catastro,
                                      ]; 

                        $tableName = 'inmuebles';  

                        $arrayCondition = ['id_impuesto' => $id_impuesto,]; 


                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                        $this->conexion->open(); 

                        $transaccion = $this->conexion->beginTransaction(); 

                        if ( $conn->modificarRegistro($this->conexion, $tableName, $arrayDatos, $arrayCondition) ){

                            $transaccion->commit(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'SUCCESSFUL UPDATE DATA OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }else{

                            $transaccion->rollBack(); 
                            $tipoError = 0; 
                            $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN UPDATE THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                            return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                        $this->conexion->close(); 


                   }else{

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{
                
                   $model->getErrors(); 
              }
         }

         return $this->render('cambio-a-propiedad-horizontal-inmuebles', [
                'model' => $model,
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
        if (($model = CambioAPropiedadHorizontalInmueblesForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
