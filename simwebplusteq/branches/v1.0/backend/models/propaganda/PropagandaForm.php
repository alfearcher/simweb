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
 *      @file PropagandaForm.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 18-08-2015
 * 
 *      @class PropagandaForm
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

use Yii;
use backend\models\Contribuyente;
use backend\models\UsosPropaganda;
use backend\models\ClasesPropaganda;


/**
 * This is the model class for table "propagandas".
 *
 * @property string $id_impuesto
 * @property string $id_contribuyente
 * @property string $ano_impositivo
 * @property string $direccion
 * @property string $id_cp
 * @property string $clase_propaganda
 * @property string $tipo_propaganda
 * @property string $uso_propaganda
 * @property string $medio_difusion
 * @property string $medio_transporte
 * @property string $fecha_desde
 * @property double $cantidad_tiempo
 * @property string $id_tiempo
 * @property integer $inactivo
 * @property string $id_sim
 * @property double $cantidad_base
 * @property string $base_calculo
 * @property integer $cigarros
 * @property integer $bebidas_alcoholicas
 * @property string $cantidad_propagandas
 * @property integer $planilla
 * @property integer $idioma
 * @property string $observacion
 * @property string $id_tipo_propaganda
 * @property string $est_mun_parr_cp
 * @property string $ano_impo
 * @property string $fecha_fin
 * @property string $fecha_guardado
 * @property  usoName $usoName
 * @property  ClaseName $claseName
 * @property  ContribuyenteName $contribuyenteName
 * @property  InactivoName $inactivoName
 */
class PropagandaForm extends \yii\db\ActiveRecord
{
    public $id_tipo_propaganda;
    public $est_mun_parr_cp;
    public $ano_impo;
    public $selection;

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
                    [ [ 'id_impuesto', 'id_contribuyente', 'ano_impositivo', 'id_cp', 'clase_propaganda', 'tipo_propaganda', 'uso_propaganda', 'medio_difusion', 'medio_transporte', 'id_tiempo', 'inactivo', 'id_sim', 'cigarros', 'bebidas_alcoholicas', 'cantidad_propagandas', 'planilla', 'idioma' ], 'integer' ],
                    [ [ 'clase_propaganda', 'id_contribuyente', 'clase_propaganda', 'tipo_propaganda', 'uso_propaganda', 'id_tiempo', 'cantidad_propagandas', 'fecha_desde', 'cantidad_tiempo', 'cantidad_base', 'base_calculo','ano_impo', 'medio_difusion', 'id_cp' ], 'required' ],
                    [ [ 'direccion', 'fecha_desde', 'fecha_fin', 'fecha_guardado', 'observacion', 'selection' ], 'safe' ],
                    [ [ 'cantidad_tiempo' ], 'number' ],
                ];
    }

    /**
    *   Metodo attributeLabels(), retorna las etiquetas de los campos.
    */
    public function attributeLabels()
    {
        return  [
                    'id_impuesto' => Yii::t( 'backend', 'Tax Id' ),
                    'id_contribuyente' => Yii::t( 'backend', 'Id Contribuyente' ),
                    'ano_impositivo' => Yii::t( 'backend', 'Tax Year' ),
                    'direccion' => Yii::t( 'backend', 'Address' ),
                    'id_cp' => Yii::t( 'backend', 'Location' ),
                    'clase_propaganda' => Yii::t( 'backend', 'Clase Propaganda' ),
                    'tipo_propaganda' => Yii::t( 'backend', 'Kind' ),
                    'uso_propaganda' => Yii::t( 'backend', 'Use' ),
                    'medio_difusion' => Yii::t( 'backend', 'Through Construction' ),
                    'medio_transporte' => Yii::t( 'backend', 'Transport Means' ),
                    'fecha_desde' => Yii::t( 'backend', 'Start Date' ),
                    'cantidad_tiempo' => Yii::t( 'backend', 'Quantity' ),
                    'id_tiempo' => Yii::t( 'backend', 'Lapse' ),
                    'inactivo' => Yii::t( 'backend', 'Status' ),
                    'id_sim' => Yii::t( 'backend', 'Id Sim'),
                    'cantidad_base' => Yii::t( 'backend', 'Number' ),
                    'base_calculo' => Yii::t( 'backend', 'Base'),
                    'cigarros' => Yii::t( 'backend', 'Cigarettes or Tobacco' ),
                    'bebidas_alcoholicas' => Yii::t( 'backend', 'Alcoholic Beverages' ),
                    'cantidad_propagandas' => Yii::t( 'backend', 'Units' ),
                    'planilla' => Yii::t( 'backend', 'Planilla' ),
                    'idioma' => Yii::t( 'backend', 'Foreign Language' ),
                    'observacion' => Yii::t( 'backend', 'Observation' ),
                    'ano_impo' => Yii::t( 'backend', 'Tax Year' ),
                    'fecha_fin' => Yii::t( 'backend', 'Date End' ),
                    'fecha_guardado' => Yii::t( 'backend', 'Date Creation' ),
                    'usoName' => Yii::t( 'backend', 'Use Advertisement' ),
                    'claseName' => Yii::t( 'backend', 'Kind of Propaganda' ),
                    'contribuyenteName' => Yii::t( 'backend', 'Business Name' ),
                    'inactivoName' => Yii::t( 'backend', 'Status Group' ),
                    'pagoName' => Yii::t( 'backend', 'Payment Status' ),
                    'referencia' => Yii::t( 'backend', 'Cause Desincorparacion' ),
                    'comentario' => Yii::t( 'backend', 'Observation' ),
                    'selection' => Yii::t( 'backend', 'Selection' ),
                ];
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas propagandas y tipos_propagandas, las cuales se relacionan  por su id referencial.
    */
    public function getUso()
    {
        return $this->hasOne( UsosPropaganda::className(), [ 'uso_propaganda' => 'uso_propaganda' ] );
    }
 
    /**
    *   Almacena el campo descripcion de la tabla tipos_propagandas, en una variable GET para retornarla a la vista.
    */
    public function getUsoName() 
    {
        return $this->uso->descripcion;
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas propagandas y clases_propagandas, las cuales se relacionan  por su id referencial.
    */
    public function getClase()
    {
        return $this->hasOne( ClasesPropaganda::className(), [ 'clase_propaganda' => 'clase_propaganda' ] );
    }
    
    /**
    *   Almacena el campo descripcion de la tabla clases_propagandas, en una variable GET para retornarla a la vista.
    */
    public function getClaseName() 
    {
        return $this->clase->descripcion;
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas propagandas y contribuyentes, las cuales se relacionan  por su id referencial.
    */
    public function getContribuyente()
    {
        return $this->hasOne( Contribuyente::className(), [ 'id_contribuyente' => 'id_contribuyente' ] );
    }
    
    /**
    *   Almacena el campo descripcion de la tabla contribuyentes, en una variable GET para retornarla a la vista.
    */
    public function getContribuyenteName() 
    {
        return $this->contribuyente->razon_social;
    }
    
    /**
    *   Almacena el campo inactivo de la tabla propagandas, en una variable GET para retornarla a la vista con el valor seteado dependiendo del condicional.
    */
    public function getInactivoName() 
    {
        if( $this->inactivo == '0' ) {
            
                    $this->inactivo = 'ACTIVO';
        } else {
                    $this->inactivo = 'INACTIVO';
        }
                    return $this->inactivo;
    }

    /**
     * [busquedaPropaganda description] metodo que realiza una busqueda en la tabla propagandas
     * @param  [type] $idPropaganda    [description] id de la propaganda del contribuyente
     * @param  [type] $idContribuyente [description] id del contribuyente
     * @return [type]                  [description] retorna la informacion de la propaganda en caso de hacer match, de lo contrario
     * retorna false
     */
    public function busquedaPropaganda($idPropaganda, $idContribuyente)
    {
        
        $buscar = Propaganda::find()
                            ->where([
                            'id_impuesto' => $idPropaganda,
                            'id_contribuyente' => $idContribuyente,
                            'inactivo' => 0,

                                ])
                            ->all();
            if($buscar == true){
                return $buscar;
            }else{
                return false;
            }
    }


}