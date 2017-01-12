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
 *      @file HistoricoForm.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 21-09-2015
 * 
 *      @class HistoricoForm
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

namespace backend\models\apuestalicita;
error_reporting(0);

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use backend\models\TarifasApuesta;
use common\conexion\ConexionController;

/**
 * This is the model class for table "historico_apuestas".
 *
 * @property  id_impuesto $id_impuesto
 * @property  clase_apuesta $clase_apuesta
 * @property  tipo_apuesta $tipo_apuesta
 * @property  fecha_desde $fecha_desde
 * @property  fecha_hasta $fecha_hasta
 * @property  ano_impositivo $ano_impositivo
 * @property  monto $monto
 * @property  porcentaje $porcentaje
 * 
 */
class HistoricoForm extends \yii\db\ActiveRecord
{   

    public $clase_apuesta; 
    public $tipo_apuesta; 
    public $ano_impositivo; 
    public $monto_apuesta;
    public $fecha_comprobar;
	   
       
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'historico_apuestas';
    }

    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    { 
        return [
            [ [ 'clase_apuesta', 'tipo_apuesta', 'fecha_desde', 'fecha_hasta', 'ano_impositivo', 'monto_apuesta' ], 'required' ],
            [ [ 'id_impuesto' ], 'integer' ],
            [ [ 'fecha_comprobar' ], 'safe' ],
            [ ['fecha_hasta'], 'compare','compareAttribute'=>'fecha_desde','operator'=>'>=' ,'message'=> Yii::t( 'backend', 'inicial date must be less than the end date' ) ] ,
            [ ['fecha_desde'], 'compare','compareAttribute'=>'fecha_comprobar','operator'=>'<=' ,'message'=> Yii::t( 'backend', 'the year of the start date may not be greater than the current year' ) ] ,
            [ ['fecha_hasta'], 'compare','compareAttribute'=>'fecha_comprobar','operator'=>'<=' ,'message'=> Yii::t( 'backend', 'the year of the deadline can not be greater than the current year' ) ] ,
   
           
            ];
    }

    /**
    *   Metodo attributeLabels(), retorna las etiquetas de los campos.
    */
    public function attributeLabels()
    {
        return [
                    'id_impuesto' => Yii::t( 'backend', 'Id Tax' ),
                    'clase_apuesta' => Yii::t( 'backend', 'Bet of Class' ),
                    'tipo_apuesta' => Yii::t( 'backend', 'Bet of Type' ),
                    'fecha_desde' => Yii::t( 'backend', 'Date From' ),
                    'fecha_hasta' => Yii::t( 'backend', 'To Date' ),
                    'ano_impositivo' => Yii::t( 'backend', 'Tax Year' ),
                    'monto_apuesta' => Yii::t( 'backend', 'Rode' ),
                    'porcentaje' => Yii::t( 'backend', 'Percentage' ),
					'fecha_comprobar' => Yii::t( 'backend', 'Date Recorded' ),
        ];
    }
    
    /**
    *  Metodo registrarHistorico(), contiene la funcion inicial para el registro de historicos de apuesta.
    *  @param $conn, instancia de conexion a base de datos.
    *  @param type $datos, array que contiene los datos a registrar en historico_apuestas.
    *  @return type $id_tax, interger.
    *  @param type $clase_apuesta, interger.
    *  @param type $tipo_apuesta, interger.
    *  @param type $fecha_desde, date.
    *  @param type $fecha_hasta, date.
    *  @param type $ano_impositivo, interger.
    *  @param type $monto, interger. 
    */
    public function registrarHistorico( $conexion, $conn, $datos = [] )
    {   
        if( $datos["planilla"] == null ) { 
            
            $datos["planilla"] = 0; 
        }
        
        $id_impuesto = $datos["id_impuesto"];
        $clase_apuesta = $datos["clase_apuesta"];
        $tipo_apuesta = $datos["tipo_apuesta"];
        $fecha_desde = $datos["fecha_desde"];
        $fecha_hasta = $datos["fecha_hasta"];
        $ano_impositivo = $datos["ano_impositivo"];
        $planilla = $datos["planilla"];
        $monto_apuesta = $datos["monto_apuesta"];
        $monto_apuesta = str_replace( '.', '', $monto_apuesta );
        $monto_apuesta = str_replace( ',', '.', $monto_apuesta );
        
        $consulta = TarifasApuesta::find()->where( [ 'ano_impositivo' => $ano_impositivo ] )->andwhere( [ 'clase_apuesta' => $clase_apuesta ] )->andwhere( [ 'tipo_apuesta' => $tipo_apuesta ] )->orderBy( 'id_tarifa_apuesta' )->all();
        $id_tarifa_apuesta = $consulta[0]['id_tarifa_apuesta'];
        
        $tabla = 'historico_apuestas';  
        $arrayDatos = [ 'id_impuesto' => $id_impuesto, 'fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'monto_apuesta' => $monto_apuesta, 'id_tarifa_apuesta' => $id_tarifa_apuesta,'planilla' => $planilla ];
      
        if( $conexion->guardarRegistro( $conn, $tabla, $arrayDatos ) ) {
                  
                    return true;
        } else {
                    return false;
        }
    }
    
    /**
    *  Metodo consultarHistorico(), contiene la funcion inicial para consultar los historicos registrado a las apuestas ilicitas.
    *  @param $conn, instancia de conexion a base de datos.
    *  @param type $id, interger que contiene el id_impuesto a consultar.
    */
    public function consultarHistorico( $conexion, $conn, $id )
    {   
        if( $id != null ) { 
        
            $sql = " SELECT A.id_impuesto, A.fecha_desde, A.fecha_hasta, A.monto_apuesta, A.planilla, A.id_tarifa_apuesta, ";
            $sql.= " C.descripcion AS tipo_apuesta, D.descripcion AS clase_apuesta, B.porcentaje, B.monto_bs, B.monto_ut, A.planilla ";
            $sql.= " FROM historico_apuestas A, tarifas_apuestas B, tipos_apuestas C, clases_apuestas D ";
            $sql.= " WHERE A.id_impuesto = {$id} AND A.id_tarifa_apuesta = B.id_tarifa_apuesta AND B.clase_apuesta = D.clase_apuesta ";
            $sql.= " AND B.tipo_apuesta = C.tipo_apuesta ORDER BY A.id_tarifa_apuesta ";
            
            $consulta_historico = $conn->createCommand( $sql )->queryAll();
            return  $consulta_historico;
        }
    }
}