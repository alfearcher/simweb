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
 *  @file InscripcionInmeblesUrbanosForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-07-2015
 * 
 *  @class InscripcionInmeblesUrbanosForm
 *  @brief Clase que permite validar cada uno de los datos del formulario de inscripcion de inmuebles 
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
 *  rules
 *  attributeLabels
 *  email_existe
 *  username_existe
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

namespace backend\models\usuario;

use Yii;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\conexion\ConexionController;
use backend\models\usuario\PerfilUsuario;

class PerfilUsuarioForm extends Model{
    
    public $conn;
    public $conexion;
    public $transaccion;
    

    Public  $username;
    Public  $ruta;
    Public  $inactivo;
    


    public function rules()
    {

        return [
             [['username', 'ruta'], 'required', 'message' => Yii::t('backend', 'Es requerido llenar el campo')],
             [['username',], 'string', 'max' => 100],
             [['inactivo'], 'integer','message' => Yii::t('backend', 'solo numero entero')],
        ]; 
    }

    
    public function attributeLabels()
    {
        return [
            'id_impuesto' => Yii::t('backend', 'Id Tax'), 
            'id_contribuyente' => Yii::t('backend', 'Id taxpayer'),
            'ano_inicio' => Yii::t('backend', 'Year home'),
            'direccion' => Yii::t('backend', 'Street Address'),
            'liquidado' => Yii::t('backend', 'liquidated'),
            'manzana_limite' => Yii::t('backend', 'Quadrant Limit'),
            'lote_1' => Yii::t('backend', 'Lote 1'),
            'lote_2' => Yii::t('backend', 'Lote 2'),
            'nivel' => Yii::t('backend', 'Level'),
            'lote_3' => Yii::t('backend', 'Lote 3'),
            'av_calle_esq_dom' => Yii::t('backend', 'Avenue Street Corner Home'),
            'casa_edf_qta_dom' => Yii::t('backend', 'House Building Quint Home'),
            'piso_nivel_no_dom' => Yii::t('backend', 'Flat Level Number Home'),
            'apto_dom' => Yii::t('backend', 'Apartment Home'),
            'tlf_hab' => Yii::t('backend', 'Phone Room'),
            'medidor' => Yii::t('backend', 'Meter'),
            'id_sim' => Yii::t('backend', 'Id Sim'),
            'observacion' => Yii::t('backend', 'Observation'),
            'inactivo' => Yii::t('backend', 'Inactive'),
            'catastro' => Yii::t('backend', 'Cadastre'),
            'id_habitante' => Yii::t('backend', 'Id Habitante'),
            'tipo_ejido' => Yii::t('backend', 'Type Ejido'),
            'propiedad_horizontal' => Yii::t('backend', 'Horizontal Property'),
            
            'estado_catastro' => Yii::t('backend', 'Edo.'),
            'municipio_catastro' => Yii::t('backend', 'Mnp.'),
            'parroquia_catastro' => Yii::t('backend', 'Prq.'),
            'ambito_catastro' => Yii::t('backend', 'Amb.'),
            'sector_catastro' => Yii::t('backend', 'Sct.'),
            'manzana_catastro' => Yii::t('backend', 'Mzn.'),
            
            'parcela_catastro' => Yii::t('backend', 'Plot'),
            'subparcela_catastro' => Yii::t('backend', 'Subplot'),
            'nivel_catastro' => Yii::t('backend', 'Level'),
            'unidad_catastro' => Yii::t('backend', 'Unit'),
        ];
    }

   
   

   
    

     

  
}

    
