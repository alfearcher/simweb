<?php
/**
 *      @copyright © by ASIS CONSULTORES 2012 - 2016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *      > This library is free software; you can redistribute it and/or modify it under 
 *      > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *      > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *      > any later version.
 *      > 
 *      > This library is distributed in the hope that it will be usefull, 
 *      > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *      > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *      > for more details.
 *      > 
 *      > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *      @file ApuestasIlicitaController.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 17-09-2015
 * 
 *      @class ApuestasIlicitaController
 *      @brief Clase permite gestionar las apuestas ilicitas ( crear, modificar y inactivar ).
 * 
 *  
 *  
 *      @property
 *  
 *      @method
 *  
 *      @inherits
 *  
 */


namespace backend\controllers\apuestailicita;
session_start();
error_reporting(0);

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\TarifasApuesta;
use common\conexion\ConexionController;
use backend\models\apuestailicita\HistoricoForm;
use backend\models\apuestailicita\ApuestasIlicitaForm;
use backend\models\apuestailicita\ApuestasIlicitaSearch;

class ApuestasIlicitaController extends Controller
{
    public $layout = 'layout-main';	
    public $conexion;
    public $conn;
    public $transaccion;
   
    public function behaviors()
    {
        return [ 'verbs'  => [ 'class'  => VerbFilter::className(), 'actions'  => [ 'delete'  => [ 'post' ], ], ], ];
    }
    
   /** 
    *   Metodo actionIndex(), retorna el listado principal de los grupos de trabajo, a la vista index.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function actionIndex()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
                    $searchModel = new ApuestasIlicitaSearch();
                    $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
                    return $this->render( 'index', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }
    
    /**
     * Displays a single ApuestasIlicitaForm model.
     * @param string $id
     * @return mixed
     */
    public function actionView( $id )
    {
            $sql = " SELECT A.id_grupo, A.descripcion, A.fecha, A.inactivo, B.descripcion AS unidad, C.descripcion AS departamento";
            $sql.= " FROM grupos_trabajo A, unidades_departamentos B, departamentos C ";
            $sql.= " WHERE A.id_departamento = B.id_departamento AND A.id_unidad = C.id_unidad AND A.inactive = '0' AND A.id_grupo= {$id}";
            
            $model= $conn->createCommand( $sql )->queryAll();
            echo var_dump($model);exit();
        //return $this->render( 'view', [ 'model' => $model ] );
    }
    
    /** 
    *   Metodo actionCreate(), permite realizar los registros de las apuestas ilicitas.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $id_contribuyente, interger.
    * 	@param $descripcion, varchar.
    * 	@param $direccion, varchar.
    * 	@param $id_cp, interger. 
    *	@param $id_sim, integer.
    *   @param $status_apuesta, integer.
    */
    public function actionCreate() 
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
            $msg = '';
            $model = new ApuestasIlicitaForm();  
            $operacion = new HistoricoForm();  

            if( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) {

                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate( $model );
            }

            if ( $model->load( Yii::$app->request->post() ) ) {

                    if ( $model->validate() ) {

                        if( $model->id_sim == null ){ $model->id_sim = 0; }

                        $conexion = new ConexionController();
                        $conn = $conexion->initConectar( 'db' );
                        $conn->open();
                        $id_contribuyente = $model->id_contribuyente;
                        $descripcion = $model->descripcion;
                        $direccion = $model->direccion;
                        $id_cp = $model->id_cp;
                        $id_sim = $model->id_sim;
                        $status_apuesta = 0;


                        $tabla = 'apuestas';  
                        $arrayDatos = [ 'id_contribuyente' => $id_contribuyente, 'descripcion' => strtoupper($descripcion), 'direccion'=> strtoupper($direccion), 'id_cp' => $id_cp, 'id_sim' => $id_sim, 'status_apuesta' => $status_apuesta ];
                        $transaccion = $conn->beginTransaction();

                        if( $conexion->guardarRegistro( $conn, $tabla, $arrayDatos ) ) {

                                    $transaccion->commit();
                                    $tipoError = 0;
                                    $msg = "REGISTRATION SUCCESSFUL ! .... Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                                    return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                        } else {
                                    $transaccion->rollBack();
                                    $tipoError = 1;
                                    $msg = "ERROR OCCURRED !....Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                                    return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                        }
                                $this->conexion->close();  
                    } else {
                                $model->getErrors();
                                return $this->render( 'create', [ 'model' => $model, 'operacion' => $operacion, 'msg' => $msg ] ); 
                    }   
            } else {
                        return $this->render( 'create', [ 'model' => $model, 'operacion' => $operacion, 'msg' => $msg ] );
            }
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }

    /** 
    *   Metodo actionUpdate(), permite realizar las modificaciones de las apuestas ilicitas registrados.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $id_contribuyente, interger.
    * 	@param $descripcion, varchar.
    * 	@param $direccion, varchar.
    * 	@param $id_cp, interger. 
    *	@param $id_sim, integer.
    *   @param $status_apuesta, integer. 
    */  
    public function actionUpdate( $id ) 
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
        
            $model = $this->findModel( $id );
            $operacion = new HistoricoForm(); 
            $btn = Yii::$app->request->post('btn');

            //echo var_dump(Yii::$app->request->post('HistoricoForm'));die();
            $datos = Yii::$app->request->post('HistoricoForm');

            $conexion = new ConexionController();
            $conn = $conexion->initConectar( 'db' );
            $conn->open();

            $consulta_historico = $operacion->consultarHistorico( $conexion, $conn, $id );

            if( $model->load( Yii::$app->request->post() )) {

                $id_contribuyente = $model->id_contribuyente;
                $descripcion = $model->descripcion;
                $direccion = $model->direccion;
                $id_cp = $model->id_cp;
                $id_sim = $model->id_sim;
                $status_apuesta = $model->status_apuesta;

                $tabla = 'apuestas';  
                $arrayDatos = [ 'id_contribuyente' => $id_contribuyente, 'descripcion' => strtoupper($descripcion), 'direccion'=> strtoupper($direccion), 'id_cp' => $id_cp, 'id_sim' => $id_sim, 'status_apuesta' => 0 ];
                $arrayCondition = [ 'id_impuesto' => $id ]; 
                $transaccion = $conn->beginTransaction();

                if( $btn == 'save' ) {

                    if( Yii::$app->request->isAjax ) {

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ActiveForm::validate( $operacion );
                    }

                    if ( $operacion->load( Yii::$app->request->post() ) ) {

                        if ( $operacion->validate() ) {

                            $resultadoOperacion = $operacion->registrarHistorico( $conexion, $conn, $datos );

                            if( $resultadoOperacion == true ) {

                                        $transaccion->commit(); 
                                        $tipoError = 0;
                                        $msg = "REGISTRATION SUCCESSFUL ! .... Wait";
                                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                            } else {
                                        $transaccion->rollBack();
                                        $tipoError = 1;
                                        $msg = "ERROR OCCURRED !....Wait";
                                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                            }
                        } else { 
                                    $model->getErrors();
                                    return $this->render( 'update', [ 'operacion' => $operacion, 'model' => $model, 'consulta_historico' => $consulta_historico ]  ); 


                        }
                    }
                }

                if( $conexion->modificarRegistro( $conn, $tabla, $arrayDatos, $arrayCondition ) ) {

                            $transaccion->commit(); 
                            $tipoError = 0;
                            $msg = "SUCCESSFULLY MODIFIED! .... Wait";
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                } else {
                            $transaccion->rollBack();
                            $tipoError = 1;
                            $msg = "ERROR OCCURRED!....Wait";
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestailicita/apuestas-ilicita/index")."'>";
                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                }
                        $this->conexion->close();
            } else {
                        return $this->render( 'update', [ 'model' => $model, 'operacion' => $operacion, 'consulta_historico' => $consulta_historico ] );
            }
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }

    protected function findModel( $id )
    {
        if( ( $model = ApuestasIlicitaForm::findOne( $id ) ) !== null ){
                    
                    return $model;
        } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}