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
 *  @file CrearPorpagandaForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 07/06/2016
 * 
 *  @class CrearPropagandaForm
 *  @brief Clase que contiene las rules para validacion del formulario de inscripcion de propaganda
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
 *  scenarios
 *  search
 *
 *  
 *
 *  @inherits
 *  
 */ 
namespace frontend\models\propaganda\crearpropaganda;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;



/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class CrearPropagandaForm extends Model
{

    public $ano_impositivo;
    public $clase_propaganda;
    public $uso_propaganda;
    public $fecha_inicial;
    public $cantidad_base;
    public $cigarrillos;
    public $cantidad_tiempo;
    public $base_calculo;
    public $bebidas_alcoholicas;
    public $tiempo;
    public $idioma;
    public $fecha_fin;
    public $id_sim;



    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['causas', 'observacion'], 'required'],
        ]; 
    } 

    /**
     * @inheritdoc
     */
   

    public function attributeLabels()
    {
        return [
                'causa' => Yii::t('frontend', 'Causa'), 
                'observacion' => Yii::t('frontend', 'Observacion'), 
        ];
    }

    public function attributeSlReposicionesCalcomania()
    {

        return [
            'nro_solicitud',
            'id_contribuyente',
            'id_impuesto',
            'nro_calcomania',
            'fecha_hora',
            'usuario',
            'causa',
            'observacion',
            'fecha_hora_proceso',
            'user_funcionario',
            'estatus',
        ];
    }

    public function verificarSolicitud($idVehiculo,$idConfig)
    {
      $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idVehiculo,
                                          'id_config_solicitud' => $idConfig,
                                          'inactivo' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
             return true;
            }else{
             return false;
            }
    }

    public function verificarCalcomania($calcomania)
    {
        //die($calcomania.'llegue');
    $buscar = Calcomania::find()
                                        ->where([ 
                                        'id_calcomania' => $calcomania,
                                        'estatus' => 0,
                                        'entregado' => 1,
                                        ])
                                      ->all();

            if($buscar == true){
               // die(var_dump($buscar));
             return $buscar;
            }else{
             return false;
            }
    }

  
}
