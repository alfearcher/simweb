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
 *  @file CambioOtrosDatosInmueblesForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 08-03-2016
 * 
 *  @class CambioOtrosDatosInmueblesForm
 *  @brief Clase que permite validar cada uno de los datos del formulario de cambio de otros datos de inmuebles 
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

namespace frontend\models\inmueble\cambiootrosdatos;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;


class CambioOtrosDatosInmueblesForm extends \yii\db\ActiveRecord
{
     
    public $validacion;
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
            [['direccion'],'required','message' => Yii::t('backend', 'Street Address cannot be blank')],
            [['ano_inicio', 'manzana_limite',  'inactivo', 'id_habitante', 'tipo_ejido'], 'integer','message' => Yii::t('backend', 'only integers')],
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
            'id_impuesto' => Yii::t('app', 'Id Impuesto'),
            'id_contribuyente' => Yii::t('app', 'Id Contribuyente'),
            'ano_inicio' => Yii::t('app', 'Ano Inicio'),
            'direccion' => Yii::t('app', 'Direccion'),
            'liquidado' => Yii::t('app', 'Liquidado'),
            'manzana_limite' => Yii::t('app', 'Manzana Limite'),
            'lote_1' => Yii::t('app', 'Lote 1'),
            'lote_2' => Yii::t('app', 'Lote 2'),
            'nivel' => Yii::t('app', 'Nivel'),
            'lote_3' => Yii::t('app', 'Lote 3'),
            'av_calle_esq_dom' => Yii::t('app', 'Av Calle Esq Dom'),
            'casa_edf_qta_dom' => Yii::t('app', 'Casa Edf Qta Dom'),
            'piso_nivel_no_dom' => Yii::t('app', 'Piso Nivel No Dom'),
            'apto_dom' => Yii::t('app', 'Apto Dom'),
            'tlf_hab' => Yii::t('app', 'Tlf Hab'),
            'medidor' => Yii::t('app', 'Medidor'),
            'id_sim' => Yii::t('app', 'Id Sim'),
            'observacion' => Yii::t('app', 'Observacion'),
            'inactivo' => Yii::t('app', 'Inactivo'),
            'catastro' => Yii::t('app', 'Catastro'),
            'id_habitante' => Yii::t('app', 'Id Habitante'),
            'tipo_ejido' => Yii::t('app', 'Tipo Ejido'),
            'propiedad_horizontal' => Yii::t('app', 'Propiedad Horizontal'),
            'estado_catastro' => Yii::t('app', 'Estado Catastro'),
            'municipio_catastro' => Yii::t('app', 'Municipio Catastro'),
            'parroquia_catastro' => Yii::t('app', 'Parroquia Catastro'),
            'ambito_catastro' => Yii::t('app', 'Ambito Catastro'),
            'sector_catastro' => Yii::t('app', 'Sector Catastro'),
            'manzana_catastro' => Yii::t('app', 'Manzana Catastro'),
            'parcela_catastro' => Yii::t('app', 'Parcela Catastro'),
            'subparcela_catastro' => Yii::t('app', 'Subparcela Catastro'),
            'nivel_catastro' => Yii::t('app', 'Nivel Catastro'),
            'unidad_catastro' => Yii::t('app', 'Unidad Catastro'),
        ];
    }
}
