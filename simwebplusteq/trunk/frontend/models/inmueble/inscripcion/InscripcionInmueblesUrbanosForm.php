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
 *  @date 29-02-2016
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

namespace frontend\models\inmueble\inscripcion;

use Yii;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\conexion\ConexionController;
use frontend\models\inmueble\inscripcion\InmueblesUrbanosForm;

class InscripcionInmueblesUrbanosForm extends Model{
    
    public $conn;
    public $conexion;
    public $transaccion;
    

    Public  $id_impuesto;
    Public  $id_contribuyente;
    Public  $ano_inicio;
    Public  $direccion;
    Public  $liquidado;
    Public  $manzana_limite;
    Public  $lote_1;
    Public  $lote_2;
    Public  $lote_3;
    Public  $nivel;
    Public  $av_calle_esq_dom;
    Public  $casa_edf_qta_dom;
    Public  $piso_nivel_no_dom;
    Public  $apto_dom;
    Public  $tlf_hab;
    Public  $medidor;
    Public  $id_sim;
    Public  $observacion;
    Public  $inactivo;
    Public  $catastro;
    Public  $id_habitante;
    Public  $tipo_ejido;
    Public  $propiedad_horizontal;
    Public  $estado_catastro;
    Public  $municipio_catastro;
    Public  $parroquia_catastro;
    Public  $ambito_catastro;
    Public  $sector_catastro;
    Public  $manzana_catastro;
    Public  $parcela_catastro;
    Public  $subparcela_catastro;
    Public  $nivel_catastro;
    Public  $nivela;
    Public  $nivelb;
    Public  $unidad_catastro;

     Public  $capa_subparcela;
     Public $fecha_inicio;
     public $nro_solicitud;

    public function rules()
    {

        return [
            [['id_contribuyente', 'ano_inicio', 'tipo_ejido', ], 'integer','message' => Yii::t('frontend', 'only integers')],
            [['id_contribuyente','casa_edf_qta_dom','piso_nivel_no_dom','apto_dom', 'ano_inicio', 'tipo_ejido', 'direccion'], 'required','message' => Yii::t('frontend', 'Required field')],
            
            [['observacion'], 'string'],
            [['direccion'], 'string', 'max' => 255,'message' => Yii::t('frontend', 'Only 255 character')],
            
            [['av_calle_esq_dom', 'casa_edf_qta_dom'], 'string', 'max' => 50,'message' => Yii::t('frontend', 'Only 50 character')],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25,'message' => Yii::t('frontend', 'Only 25 character')],
            
            [['medidor'], 'string', 'max' => 20,'message' => Yii::t('frontend', 'Only 20 character')],
            
           
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'id_impuesto' => Yii::t('frontend', 'Id Tax'), 
            'id_contribuyente' => Yii::t('frontend', 'Id taxpayer'),
            'ano_inicio' => Yii::t('frontend', 'Year home'),
            'direccion' => Yii::t('frontend', 'Street Address'),
            'liquidado' => Yii::t('frontend', 'liquidated'),
            'manzana_limite' => Yii::t('frontend', 'Quadrant Limit'),
            'lote_1' => Yii::t('frontend', 'Lote 1'),
            'lote_2' => Yii::t('frontend', 'Lote 2'),
            'nivel' => Yii::t('frontend', 'Level'),
            'lote_3' => Yii::t('frontend', 'Lote 3'),
            'av_calle_esq_dom' => Yii::t('frontend', 'Avenue Street Corner Home'),
            'casa_edf_qta_dom' => Yii::t('frontend', 'House Building Quint Home'),
            'piso_nivel_no_dom' => Yii::t('frontend', 'Flat Level Number Home'),
            'apto_dom' => Yii::t('frontend', 'Apartment Home'),
            'tlf_hab' => Yii::t('frontend', 'Phone Room'),
            'medidor' => Yii::t('frontend', 'Meter'),
            'id_sim' => Yii::t('frontend', 'Id Sim'),
            'observacion' => Yii::t('frontend', 'Observation'),
            'inactivo' => Yii::t('frontend', 'Inactive'),
            'catastro' => Yii::t('frontend', 'Cadastre'),
            'id_habitante' => Yii::t('frontend', 'Id Habitante'),
            'tipo_ejido' => Yii::t('frontend', 'Type Ejido'),
            'propiedad_horizontal' => Yii::t('frontend', 'Horizontal Property'),
            
            'estado_catastro' => Yii::t('frontend', 'Edo.'),
            'municipio_catastro' => Yii::t('frontend', 'Mnp.'),
            'parroquia_catastro' => Yii::t('frontend', 'Prq.'),
            'ambito_catastro' => Yii::t('frontend', 'Amb.'),
            'sector_catastro' => Yii::t('frontend', 'Sct.'),
            'manzana_catastro' => Yii::t('frontend', 'Mzn.'),
            
            'parcela_catastro' => Yii::t('frontend', 'Plot'),
            'subparcela_catastro' => Yii::t('frontend', 'Subplot'),
            'nivel_catastro' => Yii::t('frontend', 'Level'),
            'unidad_catastro' => Yii::t('frontend', 'Unit'),
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

    


          

         
