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
 *  @file CambioNumeroCatastralInmueblesForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-07-2015
 * 
 *  @class CambioNumeroCatastralInmueblesForm
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

namespace backend\models\inmueble;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;

class CambioNumeroCatastralInmueblesForm extends \yii\db\ActiveRecord
{
    
    public $conn;
    public $conexion;
    public $transaccion;   
    public $nivela;
    public $nivelb;
    public $validacion1; 
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
            [['id_contribuyente','ano_inicio', 'liquidado', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'inactivo', 'id_habitante', 'tipo_ejido', 'propiedad_horizontal', 'estado_catastro', 'municipio_catastro', 'parroquia_catastro', 'sector_catastro', 'manzana_catastro', 'parcela_catastro', 'subparcela_catastro', 'unidad_catastro','validacion1'], 'integer','message' => Yii::t('backend', 'only integers'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['estado_catastro', 'municipio_catastro', 'parroquia_catastro', 'ambito_catastro','sector_catastro', 'manzana_catastro','parcela_catastro'], 'required','message' => Yii::t('backend', 'Required field'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['subparcela_catastro', 'unidad_catastro','nivela','nivelb'],'required', 'when'=> function($model){ return $model->propiedad_horizontal == 1 and $this->validacion1 == 1; }, 'message' => Yii::t('backend', 'Required field')],
           // [['observacion','datosVendedor','inmuebleVendedor'], 'string'], 
            [['direccion'], 'string', 'max' => 255,'message' => Yii::t('backend', 'Only 255 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['nivel', 'ambito_catastro'], 'string', 'max' => 4,'message' => Yii::t('backend', 'Only 3 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['av_calle_esq_dom', 'casa_edf_qta_dom'], 'string', 'max' => 50,'message' => Yii::t('backend', 'Only 50 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25,'message' => Yii::t('backend', 'Only 25 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['tlf_hab'], 'string', 'max' => 15,'message' => Yii::t('backend', 'Only 15 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['medidor', 'id_sim'], 'string', 'max' => 20,'message' => Yii::t('backend', 'Only 20 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            [['catastro'], 'string', 'max' => 60,'message' => Yii::t('backend', 'Only 60 character'),'when'=> function($model){ return $this->validacion1 == 1; }],
            //'liquidado', 'id_habitante'
            
            [['propiedad_horizontal'], 'catastro_cambio','when'=>function($model){ return $model->propiedad_horizontal==0 and $this->validacion1 == 1;}], 
            [['propiedad_horizontal'], 'catastro_cambio2','when'=>function($model){ return $model->propiedad_horizontal==1 and $this->validacion1 == 1;}],
        ];
    }

    /**
     * @inheritdoc
     */
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
            'validacion1' => Yii::t('backend', 'v'),
            
        ];
    }

    public function catastro_cambio($attribute, $params)
    {
  
          //Buscar el email en la tabla 
         
            $table = InmueblesConsulta::find()
                                    ->where("estado_catastro=:estado_catastro", [":estado_catastro" => $this->estado_catastro])
                                    ->andwhere("municipio_catastro=:municipio_catastro", [":municipio_catastro" => $this->municipio_catastro])
                                    ->andwhere("parroquia_catastro=:parroquia_catastro", [":parroquia_catastro" => $this->parroquia_catastro])
                                    ->andwhere("ambito_catastro=:ambito_catastro", [":ambito_catastro" => $this->ambito_catastro])
                                    ->andwhere("sector_catastro=:sector_catastro", [":sector_catastro" => $this->sector_catastro])
                                    ->andwhere("manzana_catastro=:manzana_catastro", [":manzana_catastro" => $this->manzana_catastro])
                                    ->andwhere("parcela_catastro=:parcela_catastro", [":parcela_catastro" => $this->parcela_catastro])
                                    ->andwhere("propiedad_horizontal=:propiedad_horizontal", [":propiedad_horizontal" => 0])
                                    //->andWhere("manzana_limite=:manzana_limite", [":manzana_limite" => $this->manzana_limite])
                                    ->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 
                                   
          //$sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE manzana_limite=:manzana_limite and catastro=:catastro';
          //$inmuebles = Inmuebles::findBySql($sql, [':manzana_limite' => $this->manzana_limite, 'catastro'=> $this->catastro])->all();
                 

          //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){

                   $this->addError($attribute, Yii::t('backend', 'El Contribuyente: '.$table[0]['id_contribuyente'].' ya ha asignado catastro. Id_impuesto: '.$table[0]['id_impuesto']));
            } 
     }
    

     public function catastro_cambio2($attribute, $params)
     {
  
          //Buscar el email en la tabla 

         
            $nivel_catastro1 = array(['nivela' =>$this->nivela , 'nivelb'=>$this->nivelb ]);              
            $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";


            $table = InmueblesConsulta::find()->where("estado_catastro=:estado_catastro", [":estado_catastro" => $this->estado_catastro])
                                    ->andwhere("municipio_catastro=:municipio_catastro", [":municipio_catastro" => $this->municipio_catastro])
                                    ->andwhere("parroquia_catastro=:parroquia_catastro", [":parroquia_catastro" => $this->parroquia_catastro])
                                    ->andwhere("ambito_catastro=:ambito_catastro", [":ambito_catastro" => $this->ambito_catastro])
                                    ->andwhere("sector_catastro=:sector_catastro", [":sector_catastro" => $this->sector_catastro])
                                    ->andwhere("manzana_catastro=:manzana_catastro", [":manzana_catastro" => $this->manzana_catastro])
                                    ->andwhere("propiedad_horizontal=:propiedad_horizontal", [":propiedad_horizontal" => 1])
                                    ->andwhere("parcela_catastro=:parcela_catastro", [":parcela_catastro" => $this->parcela_catastro])
                                    ->andwhere("subparcela_catastro=:subparcela_catastro", [":subparcela_catastro" => $this->subparcela_catastro])
                                    ->andwhere("nivel_catastro=:nivel_catastro", [":nivel_catastro" => $nivel_catastro])
                                    ->andwhere("unidad_catastro=:unidad_catastro", [":unidad_catastro" => $this->unidad_catastro])
                                    //->andWhere("manzana_limite=:manzana_limite", [":manzana_limite" => $this->manzana_limite])
                                    ->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 


          //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){

                    $this->addError($attribute, Yii::t('backend', 'El Contribuyente: '.$table[0]['id_contribuyente'].' ya ha asignado catastro. Id_impuesto: '.$table[0]['id_impuesto'])); 
            } 
     }
}
