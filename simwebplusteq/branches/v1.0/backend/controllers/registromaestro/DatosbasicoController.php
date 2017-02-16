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
 *  @file DatosbasicosController.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 08-07-2015
 * 
 *  @class DatosbasicoController
 *  @brief Clase Controlador donde se cargan los metodos CRUD del datos basicos
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
namespace backend\controllers\registromaestro;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\registromaestro\DatosBasicoForm;
use backend\models\registromaestro\DatosBasicoSearch;
use backend\assets\AppAsset;
use common\conexion\ConexionController;
use yii\bootstrap\Alert;
use yii\helpers\Url;



//namespace app\components;


/**
 * DatosbasicoController implements the CRUD actions for DatosBasicoForm model.
 */
class DatosbasicoController extends Controller
{
    public $layout="layout-main"; 
    public $conn;
    public $conexion;
    public $transaccion;

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
     * Lists all DatosBasicoForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DatosBasicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DatosBasicoForm model.
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
     * Creates a new DatosBasicoForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // @return, array, vista de crear datos basicos.
    public function actionCreate()
    {
        $msg='';
        $model = new DatosBasicoForm();        
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
                if ( $conn->guardarRegistro($this->conexion, $t, $arrayDatos ) ) {
                    $transaccion->commit();
                    $tipoError = 0;
                    $msg = "REGISTRO EXITOSO!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/registromaestro/datosbasico/create")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

                } else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/registromaestro/datosbasico/create")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();  
            } else {
                    $model->getErrors();
                    $generalVisible = 'visible';
                    if (Yii::$app->request->post()["visible"] == 'natural') {
                       $visibleNatural = 'visible';
                       $visibleJuridico = 'oculto';
                    }if (Yii::$app->request->post()["visible"] == 'juridico') {
                        $visibleNatural = 'oculto';
                        $visibleJuridico = 'visible';
                    }
                     return $this->render('datos-basicos', [
                    'model' => $model,
                    'msg' => $msg,
                    'generalVisible' => $generalVisible,
                    'visibleNatural' => $visibleNatural,
                    'visibleJuridico' => $visibleJuridico,
            ]);
                }             
        } else {
            $visibleNatural = '';
            $visibleJuridico = '';
            $generalVisible = 'noVisible';
            return $this->render('datos-basicos', [
                'model' => $model,
                'msg' => $msg,
                'generalVisible' => $generalVisible,
                'visibleNatural' => $visibleNatural,
                'visibleJuridico' => $visibleJuridico,
            ]);
        }
                
    }

    /**
     * Updates an existing DatosBasicoForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_contribuyente]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DatosBasicoForm model.
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
     * Finds the DatosBasicoForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DatosBasicoForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DatosBasicoForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
