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

namespace common\models\inmueble\transaccionInmobiliaria;

use Yii;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\conexion\ConexionController;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\ParametrosNivelesCatastro;
class TransaccionesInmobiliariasForm extends Model{
    
    public $conn;
    public $conexion;
    public $transaccion;
    
    public $id_impuesto;
    public $id_comprador;
    public $id_vendedor;
    public $direccion;
    public $planilla;
    public $precio_inmueble;
    public $tipo_transaccion;
    public $usuario;
    public $fecha_hora;
    public $observacion;
    public $inactivo;
   
    

    public function rules()
    {

        return [
            [['id_contribuyente', 'ano_inicio', 'liquidado', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'inactivo', 'id_habitante', 'tipo_ejido', 'propiedad_horizontal', 'estado_catastro', 'municipio_catastro', 'parroquia_catastro', 'sector_catastro', 'manzana_catastro', 'parcela_catastro', 'subparcela_catastro', 'unidad_catastro'], 'integer','message' => Yii::t('backend', 'only integers')],
            [['id_contribuyente','id_sim','parcela_catastro','casa_edf_qta_dom','piso_nivel_no_dom','apto_dom', 'ano_inicio', 'manzana_limite', 'inactivo', 'tipo_ejido', 'propiedad_horizontal', 'estado_catastro', 'municipio_catastro', 'parroquia_catastro','ambito_catastro', 'sector_catastro', 'manzana_catastro', 'direccion'], 'required','message' => Yii::t('backend', 'Required field')],
            [['parcela_catastro', 'subparcela_catastro', 'unidad_catastro','nivela','nivelb'],'required', 'when'=> function($model){ return $model->propiedad_horizontal == 1; }, 'message' => Yii::t('backend', 'Required field')],
            [['observacion'], 'string'],
            [['direccion'], 'string', 'max' => 255,'message' => Yii::t('backend', 'Only 255 character')],
            [['nivel', 'ambito_catastro'], 'string', 'max' => 4,'message' => Yii::t('backend', 'Only 3 character')],
            [['av_calle_esq_dom', 'casa_edf_qta_dom'], 'string', 'max' => 50,'message' => Yii::t('backend', 'Only 50 character')],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25,'message' => Yii::t('backend', 'Only 25 character')],
            [['tlf_hab'], 'string', 'max' => 15,'message' => Yii::t('backend', 'Only 15 character')],
            [['medidor', 'id_sim'], 'string', 'max' => 20,'message' => Yii::t('backend', 'Only 20 character')],
            [['catastro'], 'string', 'max' => 60,'message' => Yii::t('backend', 'Only 60 character')],
            //'liquidado', 'id_habitante'
            //[['catastro'], 'catastro_existe'],
            [['propiedad_horizontal'], 'catastro_registro','when'=> function($model){ return $model->propiedad_horizontal == 0; }],
            [['propiedad_horizontal'], 'catastro_registro2','when'=> function($model){ return $model->propiedad_horizontal == 1; }],
        ]; 
    }

    
    public function attributeLabels()
    {
        return [
            'id_impuesto' => Yii::t('backend', 'Id Tax'), 
            'id_comprador' => Yii::t('backend', 'Id taxpayer Buyer'),
            'id_vendedor' => Yii::t('backend', 'Id taxpayer Seller'),
            'direccion' => Yii::t('backend', 'Street Address'),
            'planilla' => Yii::t('backend', 'payroll'),
            'precio_inmueble' => Yii::t('backend', 'Property Price'),
            'tipo_transaccion' => Yii::t('backend', 'Transaction Type'),
            'usuario' => Yii::t('backend', 'User'),
            'fecha_hora' => Yii::t('backend', 'Date Time'),
            'observacion' => Yii::t('backend', 'Observation'),
            'inactivo' => Yii::t('backend', 'Inactive'),
            
        ];
    }

    public function catastro_existe($attribute, $params)
    {
  
          //Buscar  id contribuyente, id impuesto y nombre o razón social que se repite. en el numero de catastro
          $conn = New ConexionController(); // instancia de la conexion (Connection)

          $this->conexion = $conn->initConectar('dbsim');     
          $this->conexion->open(); 
          $transaccion = $this->conexion->beginTransaction();
          
          $sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE manzana_limite='.$this->manzana_limite.' 'and 'catastro = '.$this->catastro ;

         /* $sql = 'SELECT E.estado,M.municipio,P.parroquia,A.ambito,S.codigo_ambito,A.descripcion,S.sector,MZ.manzana FROM estados As E " & _
             "inner join municipios as M on E.estado=M.estado " & _
             "inner join parroquias as P on M.estado=P.estado and M.municipio=P.municipio " & _
             "inner join sectores as S on P.estado=S.estado and P.municipio=S.municipio and P.parroquia=S.parroquia " & _
             "inner join ambitos as A on S.ambito=A.ambito " & _
             "inner join urbanizaciones as U on S.id_cp=U.id_cp " & _
             "inner join manzanas as MZ on U.id_cp=MZ.id_cp and U.urbanizacion=MZ.urbanizacion " & _
             "inner join manzana_limites as ML on MZ.id_manzana=ML.id_manzana " & _
             "where ML.manzana_limite=" & Str(nIdManzanaLimite); ';*/
          
 
          $buscar = $conn->buscarRegistro($this->conexion, $sql);
//echo'<pre>'; var_dump($buscar); echo '</pre>'; die();
          if ($buscar != null){
           
                  //echo'<pre>'; var_dump($buscar[0]['id_contribuyente']); echo '</pre>'; die();
                  $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar[0]['id_contribuyente'].'  has already allocated about this property Cadastre. Tax: '.$buscar[0]['id_impuesto'])); //el contribuidor (id) ya ha asignado catastro sobre este inmueble
          } 
                             
          $this->conexion->close(); 
   
    } 
   

    public function catastro_registro($attribute, $params)
    {
  
          //Buscar el email en la tabla 
         
            $table = InmueblesUrbanosForm::find()
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
    

     public function catastro_registro2($attribute, $params)
     {
  
          //Buscar el email en la tabla 

         
            $nivel_catastro1 = array(['nivela' =>$this->nivela , 'nivelb'=>$this->nivelb ]);              
            $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";


            $table = InmueblesUrbanosForm::find()->where("estado_catastro=:estado_catastro", [":estado_catastro" => $this->estado_catastro])
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

    


          

         
