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
 *  @file CambioPropiedadHorizontalInmueblesForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 08-03-2016
 * 
 *  @class CambioPropiedadHorizontalInmueblesForm
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

namespace frontend\models\inmueble\cambiopropiedadhorizontal;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;


class CambioPropiedadHorizontalInmueblesForm extends \yii\db\ActiveRecord
{
     
    public $validacion;
    public $nivela;
    public $nivelb;
    
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
            [['id_contribuyente', 'propiedad_horizontal', 'estado_catastro', 'municipio_catastro', 'parroquia_catastro', 'sector_catastro', 'manzana_catastro' ], 'integer','message' => Yii::t('backend', 'only integers')],
            [['parcela_catastro', 'subparcela_catastro', 'unidad_catastro' ], 'integer','message' => Yii::t('backend', 'only integers')],
            [['parcela_catastro', 'subparcela_catastro', 'unidad_catastro','nivela','nivelb'],'required', 'when'=> function($model){ return $model->propiedad_horizontal == 1; }, 'message' => Yii::t('backend', 'Required field')],
            [['ambito_catastro'], 'string', 'max' => 4,'message' => Yii::t('backend', 'Only 3 character')],
            

            [['direccion', 'observacion'], 'string', 'max' => 255,'message' => Yii::t('backend', 'Only 255 character')],
            [['propiedad_horizontal'], 'catastro_registro','when'=>function($model){ return $model->validacion==1;}], 
            [['propiedad_horizontal'], 'catastro_registro2','when'=>function($model){ return $model->validacion==1;}],
            
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

    public function catastro_registro($attribute, $params)
    {
  
          //Buscar el email en la tabla 
          if($this->propiedad_horizontal==0){
            $table = InmueblesConsulta::find()
                                    ->where("estado_catastro=:estado_catastro", [":estado_catastro" => $this->estado_catastro])
                                    ->andwhere("municipio_catastro=:municipio_catastro", [":municipio_catastro" => $this->municipio_catastro])
                                    ->andwhere("parroquia_catastro=:parroquia_catastro", [":parroquia_catastro" => $this->parroquia_catastro])
                                    ->andwhere("ambito_catastro=:ambito_catastro", [":ambito_catastro" => $this->ambito_catastro])
                                    ->andwhere("sector_catastro=:sector_catastro", [":sector_catastro" => $this->sector_catastro])
                                    ->andwhere("manzana_catastro=:manzana_catastro", [":manzana_catastro" => $this->manzana_catastro])
                                    ->andwhere("parcela_catastro=:parcela_catastro", [":parcela_catastro" => $this->parcela_catastro])
                                    ->andwhere("propiedad_horizontal=:propiedad_horizontal", [":propiedad_horizontal" => 0])
                                    ->andWhere("manzana_limite=:manzana_limite", [":manzana_limite" => $this->manzana_limite])
                                    ->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 
                                    
            //$sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE manzana_limite=:manzana_limite and catastro=:catastro';
            //$inmuebles = Inmuebles::findBySql($sql, [':manzana_limite' => $this->manzana_limite, 'catastro'=> $this->catastro])->all();
                 

            //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){
                    
                    $this->addError($attribute, Yii::t('backend', 'The taxpayer: '.$table[0]['id_contribuyente'].' has already assigned cadestre. Tax: '.$table[0]['id_impuesto']));//Impuesto: '.$table->id_impuesto; 
            }
                            
          }
     }

     public function catastro_registro2($attribute, $params)
     {
  
          //Buscar el email en la tabla 

         if($this->propiedad_horizontal==1){
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
                                    ->andWhere("manzana_limite=:manzana_limite", [":manzana_limite" => $this->manzana_limite])
                                    ->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 
                                    

            //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){

                    $this->addError($attribute, Yii::t('backend', 'The taxpayer: '.$table[0]['id_contribuyente'].' has already assigned cadestre. Tax: '.$table[0]['id_impuesto']));//Impuesto: '.$table->id_impuesto; 
            } 
         
          } 
     }
}
