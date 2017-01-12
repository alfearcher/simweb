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
 *  @file AvaluoCatastralForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-07-2015
 * 
 *  @class AvaluoCatastaralForm
 *  @brief Clase que permite validar cada uno de los datos del formulario de avaluo catastral de inmuebles 
 *  urbanos, se establecen las reglas para los datos a ingresar y se le asigna el nombre de las etiquetas 
 *  de los campos. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  tableName
 *  rules
 *  attributeLabels
 *  catastro_existe
 *
 *  
 *
 *  @inherits
 *  
 */ 

namespace backend\models\inmueble;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;
use backend\models\inmueble\Solvencias;
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
class LinderosForm extends \yii\db\ActiveRecord
{

    public $conn;
    public $conexion;
    public $transaccion;   
    public $metros_construccion;
    public $metros_terreno;
    public $valor_construccion;
    public $valor_terreno;
    public $validacion;
    public $lindero_norte;
    public $lindero_sur;
    public $lindero_este;
    public $lindero_oeste;

   

    public static function tableName()
    {
        return 'historico_avaluos';
    }
 


    public function rules()
    {

        return [ 

            [['id_impuesto'], 'integer','message' => Yii::t('backend', 'only integers')],
            [['lindero_norte','lindero_sur','lindero_este','lindero_oeste'],'required','message' => Yii::t('backend', 'No debe estar vacio el campo')],
            
            
        ]; 
    }

    
    public function attributeLabels()
    {
        return [ 
            'lindero_norte' => Yii::t('backend', 'Lindero Norte'), 
            'lindero_sur' => Yii::t('backend', 'Lindero Sur'),
            'lindero_este' => Yii::t('backend', 'Lindero Este'),
            'lindero_oeste' => Yii::t('backend', 'Lindero Oeste'),
            
        ];  
    }

    
}
