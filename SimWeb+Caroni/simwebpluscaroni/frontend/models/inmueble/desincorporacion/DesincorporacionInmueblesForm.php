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
 *  @file DesincorporacionInmueblesForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 08-03-2016
 * 
 *  @class DesincorporacionInmueblesForm
 *  @brief Clase que permite validar cada uno de los datos del formulario de desincorporacion de inmuebles 
 *  urbanos, se establecen las reglas para los datos a ingresar y se le asigna el nombre de las etiquetas 
 *  de los campos. 
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
 *
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

namespace frontend\models\inmueble\desincorporacion;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;


class DesincorporacionInmueblesForm extends \yii\db\ActiveRecord
{
    
    public $validacion;
    public $causa;
    public $observacion;
    public $nro_solicitud;
    
    public static function tableName()
    {
        return 'inmuebles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [  
            
            [['ano_inicio', 'manzana_limite',  'inactivo', 'id_habitante', 'tipo_ejido', 'causa'], 'integer','message' => Yii::t('backend', 'only integers')],
            // [['observacion','datosVendedor','inmuebleVendedor'], 'string'], 
            [['direccion', 'observacion'], 'string', 'max' => 255,'message' => Yii::t('backend', 'Only 255 character')],
            [['validacion'], 'string', 'max' => 4,'message' => Yii::t('backend', 'Only 3 character')],
            [['av_calle_esq_dom', 'casa_edf_qta_dom'], 'string', 'max' => 50,'message' => Yii::t('backend', 'Only 50 character')],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25,'message' => Yii::t('backend', 'Only 25 character')],
            [['tlf_hab'], 'string', 'max' => 15,'message' => Yii::t('backend', 'Only 15 character')],
            [['medidor'], 'string', 'max' => 20,'message' => Yii::t('backend', 'Only 20 character')], 
            
            
        ];  
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'causa' => Yii::t('frontend', 'Cause'),
            'id_impuesto' => Yii::t('frontend', 'Id Impuesto'),
            'id_contribuyente' => Yii::t('app', 'Id Contribuyente'),
            'ano_inicio' => Yii::t('frontend', 'Ano Inicio'),
            'direccion' => Yii::t('frontend', 'Direccion'),
            'liquidado' => Yii::t('frontend', 'Liquidado'),
            'manzana_limite' => Yii::t('frontend', 'Manzana Limite'),
            'lote_1' => Yii::t('frontend', 'Lote 1'),
            'lote_2' => Yii::t('frontend', 'Lote 2'),
            'nivel' => Yii::t('frontend', 'Nivel'),
            'lote_3' => Yii::t('frontend', 'Lote 3'),
            'av_calle_esq_dom' => Yii::t('frontend', 'Av Calle Esq Dom'),
            'casa_edf_qta_dom' => Yii::t('frontend', 'Casa Edf Qta Dom'),
            'piso_nivel_no_dom' => Yii::t('frontend', 'Piso Nivel No Dom'),
            'apto_dom' => Yii::t('frontend', 'Apto Dom'),
            'tlf_hab' => Yii::t('frontend', 'Tlf Hab'),
            'medidor' => Yii::t('frontend', 'Medidor'),
            'id_sim' => Yii::t('frontend', 'Id Sim'),
            'observacion' => Yii::t('frontend', 'Observacion'),
            'inactivo' => Yii::t('frontend', 'Inactivo'),
            'catastro' => Yii::t('frontend', 'Catastro'),
            'id_habitante' => Yii::t('frontend', 'Id Habitante'),
            'tipo_ejido' => Yii::t('frontend', 'Tipo Ejido'),
            'propiedad_horizontal' => Yii::t('frontend', 'Propiedad Horizontal'),
            'estado_catastro' => Yii::t('frontend', 'Estado Catastro'),
            'municipio_catastro' => Yii::t('frontend', 'Municipio Catastro'),
            'parroquia_catastro' => Yii::t('frontend', 'Parroquia Catastro'),
            'ambito_catastro' => Yii::t('frontend', 'Ambito Catastro'),
            'sector_catastro' => Yii::t('frontend', 'Sector Catastro'),
            'manzana_catastro' => Yii::t('frontend', 'Manzana Catastro'),
            'parcela_catastro' => Yii::t('frontend', 'Parcela Catastro'),
            'subparcela_catastro' => Yii::t('frontend', 'Subparcela Catastro'),
            'nivel_catastro' => Yii::t('frontend', 'Nivel Catastro'),
            'unidad_catastro' => Yii::t('frontend', 'Unidad Catastro'),
        ];
    }

    public function validarCheck($postCheck)
    {
        if (count($postCheck) > 0){

            return true;
        }else{
            return false;
        }
    }
}
