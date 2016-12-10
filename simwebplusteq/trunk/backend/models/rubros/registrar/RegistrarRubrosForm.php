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
 *  @file RegistrarRubrosForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 15/10/2016
 * 
 *  @class RegistrarRubrosForm
 *  @brief Clase que contiene las rules para validacion  del formulario de registro de rubros
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
namespace backend\models\rubros\registrar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use backend\models\tasa\Tasa;
use common\models\tasas\GrupoSubnivel;

class RegistrarRubrosForm extends Model
{

    public $ente;
    public $ano_impositivo;
    public $rubro;
    public $descripcion;
    public $alicuota;
    public $minimo;
    public $minimo_ut;
    public $licores;
    public $divisor_alicuota;
    public $id_rubro_aseo;
    public $monto_aseo;
    public $tipo_monto;
    public $calculo_por_unidades;
    public $id_metodo;

    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['ano_impositivo','rubro', 'descripcion', 'alicuota', 'minimo', 'minimo_ut', 'licores', 'divisor_alicuota', 'id_rubro_aseo', 'monto_aseo', 'tipo_monto', 'calculo_por_unidades', 'id_metodo'], 'required'],

            [['rubro'], 'verificarRubro'],

            [['alicuota','minimo', 'minimo_ut', 'divisor_alicuota', 'monto_aseo', 'calculo_por_unidades'] ,'integer'],

            ['descripcion', 'string'];

    
            
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

        // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                
        'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'),
        'rubro' => Yii::t('frontend', 'Rubro'),
        'descripcion' => Yii::t('frontend', 'Descripcion'),
        'alicuota' => Yii::t('frontend', 'Alicuota'),
        'minimo' => Yii::t('frontend', 'Minimo'),
        'minimo_ut' => Yii::t('frontend', 'Minimo U.T'),
        'licores' => Yii::t('frontend', 'Licores'),
        'divisor_alicuota' => Yii::t('frontend', 'Divisor Alicuota'),
        'id_rubro_aseo' => Yii::t('frontend', 'Rubro Aseo'),
        'monto_aseo' => Yii::t('frontend', 'Monto Aseo'),
        'tipo_monto' => Yii::t('frontend', 'Tipo Monto'),
        'calculo_por_unidades' => Yii::t('frontend', 'Calculo Por Unidad'),
        'id_metodo' => Yii::t('frontend', 'Metodo'),
        
     
 
   
       
               
                



              
                
        ];
    }
    
    /**
     * [verificarDescripcionSubnivel description] metodo que verifica que una descripcion del grupo subnivel no se repita
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si consigue la informacion buscada
     */
    public function verificarRubro($attribute, $params)
    {
         $busqueda = GrupoSubnivel::find()
                                        ->where([

                                      'descripcion' => $this->descripcion,
                                     // 'inactivo' => 0,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Esta Descripcion ya existe' ));
              }else{
                return false;
              }

    }




    /**
     * [verificarGrupoSubnivel description] metodo que verifica que un grupo subnivel  no se repita
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] retorna mensaje de error si consigue la informacion buscada
     */
    public function verificarGrupoSubnivel($attribute, $params)
    {
         $busqueda = GrupoSubnivel::find()
                                        ->where([

                                      'grupo_subnivel' => $this->grupo_subnivel,
                                      //    'inactivo' => 0,
                                     // 'estatus' => 0,

                                          ])
                                        ->all();

              if ($busqueda != null){

                $this->addError($attribute, Yii::t('backend', 'Este Grupo Subnivel ya existe' ));
              }else{
                return false;
              }

    }



   
    public function attributeGrupoSubnivel()
    {

       return [

        'grupo_subnivel',
        'descripcion',
        'inactivo',
        


        ];
    }
}
