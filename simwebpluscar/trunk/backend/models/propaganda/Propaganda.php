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
use backend\models\TiposPropaganda;


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
class Propaganda extends \yii\db\ActiveRecord
{


    

    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'propagandas';
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
     * [getTipoPropaganda description] Metodo que realiza la busqueda de la descripcion del tipo de propaganda en base a su indice
  *
     * @return [type]       [description] devuelve la descripcion de la propaganda encontrada
     */
    public function getTipoPropaganda()
    {

        return $this->hasOne(TiposPropaganda::className(), ['tipo_propaganda' => 'tipo_propaganda']);
    }
}