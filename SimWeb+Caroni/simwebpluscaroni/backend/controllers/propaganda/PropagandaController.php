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
 *      @file PropagandaController.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 18-08-2015
 * 
 *      @class PropagandaController
 *      @brief Clase permite gestionar las propagandas ( crear, modificar y inactivar ).
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

namespace backend\controllers\propaganda;
session_start();
error_reporting(0);

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\conexion\ConexionController;
use backend\models\propaganda\DisableForm;
use backend\models\propaganda\PropagandaForm;
use backend\models\propaganda\PropagandaSearch;
use backend\models\operacionbase\OperacionBase;
use backend\controllers\operacionbase\OperacionBaseController;


class PropagandaController extends Controller
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
    *   Metodo actionIndex(), retorna el listado principal de las propagandas, a la vista index.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function actionIndex()
    {   
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
                    $searchModel = new PropagandaSearch();
                    $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
                    return $this->render( 'index', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
            
    }
    
    /** 
    *   Metodo actionIndex1(), retorna el listado principal de las propagandas, a la vista index.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function actionDesincorporacion()
    {    
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
                    $searchModel = new PropagandaSearch();
                    $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
                    return $this->render( 'desincorporacion', [ 'searchModel' => $searchModel, 'dataProvider' => $dataProvider ] );
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }
  
	/**
    * Metodo actionView(), retorna una vista para visualizar los datos modificados o registrados de las propagandas
    * @param string $id
    * @return mixed
    */
    public function actionView( $id )
    {	
        $conexion = new ConexionController();
        $conn = $conexion->initConectar( 'db' );
        $conn->open();  
	
        if( $id == '' ) {
			
					$sql_max = " SELECT MAX(id_impuesto) AS id_impuesto FROM propagandas WHERE inactivo = 0 ";
					$id = $conn->createCommand( $sql_max )->queryAll();
					$id = $id[0]["id_impuesto"];
					$create = 1;
		} else {
					$id = $id;
		}
	
        $sql = " SELECT A.id_impuesto, A.ano_impositivo, B.razon_social, C.descripcion AS clase, D.descripcion AS uso, A.cantidad_tiempo, A.fecha_desde, A.fecha_fin, E.descripcion AS tiempo, A.direccion, A.observacion, ";
		$sql.= " A.cantidad_base, F.descripcion AS base, A.cantidad_propagandas, A.cigarros, A.bebidas_alcoholicas, A.idioma, A.id_sim, H.descripcion AS tipo, I.descripcion AS medio, J.descripcion AS transporte, A.id_cp ";
        $sql.= " FROM propagandas A, contribuyentes B, clases_propagandas C, usos_propagandas D, tiempos E, bases_calculos F, tipos_propagandas H, medios_difusion I, medios_transportes J";
        $sql.= " WHERE A.id_impuesto = {$id} AND A.id_contribuyente = B.id_contribuyente AND A.clase_propaganda = C.clase_propaganda AND A.uso_propaganda = D.uso_propaganda AND A.id_tiempo = E.id_tiempo ";
		$sql.= " AND A.base_calculo = F.base_calculo AND A.tipo_propaganda = H.tipo_propaganda AND A.medio_difusion = I.medio_difusion AND A.medio_transporte = J.medio_transporte";
        $model = $conn->createCommand( $sql )->queryAll();
            
        return $this->render( 'view', [ 'model' => $model, 'create' => $create ] );
    }
	
  
    /** 
    *   Metodo actionSearch(), retorna los catalagos de las propagandas dependiendo del @param $ano_impositivo, a consultar.
    * 	@param $conn, instancia de conexion a base de datos.
    * 	@param $btn, obtiene el valor del boton.
    *   @param $ano_impositivo, integer indica el año impositivo de la propaganda, cero (0) no indica nada.
    */
    public function actionSearch()
    {
        $model = new DisableForm();
        $btn = Yii::$app->request->post( 'btn' );
        
        if( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) {
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate( $model );
        }
        
        if( $btn == 'search' ) {
          
            if ( $model->validate() ){ 
                    
                $conexion = new ConexionController();
                $conn = $conexion->initConectar( 'db' );
                $conn->open();
                $ano_impositivo = $model->ano_impo;
                    
                if( is_numeric( $ano_impositivo ) ) {
                    
                    $sql = " SELECT B.descripcion AS propaganda, D.cigarro, D.alcohol, D.idioma, C.descripcion AS base, D.id_ordenanza, D.monto_aplicar ";
                    $sql.= " FROM propagandas A, tipos_propagandas B, bases_calculos C, tarifas_propagandas D WHERE A.tipo_propaganda = B.tipo_propaganda ";
                    $sql.= " AND A.base_calculo = C.base_calculo AND A.tipo_propaganda = D.tipo_propaganda AND A.inactivo = '0' AND A.ano_impositivo={$ano_impositivo} ORDER BY id_impuesto";
                    $command = $conn->createCommand( $sql )->queryAll();
                    
                    if( count( $command ) > 0 ) {
                 
                                return $this->render( 'catalago-propganada', [ 'model' => $model, 'command' => $command ] );
                    } else { 
                                $tipoError = 1;
                                $msg = "ERROR OCCURRED!....Wait";
                                $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/search")."'>";
                                return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                    }
                        $this->conexion->close();   
                }
            } else {
                        $model->getErrors();
                        return $this->render( 'search', [ 'model' => $model ] );
            }   
        } else {
                    return $this->render( 'search', [ 'model' => $model ] );
        }
    }
    
    /** 
    *   Metodo actionCreate(), permite realizar los registros de las propagandas.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $btn, obtiene el valor del boton.
    * 	@param $id_contribuyente, integer.
    * 	@param $ano_impositivo, integer.
    * 	@param $direccion, varchar. 
    *	@param $id_cp, integer.
    *   @param $clase_propaganda, integer.
    *   @param $tipo_propaganda, integer.
    *   @param $uso_propaganda, integer.
    *   @param $medio_difusion, integer.
    *   @param $medio_transporte, integer.
    *   @param $fecha_desde, date.
    *   @param $cantidad_tiempo, double.
    *   @param $id_tiempo, integer.
    *   @param $inactivo, integer.
    *   @param $id_sim, integer.
    *   @param $cantidad_base, double.
    *   @param $base_calculo, integer.
    *   @param $cigarros, integer.
    *   @param $bebidas_alcoholicas, integer.
    *   @param $cantidad_propagandas, integer.
    *   @param $planilla, integer.
    *   @param $idioma, integer.
    *   @param $observacion, varchar.
    *   @param $fecha_fin, date.
    *   @param $fecha_guardado, date.
    */
    public function actionCreate() 
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
           
            $model = new PropagandaForm();  
            $btn = Yii::$app->request->post( 'btn' );
            $msg = '';

            if( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) {

                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate( $model );
            }

            if ( $btn == 'crud' ){

                if( $model->base_calculo == '1' || $model->base_calculo == '5' ) { 

                    $model->cantidad_propagandas='0';
                } 

                    if ( $model->validate() ) {

                        $conexion = new ConexionController();
                        $conn = $conexion->initConectar( 'db' );
                        $conn->open();
                        $id_contribuyente = $model->id_contribuyente;
                        $ano_impositivo = $model->ano_impo;
                        $direccion = $model->direccion;
                        $id_cp = $model->id_cp;
                        $clase_propaganda = $model->clase_propaganda;
                        $tipo_propaganda = $model->tipo_propaganda;
                        $uso_propaganda = $model->uso_propaganda;
                        $medio_difusion = $model->medio_difusion;
                        $medio_transporte = $model->medio_difusion;
                        $fecha_desde = $model->fecha_desde;
                        $cantidad_tiempo = $model->cantidad_tiempo;
                        $id_tiempo = $model->id_tiempo;
                        $inactivo = $model->inactivo;
                        $id_sim = $model->id_sim;
                        $cantidad_base = $model->cantidad_base;
                        $base_calculo = $model->base_calculo;
                        $cigarros = $model->cigarros;
                        $bebidas_alcoholicas = $model->bebidas_alcoholicas;
                        $cantidad_propagandas = $model->cantidad_propagandas;
                        $planilla = $model->planilla;
                        $idioma = $model->idioma;
                        $observacion = $model->observacion;
                        $fecha_fin = $model->fecha_fin;
                        $fecha_guardado = $model->fecha_guardado;
                        $cantidad_base = str_replace( '.', '', $cantidad_base );
                        $cantidad_base = str_replace( ',', '.', $cantidad_base );

                        $tabla = 'propagandas';
                        $arrayDatos = [ 'id_contribuyente' => $id_contribuyente, 'ano_impositivo' => $ano_impositivo, 'direccion' => strtoupper($direccion), 'id_cp' => $id_cp, 'clase_propaganda' => $clase_propaganda, 'tipo_propaganda' => $tipo_propaganda, 'uso_propaganda' => $uso_propaganda, 'medio_difusion' => $medio_difusion, 'medio_transporte' => $medio_transporte, 'fecha_desde' => $fecha_desde, 'cantidad_tiempo' => $cantidad_tiempo, 'id_tiempo' => $id_tiempo, 'inactivo' => $inactivo, 'id_sim' => $id_sim, 'cantidad_base' => $cantidad_base, 'base_calculo' => $base_calculo, 'cigarros' => $cigarros, 'bebidas_alcoholicas' => $bebidas_alcoholicas, 'cantidad_propagandas' => $cantidad_propagandas, 'planilla' => $planilla, 'idioma' => $idioma, 'observacion' => strtoupper($observacion), 'fecha_fin' => $fecha_fin, 'fecha_guardado' => $fecha_guardado ];
                        $transaccion = $conn->beginTransaction();

                            if( $conexion->guardarRegistro( $conn, $tabla, $arrayDatos ) ) {

                                        $transaccion->commit();
                                        $tipoError = 0;
                                        $msg = "REGISTRATION SUCCESSFUL ! .... Wait";
										$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/view")."&id=$id'>";
                                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                            } else {
                                        $transaccion->rollBack();
                                        $tipoError = 1;
                                        $msg = "ERROR OCCURRED !....Wait";
                                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/create")."'>";
                                        return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                            }
                                        $this->conexion->close();  
                    } else { 
                                $model->getErrors();
                                return $this->render( 'create', [ 'model' => $model, 'msg' => $msg ] );
                    }   
            } else {
                        return $this->render( 'create', [ 'model' => $model, 'msg' => $msg ] );
            }
        }  else {
                    echo "No hay Contribuyente!!!...";
        }
    }

    /** 
    *   Metodo actionUpdate(), permite realizar las modificaciones de las propagandas registradas.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $btn, obtiene el valor del boton.
    * 	@param $id_contribuyente, integer.
    * 	@param $ano_impositivo, integer.
    * 	@param $direccion, varchar. 
    *	@param $id_cp, integer.
    *   @param $clase_propaganda, integer.
    *   @param $tipo_propaganda, integer.
    *   @param $uso_propaganda, integer.
    *   @param $medio_difusion, integer.
    *   @param $medio_transporte, integer.
    *   @param $fecha_desde, date.
    *   @param $cantidad_tiempo, double.
    *   @param $id_tiempo, integer.
    *   @param $inactivo, integer.
    *   @param $id_sim, integer.
    *   @param $cantidad_base, double.
    *   @param $base_calculo, integer.
    *   @param $cigarros, integer.
    *   @param $bebidas_alcoholicas, integer.
    *   @param $cantidad_propagandas, integer.
    *   @param $planilla, integer.
    *   @param $idioma, integer.
    *   @param $observacion, varchar.
    *   @param $fecha_fin, date.
    *   @param $fecha_guardado, date.
    */ 
    public function actionUpdate( $id ) 
    {   
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
            $model = $this->findModel( $id );
            $btn = Yii::$app->request->post( 'btn' );

             if( $model->base_calculo == '1' || $model->base_calculo == '5' ) { 

                $model->cantidad_propagandas='0';
            } 

            if( $model->load( Yii::$app->request->post() ) && $model->save() && $btn=='crud' ) {

                $conexion = new ConexionController();
                $conn = $conexion->initConectar( 'db' );
                $conn->open();
                $id_contribuyente = $model->id_contribuyente;
                $ano_impositivo = $model->ano_impo;
                $direccion = $model->direccion;
                $id_cp = $model->id_cp;
                $clase_propaganda = $model->clase_propaganda;
                $tipo_propaganda = $model->tipo_propaganda;
                $uso_propaganda = $model->uso_propaganda;
                $medio_difusion = $model->medio_difusion;
                $medio_transporte = $model->medio_difusion;
                $fecha_desde = $model->fecha_desde;
                $cantidad_tiempo = $model->cantidad_tiempo;
                $id_tiempo = $model->id_tiempo;
                $inactivo = $model->inactivo;
                $id_sim = $model->id_sim;
                $cantidad_base = $model->cantidad_base;
                $base_calculo = $model->base_calculo;
                $cigarros = $model->cigarros;
                $bebidas_alcoholicas = $model->bebidas_alcoholicas;
                $cantidad_propagandas = $model->cantidad_propagandas;
                $planilla = $model->planilla;
                $idioma = $model->idioma;
                $observacion = $model->observacion;
                $fecha_fin = $model->fecha_fin;
                $fecha_guardado = $model->fecha_guardado;
                $cantidad_base = str_replace( '.', '', $cantidad_base );
                $cantidad_base = str_replace( ',', '.', $cantidad_base );

                $tabla = 'propagandas';  
                $arrayDatos = [ 'id_contribuyente' => $id_contribuyente, 'ano_impositivo' => $ano_impositivo, 'direccion' => strtoupper($direccion), 'id_cp' => $id_cp, 'clase_propaganda' => $clase_propaganda, 'tipo_propaganda' => $tipo_propaganda, 'uso_propaganda' => $uso_propaganda, 'medio_difusion' => $medio_difusion, 'medio_transporte' => $medio_transporte, 'fecha_desde' => $fecha_desde, 'cantidad_tiempo' => $cantidad_tiempo, 'id_tiempo' => $id_tiempo, 'inactivo' => $inactivo, 'id_sim' => $id_sim, 'cantidad_base' => $cantidad_base, 'base_calculo' => $base_calculo, 'cigarros' => $cigarros, 'bebidas_alcoholicas' => $bebidas_alcoholicas, 'cantidad_propagandas' => $cantidad_propagandas, 'planilla' => $planilla, 'idioma' => $idioma, 'observacion' => strtoupper($observacion), 'fecha_fin' => $fecha_fin, 'fecha_guardado' => $fecha_guardado ];
                $arrayCondition = [ 'id_impuesto' => $id ]; 
                $transaccion = $conn->beginTransaction();

                if( $conexion->modificarRegistro( $conn, $tabla, $arrayDatos, $arrayCondition ) ) {   

                            $transaccion->commit(); 
                            $tipoError = 0;
                            $msg = "SUCCESSFULLY MODIFIED! .... Wait";
							$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/view")."&id=$id'>";
                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                } else {
                            $transaccion->rollBack();
                            $tipoError = 1;
                            $msg = "ERROR OCCURRED!....Wait";
							$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/update")."&id=$id'>";
                            return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                }   
                            $this->conexion->close();
            } else {
                        return $this->render('update', ['model' => $model]);
            }
        }  else {
                    echo "No hay Contribuyente!!!...";
        }    
    }
        
    /** 
    *   Metodo actionDisable(), permite realizar las inactivaciones de las propagandas registradas de forma masivo o individual.
    *   @param $conn, instancia de conexion a base de datos.
    * 	@param $msg, obtiene el valor del mensaje se muestra al retornar a la vista indicada.
    * 	@param $btn, obtiene el valor del boton.
    * 	@param $id, array que obtine los id impuestos seleccionados para ralizar la inactivacion.
    * 	@param $selections, array que contiene los id impuesto ( Todos los seleccionados ), tipo de inactivacion y observaciones.
    */   
    public function actionDisable()
    {
        if ( isset( $_SESSION['idContribuyente'] ) ) {
            
            $model = new DisableForm();
            $conexion = new ConexionController();
            $conn = $conexion->initConectar( 'db' );
            $conn->open();
            $id = Yii::$app->request->post( 'selection' );
            $btn = Yii::$app->request->post( 'btn' );

            if( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) {

                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate( $model );
            }

            if( $btn == 'save' ) {

                if ( $model->validate() ){

                    $selections = Yii::$app->request->post( 'DisableForm' );

                    /**
                    *    Condicional que limita que que el @param $selections, sea mayor a tres, de lo contrario no hay seleccion los id impuestos.
                    */
                    if( count( $selections ) > 3 ){

                        $operacion = new DisableForm();
                        $resultadoOperacion = $operacion->anularPropaganda( $conexion, $conn, $selections );

                        /**
                        *    Condicional que permite especificar el redireccionamiento de los mensaje de la inactivacion.
                        */
                        if ( $resultadoOperacion == true ){

                                    $tipoError = 0;
                                    $msg = "SUCCESSFULLY MODIFIED ! .... Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/desincorporacion")."'>";
                                    return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                        } else {
                                    $tipoError = 1;
                                    $msg = "INACTIVATION UNREALIZED ! ....Wait";
                                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/desincorporacion")."'>";
                                    return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                        }
                    } else {
                                $tipoError = 1;
                                $msg = "ERROR OCCURRED, NOT SELECTED COMMERCIALS TO INACTIVATE !....Wait";
                                $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("propaganda/propaganda/desincorporacion")."'>";
                                return $this->render( '/mensaje/mensaje', [ 'msg' => $msg, 'url' => $url, 'tipoError' => $tipoError ] );
                    }
                } else {
                            $model->getErrors();
                            $selections= Yii::$app->request->post('DisableForm');
                            return $this->render( 'disable', [ 'model' => $model, 'selections' => $selections ] );
                }   
            } else {
                        return $this->render( 'disable', [ 'model' => $model, 'id' => $id ] );
            }
        }  else {
                    echo "No hay Contribuyente!!!...";
        }  
    }   
    
    protected function findModel( $id )
    {
        if( ( $model = PropagandaForm::findOne( $id ) ) !== null ){
                    
                    return $model;
        } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}