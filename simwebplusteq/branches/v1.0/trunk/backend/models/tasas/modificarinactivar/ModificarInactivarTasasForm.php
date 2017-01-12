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
 *  @file ModificarInactivarTasasForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/2016
 * 
 *  @class ModificarInactivarTasasForm
 *  @brief Clase que contiene las rules para validacion  del formulario de modificacion de tasas
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
namespace backend\models\tasas\modificarinactivar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\vehiculo\cambiodatos\BusquedaVehiculos;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\tasa\Tasa;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class ModificarInactivarTasasForm extends Model
{

    public $id_impuesto;
    public $id_codigo;
    public $impuesto;
    public $ano_impositivo;
    public $grupo_subnivel;
    public $codigo; 
    public $descripcion;
    public $monto;
    public $tipo_rango;
  public $inactivo;
    public $cantidad_ut;


  
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

           [[ 'grupo_subnivel', 'descripcion','monto','tipo_rango','cantidad_ut','impuesto', 'id_codigo', 'ano_impositivo', 'codigo'], 'required'],

            

            //['codigo', 'verificarCodigo'],

          //  ['codigo_contable', 'verificarCodigoContable'],
            
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
                
      //  'id_impuesto' => Yii::t('frontend', ''),
        'impuesto' => Yii::t('frontend', 'Impuesto'),
        'id_codigo' => Yii::t('frontend', 'Codigo Contable'),
        'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'),
        'grupo_subnivel' => Yii::t('frontend', 'Grupo Subnivel'),
        'tipo_rango' => Yii::t('frontend', 'Tipo de Rango'),
        'codigo' => Yii::t('frontend', 'Codigo Especifico'),
        'descripcion' => Yii::t('frontend', 'Descripcion'),
        'monto' => Yii::t('frontend', 'Monto'),
        'cantidad_ut' => Yii::t('frontend', 'Cantidad U.T'),

 
   
       
        ];
    }


    /**
     * [relacionBusquedaTasas description] metodo que realiza la busqueda de la relacion
     * @param  [type] $modelo [description] modelo que contiene la informacion
     * @return [type]         [description] redirecciona al metodo que renderiza el gridview con el dataprovider
     */
    public function relacionBusquedaTasas($modelo){

     //   die(var_dump($modelo));

        $busqueda = Tasa::find()
                        ->where([
                            'varios.id_codigo' => $modelo->id_codigo,
                            'varios.impuesto' => $modelo->impuesto,
                            'varios.ano_impositivo' => $modelo->ano_impositivo,
                          //  die($modelo->ano_impositivo),
                            'varios.codigo' => $modelo->codigo,
                            'varios.inactivo' => 0,


                            ])
                        ->joinWith('codigoContable')
                        ->joinWith('impuestos')
                        ->joinWith('grupoSubNivel')
                        ->joinWith('tipoRango');

                       // die(var_dump($busqueda));

                        return $busqueda;
    }
    /**
     * [busquedaTasas description] metodo que redirecciona a otro metodo que realiza la busqueda de la relacion de la tabla tasas 
     * @param  [type] $modelo [description] datos para realizar la busqueda relacion
     * @return [type]         [description] retorna la realcion
     */
    public function busquedaTasas($modelo){
        //die(var_dump($modelo));
               $query = self::relacionBusquedaTasas($modelo);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        $query
        ->all();
         
          
        return $dataProvider;
    }

    /**
     * [busquedaTasasModificar description] metodo que realiza la busqueda de la tasa especifica en base a un id
     * @param  [type] $idTasa [description] id de la tasa que se va a buscar
     * @return [type]         [description] retorna la informacion si la consigue y false, si no la consigue
     */
    public function busquedaTasasModificar($idTasa)
    {

            $busqueda = Tasa::find()
                            ->where([
                            'id_impuesto' => $idTasa,
                            'inactivo' => 0,

                                ])
                            ->one();

                    if($busqueda == true){
                        return $busqueda;
                    }else{
                        return false;
                    }

    }

    //atributos de la tabla codigos_contables
   
    public function attributeVarios()
    {

       return [

        'id_impuesto',
        'id_codigo',
        'impuesto',
        'ano_impositivo',
        'grupo_subnivel',
        'codigo',
        'descripcion',
        'monto',
        'tipo_rango',
        'inactivo',
        'cantidad_ut',


        ];
    }
}
