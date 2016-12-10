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
 *      @file GruposTrabajoController.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 04-08-2015
 * 
 *      @class GruposTrabajoController
 *      @brief Clase permite gestionar los grupos de trabajo ( crear, modificar y inactivar ).
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


namespace backend\controllers\grupotrabajo;
error_reporting(0);

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\conexion\ConexionController;
use backend\models\grupotrabajo\GruposTrabajoForm;
use backend\models\grupotrabajo\GruposTrabajoSearch;
 

class GruposTrabajoController extends Controller
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
        $searchModel = new GruposTrabajoSearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        return $this->render( 'index', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
    }
    
    /** 
    *   Metodo actionIndex(), retorna el listado principal de los grupos de trabajo, a la vista index.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function actionDesincorporacion()
    {    
        $searchModel = new GruposTrabajoSearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        return $this->render( 'desincorporacion', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
    }
  
    /**
    * Metodo actionView(), retorna una vista para visualizar los datos modificados o registrados de los grupos de trabajos
    * @param string $id
    * @return mixed
    */
    public function actionView( $id )
    {
        $conexion = new ConexionController();
        $conn = $conexion->initConectar( 'db' );
        $conn->open();  
        
		if( $id == '' ) {
			
					$sql_max = " SELECT MAX(id_grupo) AS id_grupo FROM grupos_trabajo WHERE inactivo = 0";
					$id = $conn->createCommand( $sql_max )->queryAll();
					$id = $id[0]["id_grupo"];
					$create = 1;
		} else {
					$id = $id;
		}
		
        $sql = " SELECT A.id_grupo, A.descripcion, A.fecha, A.inactivo, B.descripcion AS unidad, C.descripcion AS departamento";
        $sql.= " FROM grupos_trabajo A, unidades_departamentos B, departamentos C ";
        $sql.= " WHERE A.id_departamento = C.id_departamento AND A.id_unidad = B.id_unidad AND A.INACTIVO = '0' AND A.id_grupo= {$id}";
        $model = $conn->createCommand( $sql )->queryAll();
           
        return $this->render( 'view', [ 'model' => $model, 'id' => $id, 'create' => $create ] );
    }
    
    /** 
    *   Metodo actionCreate(), permite realizar los registros de los grupos de trabajo.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $descripcion, varchar.
    * 	@param $id_departamento, integer.
    * 	@param $id_unidad, interger. 
    *	@param $fecha, integer.
    *   @param $inactivo, integer.
    */
    public function actionCreate() 
    {
        $msg = '';
        $model = new GruposTrabajoForm();  
        
        if( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate( $model );
        }
        
        if ( $model->load( Yii::$app->request->post() ) ) {
           
                if ( $model->validate() ) {

                    $conexion = new ConexionController();
                    $conn = $conexion->initConectar( 'db' );
                    $conn->open();
                    $descripcion = $model->descripcion;
                    $id_departamento = $model->id_departamento;
                    $id_unidad = $model->id_unidad;
                    $fecha = $model->fecha;
                    $inactivo = $model->inactivo;
                    
                    $tabla = 'grupos_trabajo';  
                    $arrayDatos = [ 'descripcion'=> strtoupper($descripcion), 'id_departamento'=> $id_departamento, 'id_unidad'=> $id_unidad, 'fecha'=> $fecha, 'inactivo'=> $inactivo ];
                    $transaccion = $conn->beginTransaction();
                        
                    if( $descripcion != '' ) { 
                        
                        $resultadoOperacion = $model->consultarGruposTrabajo( $conexion, $conn, $descripcion );            
                    }
                    
                    if( count( $resultadoOperacion ) == 0 ) {  
                        
                                if( $conexion->guardarRegistro( $conn, $tabla, $arrayDatos ) ) {

                                            $transaccion->commit();
                                            $tipoError = 0;
                                            $msg = "REGISTRATION SUCCESSFUL ! .... Wait";
                                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("grupotrabajo/grupos-trabajo/view")."&id=$id'>";
                                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                                } else {
                                            $transaccion->rollBack();
                                            $tipoError = 1;
                                            $msg = "ERROR OCCURRED !....Wait";
                                            $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("grupotrabajo/grupos-trabajo/create")."'>";
                                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                                }
                        
                    } else {
                                $model->addError( 'descripcion', Yii::t( 'backend', 'Group description already exists' ) );
                                return $this->render( 'create', [ 'model' => $model, 'msg' => $msg ] ); 
                    }
                            $this->conexion->close();  
                } else {
                            $model->getErrors();
                            return $this->render( 'create', [ 'model' => $model, 'msg' => $msg ] ); 
                }   
        } else {
                    return $this->render( 'create', [ 'model' => $model, 'msg' => $msg ] );
        }
    }

    /** 
    *   Metodo actionUpdate(), permite realizar las modificaciones de los grupos de trabajo registrados.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $descripcion, varchar.
    * 	@param $id_departamento, integer.
    * 	@param $id_unidad, interger. 
    */  
    public function actionUpdate( $id ) 
    {
        $model = $this->findModel( $id );
        
        if( $model->load( Yii::$app->request->post() ) ) {
                    
            $conexion = new ConexionController();
            $conn = $conexion->initConectar( 'db' );
            $conn->open();
            $descripcion = $model->descripcion;
            $id_departamento = $model->id_departamento;
            $id_unidad = $model->id_unidad;
            
            $tabla = 'grupos_trabajo';  
            $arrayDatos = [ 'descripcion' => strtoupper($descripcion), 'id_departamento' => $id_departamento, 'id_unidad' => $id_unidad ]; 
            $arrayCondition = [ 'id_grupo' => $id ]; 
            $transaccion = $conn->beginTransaction();
            
            if( $conexion->modificarRegistro( $conn, $tabla, $arrayDatos, $arrayCondition ) ) {

                        $transaccion->commit(); 
                        $tipoError = 0;
                        $msg = "SUCCESSFULLY MODIFIED! .... Wait";
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("grupotrabajo/grupos-trabajo/view")."&id=$id'>";
                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
            } else {
                        $transaccion->rollBack();
                        $tipoError = 1;
                        $msg = "ERROR OCCURRED!....Wait";
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("grupotrabajo/grupos-trabajo/update")."&id=$id'>";
                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
            }
                    $this->conexion->close();
        } else {
                    return $this->render( 'update', [ 'model' => $model ] );
        }
    }

    /** 
    *   Metodo actionDisable(), permite realizar las inactivacion de los grupos de trabajo de forma individual.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $btn, obtiene el valor del boton.
    * 	@param $id, interger que obtiene el numero de id de grupos de trabajo para ralizar la inactivacion.
    * 	@param $inactivo, interger que obtiene el valor de la inactivacion.
    */   
    public function actionDisable( $id )  
    {
        $model = $this->findModel( $id );
        $conexion = new ConexionController();
        $conn = $conexion->initConectar( 'db' );
        $conn->open();
        $inactivo = $model->inactivo;
        
     
        if( $inactivo == '0' ) { 
                    
                    $inactivo = 1;
        } else {
                    $inactivo = 0;
        }
       
        $tabla = 'grupos_trabajo'; 
        $arrayDatos = [ 'inactivo' => $inactivo ]; 
        $arrayCondition = [ 'id_grupo' => $id ]; 
        $transaccion = $conn->beginTransaction();
        
        if( $conexion->modificarRegistro( $conn, $tabla, $arrayDatos, $arrayCondition ) ) {
                        
            $transaccion->commit(); 
            return $this->redirect(['desincorporacion']);
        }   
            $this->conexion->close();
    }
    
    protected function findModel( $id )
    {
        if( ( $model = GruposTrabajoForm::findOne( $id ) ) !== null ){
                    
                    return $model;
        } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}