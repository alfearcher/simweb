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
 *  @file CambioOtrosDatosUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class CambioOtrosDatosInmueblesUrbanosController
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
 *  Cambiosotrosdatosinmuebles
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
use backend\models\inmueble\CambioOtrosDatosInmueblesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;
session_start();
/***************************************************************************************************
 * CambioOtrosDatosInmueblesUrbanosController implements the actions for InmueblesUrbanosForm model.
 ***************************************************************************************************/
class CambioOtrosDatosInmueblesUrbanosController extends Controller
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
     *Metodo: CambioOtrosDatosInmuebles 
     *Actualiza otros datos del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato string y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/ 
    public function actionCambioOtrosDatosInmuebles($id_impuesto)
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        //$model2 = new CambiarOtrosDatosInmueblesForm();
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

               
                  
                if (!\Yii::$app->user->isGuest){

                     $datosCambio = Yii::$app->request->post('CambioOtrosDatosInmueblesForm');

                     $id_impuesto = $datosCambio["id_impuesto"];                   //clave principal de la tabla no sale en el formulario identificador del inpuesto inmobiliario
                     $id_contribuyente = $datosCambio["id_contribuyente"];         //identidad del contribuyente
                     $ano_inicio = $datosCambio["ano_inicio"];                     //anio de inicio
                     $direccion = $datosCambio["direccion"];                       //direccion
                     
                     $casa_edf_qta_dom = $datosCambio["casa_edf_qta_dom"];         //casa. edificio. quinta. domicilio
                     $piso_nivel_no_dom = $datosCambio["piso_nivel_no_dom"];       //piso. nivel. numero. domicilio
                     $apto_dom = $datosCambio["apto_dom"];                         //apartamento. domicilio

                     $tlf_hab = $datosCambio["tlf_hab"];                           //telefono habitacion
                     $medidor = $datosCambio["medidor"];                           //medidor
                     
                     $observacion = $datosCambio["observacion"];                   //observaciones
                     $inactivo = $datosCambio["inactivo"];                         //inactivo
                    
                     $tipo_ejido = $datosCambio["tipo_ejido"];                     //tipo ejido
                     

                  
                     //--------------TRY---------------
                        $arrayDatos = [
                                        //otros datos
                                       'ano_inicio' => $ano_inicio,
                                       'direccion' => $direccion,
                                       'casa_edf_qta_dom' => $casa_edf_qta_dom, 
                                       'piso_nivel_no_dom' => $piso_nivel_no_dom,
                                       'apto_dom' => $apto_dom, 
                                       'tlf_hab' => $tlf_hab,
                                       'medidor' => $medidor,
                                       'observacion' => $observacion,
                                       'inactivo' => $inactivo,
                                       'tipo_ejido' => $tipo_ejido,
                                      ]; 

                        $tableName = 'inmuebles'; 

                        $arrayCondition = ['id_impuesto' => $id_impuesto,]; 


                        $conn = New ConexionController(); 

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
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

         return $this->render('cambio-otros-datos-inmuebles', [
                'model' => $model,
            ]); 
       
        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['menu/vertical'])."'>";
        }
    } 

    /**
     * Finds the InmueblesUrbanosForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inmuebles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     **/
    protected function findModel($id)
    {
        if (($model = CambioOtrosDatosInmueblesForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
