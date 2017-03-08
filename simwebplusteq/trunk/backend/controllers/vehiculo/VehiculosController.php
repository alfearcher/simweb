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
 *  @file VehiculosController.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 03-08-2015
 * 
 *  @class VehiculosController
 *  @brief Clase Controlador donde se cargan los metodos CRUD de vehiculos
 * 
 *  
 *  
 *  @property
 *  
 *  @method
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\vehiculo;

use Yii;
use backend\assets\AppAsset;
use backend\models\vehiculo\VehiculosForm;
use backend\models\vehiculo\VehiculosCambioPlacaForm;
use backend\models\vehiculo\VehiculosSearch;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use common\conexion\ConexionController;

/**
 * VehiculosController implements the CRUD actions for VehiculosForm model.
 */
class VehiculosController extends Controller
{
    public $conn;
    public $conexion;
    public $transaccion;
    public $layout = 'layoutbase';
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all VehiculosForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VehiculosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
 
    /**
     * Lista el vehiculo que esta relacionado con la placa que se ingreso en el
     * formulariod e busqueda.
     * @return mixed
     */
    public function actionCambioPlacaResult()
    {
        $searchModel = new VehiculosSearch();
        $dataProvider = $searchModel->searchPlaca(Yii::$app->request->queryParams);
         $model = new VehiculosForm();

        return $this->render('cambioplaca/cambio-placa-result', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Metodo que permite cargar la vista de busqueda de vehiculos
     * para su debida modificacion de datos
     * @return mixed
     */
    public function actionBusqueda()
    {
        $model = new VehiculosForm();
        $searchModel = new VehiculosSearch();
        return $this->render('busqueda', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Metodo que permite cargar la vista de busqueda de vehiculos
     * para su debida modificacion de datos
     * @return mixed
     */
    public function actionCambioPlaca()
    {
        $model = new VehiculosForm();
        $searchModel = new VehiculosSearch();
        return $this->render('cambioplaca/cambio-placa', [
            'searchModel' => $searchModel,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single view-final-vehiculo of VehiculosForm model.
     * @param string $idVehiculo
     * @return mixed
     */
    public function actionViewFinalVehiculo($idVehiculo)
    {
        return $this->render('cambioplaca/view-final-vehiculo', [
            'model' => $this->findModel($idVehiculo),
        ]);
    }

    /**
     * Displays a single VehiculosForm model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VehiculosForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $msg='';
        $desabilitar = false; // Permite colocar un input en modo escritura o solo lectura en la vista correspondiente 
        $model = new VehiculosForm();        
        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ( $model->validate() ) {
                $t = $model->tableName();
                $conn = New ConexionController();
                
                $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                $this->conexion->open();

                $arrayDatos = $model->attributes;
                $transaccion = $this->conexion->beginTransaction();
                
                /*
                *********************************************************
                *   Segmento que permite formatear las variables double *
                *    para su correcto almacenamiento en la BD           *
                *********************************************************
                */
                $arrayDatos["precio_inicial"] = str_replace('.', '', $arrayDatos["precio_inicial"]);
                $arrayDatos["precio_inicial"] = str_replace(',', '.', $arrayDatos["precio_inicial"]);
                $arrayDatos["peso"] = str_replace('.', '', $arrayDatos["peso"]);
                $arrayDatos["peso"] = str_replace(',', '.', $arrayDatos["peso"]);
                /*
                *********************************************************
                *                   Fin del                             *
                *   Segmento que permite formatear las variables double *
                *    para su correcto almacenamiento en la BD           *
                *********************************************************
                */
                if ( $conn->guardarRegistro($this->conexion, $t, $arrayDatos ) ) {
                    $transaccion->commit();
                    $tipoError = 0;
                    $msg = "REGISTRO EXITOSO!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/vehiculos/create")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

                } else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/vehiculos/create")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();  
            } else {
                    $model->getErrors();
                    $generalVisible = 'visible';
                    
                    return $this->render('create', [
                        'model' => $model,
                        'msg' => $msg,
                        'desabilitar' => $desabilitar,
                    ]);
                }             
        } else {
            return $this->render('create', [
                'model' => $model,
                'msg' => $msg,
                'desabilitar' => $desabilitar,
            ]);
        }
    }
    /**
     * Actualiza la placa del vehiculo
     * If update is successful, the browser will be redirected to the 'view-final-vehiculo' page.
     * @param string $idVehiculo
     * @return mixed
     */
    public function actionPlacaUpdate($idVehiculo)
    {
        $model = $this->findModelUpdatePlaca($idVehiculo);
        $msg='';
        $desabilitar = false; // Permite colocar un input en modo escritura o solo lectura en la vista correspondiente 
        
        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ( $model->validate() ) {
                $t = $model->tableName();
                $conn = New ConexionController();
                
                $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                $this->conexion->open();

                $arrayDatos = Yii::$app->request->post();
                $transaccion = $this->conexion->beginTransaction();

                $arrayCondition = ['id_vehiculo' => $arrayDatos["VehiculosCambioPlacaForm"]['id_vehiculo']];

                $oldModel = $model->getOldAttributes();    // Optenemos los valores antiguos del modelo

                $arrayBitacora = array(
                                        'id_vehiculo' => $arrayDatos["VehiculosCambioPlacaForm"]['id_vehiculo'],
                                        'id_contribuyente' => $oldModel['id_contribuyente'],
                                        'placa_vieja' => $oldModel['placa'],                                        
                                        'placa_nueva' => $arrayDatos["VehiculosCambioPlacaForm"]['placa'],
                                        'usuario' => Yii::$app->user->identity->username, 
                                        'fecha_hora' => date('Y-m-d H:i:s'), 
                                );

                if ( $conn->modificarRegistro($this->conexion, $t, $arrayDatos["VehiculosCambioPlacaForm"], $arrayCondition ) ) {

                    $tBitacora = 'bitacora_cambio_placa';   // nombre de la tabla

                    if ( $conn->guardarRegistro($this->conexion, $tBitacora, $arrayBitacora ) ) { // Almacenamos la bitacora
                        $transaccion->commit();
                        $condicionMsj = 'ACTUALIZADO';
                    }else{
                        $condicionMsj = 'NO SE PUDO COMPLETAR - VIOLACION DE SEGURIDAD - CONTACTE CON EL ADMINISTRADOR';
                        $transaccion->rollBack();
                    }
                    $tipoError = 0;
                    $msg = "REGISTRO ".$condicionMsj."!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/vehiculo/vehiculos/view-final-vehiculo").'&id='.$arrayDatos["VehiculosCambioPlacaForm"]['id_vehiculo']."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

                } else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/vehiculo/vehiculos/view-final-vehiculo").'&id='.$arrayDatos["VehiculosCambioPlacaForm"]['id_vehiculo']."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();    
            }else {
                    $model->getErrors();                    
                    return $this->render('cambioplaca/_form-cambio-placa', [
                    'model' => $model,
                    'msg' => $msg,
                    'desabilitar' => $desabilitar,
                ]);       
            } 
        }else {
            return $this->render('cambioplaca/_form-cambio-placa', [
                'model' => $model,
                'msg' => $msg,
                'desabilitar' => $desabilitar,
            ]);
        }        
    }   

    /**
     * Updates an existing VehiculosForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $msg='';
        $desabilitar = true; // Permite colocar un input en modo escritura o solo lectura en la vista correspondiente 
        $model = $this->findModel($id);    
        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ( $model->validate() ) {
                $t = $model->tableName();
                $conn = New ConexionController();
                
                $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                $this->conexion->open();

                $arrayDatos = $model->attributes;
                $transaccion = $this->conexion->beginTransaction();
                
                /*
                *********************************************************
                *   Segmento que permite formatear las variables double *
                *    para su correcto almacenamiento en la BD           *
                *********************************************************
                */
                $arrayDatos["precio_inicial"] = str_replace('.', '', $arrayDatos["precio_inicial"]);
                $arrayDatos["precio_inicial"] = str_replace(',', '.', $arrayDatos["precio_inicial"]);
                $arrayDatos["peso"] = str_replace('.', '', $arrayDatos["peso"]);
                $arrayDatos["peso"] = str_replace(',', '.', $arrayDatos["peso"]);
                /*
                *********************************************************
                *                   Fin del                             *
                *   Segmento que permite formatear las variables double *
                *    para su correcto almacenamiento en la BD           *
                *********************************************************
                */

                $arrayCondition = ['id_vehiculo' => $arrayDatos['id_vehiculo']];
                
                if ( $conn->modificarRegistro($this->conexion, $t, $arrayDatos, $arrayCondition ) ) {
                    $transaccion->commit();
                    $tipoError = 0;
                    $msg = "REGISTRO EXITOSO!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/vehiculos/view").'&id='.$arrayDatos['id_vehiculo']."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

                } else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/vehiculos/view").'&id='.$arrayDatos['id_vehiculo']."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();  
            } else {
                    $model->getErrors();                    
                    return $this->render('update', [
                    'model' => $model,
                    'msg' => $msg,
                    'desabilitar' => $desabilitar,
                    ]);
                }             
        } else {
            return $this->render('update', [
                'model' => $model,
                'msg' => $msg,
                'desabilitar' => $desabilitar,
            ]);
        }
    }

    /**
     * Deletes an existing VehiculosForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VehiculosForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VehiculosForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VehiculosForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the VehiculosForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VehiculosForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelUpdatePlaca($id)
    {
        if (($model = VehiculosCambioPlacaForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
