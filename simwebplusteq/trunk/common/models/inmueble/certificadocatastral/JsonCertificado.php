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
 *  @file LoginForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class LoginForm
 *  @brief Clase que permite validar cada uno de los datos del formulario login.
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
 *  validatePassword
 *  login
 *  getUser
 *
 *  @inherits
 *  
 */
namespace common\models\inmueble\certificadocatastral;

use Yii;
use yii\base\Model;
use common\models\funcionario\Funcionario;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\AvaluoCatastralForm;
use backend\models\inmueble\InmueblesRegistrosForm;

/**
 * LoginForm es el model del login de acceso.
 */
class JsonCertificado 
{
    	
	
     /**
     * [DatosContribuyente] metodo que busca los datos del contribuyente en 
     * la tabla contribuyente
     */
     public function DatosJson($idInmueble)
     {

       $datosInmueble = InmueblesUrbanosForm::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->one();

      

       $datosIRegistros = InmueblesRegistrosForm::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])
                                            //->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all();

      
          
       $datosHAvaluos = AvaluoCatastralForm::find()->where("id_impuesto=:impuesto", [":impuesto" => $idInmueble])->asArray()
                                            ->andwhere("inactivo=:inactivo", [":inactivo" => 0])
                                            ->all(); 

      
      
      if ($datosHAvaluos != null) {
                
                foreach ($datosHAvaluos as $key => $value) {
                                            
                } 
                
                
                $_SESSION['datosUAvaluos'] = $value; 

                if ($datosIRegistros!= null) {

                    foreach ($datosIRegistros as $key => $valueIn) {
                                            
                    } 
                    
                    $_SESSION['datosURegistros'] = $valueIn;
                } else {
                 
                  $valueIn = false;
                  $_SESSION['datosURegistros'] = $valueIn; 
   
                } 
                
          } else {
                $value = false;
                $_SESSION['datosUAvaluos'] = $value;
                
                
                if ($datosIRegistros != null) {

                    foreach ($datosIRegistros as $key => $valueIn) {
                                            
                    } 
                    
                    $_SESSION['datosURegistros'] = $valueIn;
                } else {
                 
                  $valueIn = false; 
                  $_SESSION['datosURegistros'] = $valueIn; 
                  
                  
                } 
          }  
        
      $inmueble = [    
            'id_sim'=> $datosInmueble->id_impuesto,
            'id_contribuyente'=>$datosInmueble->id_contribuyente,
            'ano_inicio'=>$datosInmueble->ano_inicio,
            'direccion'=>$datosInmueble->direccion,
            'liquidado'=>$datosInmueble->liquidado,
            'manzana_limite'=>$datosInmueble->manzana_limite,
            'lote_1'=>$datosInmueble->lote_1,
            'lote_2'=>$datosInmueble->lote_2,
            'lote_3'=>$datosInmueble->lote_3,
            'nivel'=>$datosInmueble->nivel,
            'av_calle_esq_dom'=>$datosInmueble->av_calle_esq_dom,
            'casa_edf_qta_dom'=>$datosInmueble->casa_edf_qta_dom,
            'piso_nivel_no_dom'=>$datosInmueble->piso_nivel_no_dom,
            'apto_dom'=>$datosInmueble->apto_dom,
            'tlf_hab'=>$datosInmueble->tlf_hab,
            'medidor'=>$datosInmueble->medidor,
            'id_sim'=>$datosInmueble->id_sim,
            'observacion'=>$datosInmueble->observacion,
            'inactivo'=>$datosInmueble->inactivo,
            'catastro'=>$datosInmueble->catastro,
            'id_habitante'=>$datosInmueble->id_habitante,
            'tipo_ejido'=>$datosInmueble->tipo_ejido,
            'propiedad_horizontal'=>$datosInmueble->propiedad_horizontal,
            'estado_catastro'=>$datosInmueble->estado_catastro,
            'municipio_catastro'=>$datosInmueble->municipio_catastro,
            'parroquia_catastro'=>$datosInmueble->parroquia_catastro,
            'ambito_catastro'=>$datosInmueble->ambito_catastro,
            'sector_catastro'=>$datosInmueble->sector_catastro,
            'manzana_catastro'=>$datosInmueble->manzana_catastro,
            'parroquia_catastro'=>$datosInmueble->parcela_catastro,
            'subparcela_catastro'=>$datosInmueble->subparcela_catastro,
            'nivel_catastro'=>$datosInmueble->nivel_catastro,
            'unidad_catastro'=>$datosInmueble->unidad_catastro,
      ];

      $avaluo = [
            'id_historico_avaluo' =>$value['id_historico_avaluo'],
            'id_impuesto' =>$value['id_impuesto'],
            'fecha' =>$value['fecha'],
            'valor' =>$value['valor'], 
            'mts' =>$value['mts'], 
            'ano_impositivo' =>$value['ano_impositivo'], 
            'id_uso_inmueble' =>$value['id_uso_inmueble'], 
            'valor_por_mts2' =>$value['valor_por_mts2'], 
            'clase_inmueble' =>$value['clase_inmueble'], 
            'tipo_inmueble' =>$value['tipo_inmueble'], 
            'tipo_aseo' =>$value['tipo_aseo'], 
            'mts2_terreno' =>$value['mts2_terreno'], 
            'valor_por_mts2_terreno' =>$value['valor_por_mts2_terreno'], 
            'lindero_norte' =>$value['lindero_norte'], 
            'lindero_sur' =>$value['lindero_sur'], 
            'lindero_este' =>$value['lindero_este'], 
            'lindero_oeste' =>$value['lindero_oeste'], 
            'inactivo' =>$value['inactivo'], 
            'id_tipologia_zona' =>$value['id_tipologia_zona'],
      ];

      $registro = [
            'id_inmueble_registro' =>$valueIn['id_inmueble_registro'], 
            'id_impuesto' =>$valueIn['id_impuesto'], 
            'id_contribuyente' =>$valueIn['id_contribuyente'], 
            'num_reg' =>$valueIn['num_reg'], 
            'reg_mercantil' =>$valueIn['reg_mercantil'], 
            'tomo' =>$valueIn['tomo'], 
            'protocolo' =>$valueIn['protocolo'],
            'folio' =>$valueIn['folio'], 
            'fecha' =>$valueIn['fecha'], 
            'estatus' =>$valueIn['estatus'], 
            'valor_documental' =>$valueIn['valor_documental'], 
            'id_tipo_documento_inmueble' =>$valueIn['id_tipo_documento_inmueble'], 
            'nro_matricula' =>$valueIn['nro_matricula'],  
            'asiento_registral' =>$valueIn['asiento_registral'],
      ];

      $inmuebleJson = json_encode($inmueble); 
      $avaluoJson = json_encode($avaluo); 
      $registroJson = json_encode($registro); 
      $firmaControl = md5($inmuebleJson.$avaluoJson.$registroJson);

      $JsonInmueble = ['inmuebleJson'=>$inmuebleJson,
                       'avaluoJson'=>$avaluoJson,
                       'registroJson'=>$registroJson,
                       'firmaControl'=>$firmaControl];

      

      return $JsonInmueble;                                              

     }


    

     
}
