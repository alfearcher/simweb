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
 *  @file InmueblesConsulta.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class InmueblesConsulta
 *  @brief Clase que permite acceder a los datos de la tabla inmuebles. 
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  tableName
 *  
 *
 *  @inherits
 *  
 */ 

/**
 * This is the model class for table "inmuebles".
 *
 * @property integer $id_impuesto
 * @property string $id_contribuyente
 * @property integer $ano_inicio
 * @property string $direccion
 * @property integer $liquidado
 * @property integer $manzana_limite
 * @property integer $lote_1
 * @property integer $lote_2
 * @property string $nivel
 * @property integer $lote_3
 * @property string $av_calle_esq_dom
 * @property string $casa_edf_qta_dom
 * @property string $piso_nivel_no_dom
 * @property string $apto_dom
 * @property string $tlf_hab
 * @property string $medidor
 * @property string $id_sim
 * @property string $observacion
 * @property integer $inactivo
 * @property string $catastro
 * @property string $id_habitante
 * @property integer $tipo_ejido
 * @property string $propiedad_horizontal
 * @property string $estado_catastro
 * @property string $municipio_catastro
 * @property string $parroquia_catastro
 * @property string $ambito_catastro
 * @property string $sector_catastro
 * @property string $manzana_catastro
 * @property string $parcela_catastro
 * @property string $subparcela_catastro
 * @property string $nivel_catastro
 * @property string $unidad_catastro
 */
namespace backend\models\inmueble;

use Yii;


class TiposInmuebles extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'tipos_inmuebles';
    }

    
}
