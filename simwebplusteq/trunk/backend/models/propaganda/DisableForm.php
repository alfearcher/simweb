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
 *      @file DisableForm.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 18-08-2015
 * 
 *      @class DisableForm
 *      @brief Clase contiene las reglas de negocios ( Etiquetas, validaciones y busqueda ).
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

namespace backend\models\propaganda;
error_reporting(0);

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\conexion\ConexionController;
use backend\controllers\operacionbase\OperacionBaseController;

/**
 * This is the model class for table "propagandas".
 *
 
 * @property  msgError $msgError
 * @property  ano_impo $ano_impo
 * @property  ano_impo $ano_impo
 * @property  comentario $comentario
 * @property  causa_desincorporacion $causa_desincorporacion
 */
class DisableForm extends \yii\db\ActiveRecord
{
    public $msgError;
    public $ano_impo;
    public $comentario;
    public $causa_desincorporacion;
    public $l0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'propagandas';
    }

    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
        return [
            [ [ 'causa_desincorporacion', 'comentario', 'ano_impo' ], 'required' ],
        ];
    }

    /**
    *   Metodo attributeLabels(), retorna las etiquetas de los campos.
    */
    public function attributeLabels()
    {
        return [
            'ano_impo' => Yii::t( 'backend', 'Tax Year' ),
            'causa_desincorporacion' => Yii::t( 'backend', 'Because Divestiture' ),
            'comentario' => Yii::t( 'backend', 'Comment' ),
        ];
    }
    
   
    
    /**
    *  Metodo anularPropaganda(), contiene la funcion de inicial el proceso de inactivacion de propagandas.
    *  @param $conn, instancia de conexion a base de datos.
    *  @param type $selections, array que contiene los id_impuesto para iniciar la inactivacion.
    *  @return type $selections, array que contiene los datos enviados desde el formulario disable
    */
    public function anularPropaganda( $conexion, $conn, $selections = [] )
    {   
        /**
        *    Valido que la variable $selections sea un array
        */
        if ( is_array( $selections ) ) {
            
            /**
            *   @param type $cont (Contador inicializado en 0)
            */
            $cont = 0;
            foreach ( $selections as $selection) {
                
                /**
                *   @param type $c, interger toma el valor del contador para evitar errores al momento de incrementar las variables dinamicas.
                *   @param type $ids, array que obtiene el array de los id_impuesto, que son necesario para iniciar la inactivacion.
                *   @param type $observacion, varchar que obtiene la observacion de la inactivacion.
                *   @param type $causa_desincorporacion, interger que obtiene el id de la causa de la desincorporacion.
                *   @param type $inactivo, interger variable seteada con el valor 1 hace referencia que esta inactivo.
                *   @param type $impuesto, interger seteada con el valor 4 hace referencia que es solo propaganda.
                *   @param type $fecha_hora, datetime seteada con el valor de la fecha y hora de la inactivacion.
                *   @param type $usuario, varchar seteada con el usuario de SESION.
                *   @param type $condicion, interger seteada con los id_impuesto solamente para condicionar el ciclo de no leer todo el array.
                * 
                */
                $c = $cont++; 
                $ids = $selections[$c];
                $observacion = $selections['comentario'];
                $causa_desincorporacion = ['causa_desincorporacion'];
                $inactivo = 1;
                $impuesto = 4;
                $fecha_hora = date('Y-m-d H:i:s');
                $usuario = Yii::$app->user->identity->username;
                $condicion = $selections[$c];
                
                /**
                *   Consulta para obtener el id_contribuyente de cada una de las propagandas seleccionada para inactivar.
                */
                $consulta = PropagandaForm::find()->where( [ 'id_impuesto' => $ids ] )->andwhere( [ 'inactivo' => '0' ] )->orderBy( 'id_impuesto' )->all();
                
                foreach ( $consulta as $row ) {
                
                    $id_contribuyente=$row->id_contribuyente;
                }
                
                /**
                *   Parametros para realizar la inactivacion de las propagandas
                */
                $tabla0 = 'desincorporaciones'; 
                $arrayDatos0 = [ 'id_contribuyente' => $id_contribuyente, 'id_impuesto' => $ids, 'impuesto' => $impuesto, 'causa_desincorporacion' => $causa_desincorporacion, 'observacion' => strtoupper($observacion), 'usuario' => $usuario, 'fecha_hora' => $fecha_hora, 'inactivo' => $inactivo ];
                $tabla1 = 'propagandas';  
                $arrayDatos1 = [ 'inactivo' => $inactivo ];
                $arrayCondition1 = [ 'id_impuesto' => $ids ];
                
                    /**
                    *   Condicional para limitar que el foreach solo recorra la cantidad de id_impuesto
                    *   y no todo el array
                    */
                    if ( $c <= $condicion ) {
                        
                        /**
                        *   Verificos que los ids sean solo un array numerico para proceder a la
                        *   inactivacion, de los contrario salgo del cilo retornando false
                        */
                        if ( is_numeric( $ids )) {
                        
                            if ( $ids > 0 ) {
                                
                                /**
                                *    Realizo la inactivacion el la tabla propagandas y la inserccion
                                 *   en la tabla desincorporaciones 
                                */
                                $transaccion = $conn->beginTransaction();
                                if( $conexion->modificarRegistro( $conn, $tabla1, $arrayDatos1, $arrayCondition1 ) ){  
                                    
                                    if( $id_contribuyente != 0 ) { 
                                          
                                        $conexion->guardarRegistro( $conn, $tabla0, $arrayDatos0 );
                                    }
                                     
                                    $resultListarPlanilla = OperacionBaseController::getPlanillaSegunObjeto( $conn, 4, $ids );
                                    
                                    if( is_array( $resultListarPlanilla ) ) { 
                                 
                                        if ( $resultListarPlanilla > 0 ) {
                                         
                                            $resultAnularPlanillas = OperacionBaseController::anularEstasPlanillas( $conn, $resultListarPlanilla, $observacion );
                                                
                                        } else {
                                                    return false;
                                        }
                                    } 
                                            $transaccion->commit();
                                    
                                } else {
                                            $transaccion->rollBack();
                                }
                                    
                            } else {
                                        return false;
                            }
			} else {
                                    return false;
			}
                    }
            } 
                    return true;
	} else {
                    return false;
	}
    }
}