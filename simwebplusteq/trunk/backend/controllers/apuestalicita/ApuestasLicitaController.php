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
 *      @file ApuestasLicitaController.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 17-09-2015
 * 
 *      @class ApuestasLicitaController
 *      @brief Clase permite gestionar las apuestas Licitas ( crear, modificar y inactivar ).
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


namespace backend\controllers\apuestalicita;
session_start();
error_reporting(0);

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\TarifasApuesta;
use common\conexion\ConexionController;
use backend\models\apuestalicita\HistoricoForm;
use backend\models\apuestalicita\ApuestasLicitaForm;
use backend\models\apuestalicita\ApuestasLicitaSearch;

class ApuestasLicitaController extends Controller
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
    *   Metodo actionIndex(), retorna el listado principal de las apuestas licitas, a la vista index.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function actionIndex()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
                    $searchModel = new ApuestasLicitaSearch();
                    $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
                    return $this->render( 'index', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }
    
    /**
    * Metodo actionView(), retorna una vista para visualizar los datos modificados o registrados de las apuestas licitas
    * @param string $id
    * @return mixed
    */
    public function actionView( $id )
    {
        $conexion = new ConexionController();
        $conn = $conexion->initConectar( 'db' );
        $conn->open();  
        
		if( $id == '' ) {
		
					$sql_max = " SELECT MAX(id_impuesto) AS id_impuesto FROM apuestas WHERE status_apuesta = 0";
					$id = $conn->createCommand( $sql_max )->queryAll();
					$id = $id[0]["id_impuesto"];
					$create = 1;
        } else {
	   				$id = $id;
		}
		
        $sql = " SELECT A.id_impuesto, A.descripcion, A.direccion, A.fecha_creacion, B.razon_social";
        $sql.= " FROM apuestas A, contribuyentes B ";
        $sql.= " WHERE A.id_contribuyente = B.id_contribuyente AND A.id_impuesto = {$id}";
        $model = $conn->createCommand( $sql )->queryAll();
		//ECHO $sql;exit();
		
		return $this->render( 'view', [ 'model' => $model, 'create' => $create ] );
    }
    
    /**
    * Metodo actionView(), retorna una vista para visualizar los datos registrados de los historicos de la apuesta licita
    * @param string $id
    * @return mixed
    */
    public function actionViewHistorico( $id )
    {
        $conexion = new ConexionController();
        $conn = $conexion->initConectar( 'db' );
        $conn->open();  
        
        $sql_max = " SELECT MAX(id_historico_apuesta) AS id_historico_apuesta FROM historico_apuestas WHERE id_impuesto = {$id}";
        $id = $conn->createCommand( $sql_max )->queryAll();
       
        $sql = " SELECT A.id_impuesto, A.fecha_desde, A.fecha_hasta, A.monto_apuesta, A.planilla, B.porcentaje, B.ano_impositivo, C.descripcion AS clase, D.descripcion AS tipo";
        $sql.= " FROM historico_apuestas A, tarifas_apuestas B, clases_apuestas C, tipos_apuestas D ";
        $sql.= " WHERE A.id_tarifa_apuesta = B.id_tarifa_apuesta AND B.clase_apuesta = C.clase_apuesta AND B.tipo_apuesta = D.tipo_apuesta AND A.id_historico_apuesta = {$id[0]["id_historico_apuesta"]}";
        $model = $conn->createCommand( $sql )->queryAll();
         //echo var_dump($model);exit();  
        return $this->render( 'view-historico', [ 'model' => $model ] );
    }
    
    /** 
    *   Metodo actionCreate(), permite realizar los registros de las apuestas licitas.
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
            $model = new ApuestasLicitaForm();  
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
                        $fecha_creacion = date('Y-m-d');


                        $tabla = 'apuestas';  
                        $arrayDatos = [ 'id_contribuyente' => $id_contribuyente, 'descripcion' => strtoupper($descripcion), 'direccion'=> strtoupper($direccion), 'id_cp' => $id_cp, 'id_sim' => $id_sim, 'status_apuesta' => $status_apuesta, 'fecha_creacion' => $fecha_creacion];
                        $transaccion = $conn->beginTransaction();

                        if( $conexion->guardarRegistro( $conn, $tabla, $arrayDatos ) ) {

                                    $transaccion->commit();
                                    $tipoError = 0;
                                    $msg = "REGISTRATION SUCCESSFUL ! .... Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/view")."&id=$id'>";
                                    return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                        } else {
                                    $transaccion->rollBack();
                                    $tipoError = 1;
                                    $msg = "ERROR OCCURRED !....Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/create")."'>";
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
    *   Metodo actionUpdate(), permite realizar las modificaciones de las apuestas licitas registrados.
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
                                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/view-historico")."&id=$id'>";
                                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                            } else {
                                        $transaccion->rollBack();
                                        $tipoError = 1;
                                        $msg = "ERROR OCCURRED !....Wait";
                                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/update")."&id=$id'>";
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
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/view")."&id=$id'>";
                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                } else {
                            $transaccion->rollBack();
                            $tipoError = 1;
                            $msg = "ERROR OCCURRED!....Wait";
                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("apuestalicita/apuestas-licita/update")."&id=$id'>";
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
        if( ( $model = ApuestasLicitaForm::findOne( $id ) ) !== null ){
                    
                    return $model;
        } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}