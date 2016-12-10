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
 *  @file ReplicarTasasForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/2016
 * 
 *  @class ReplicarTasasForm
 *  @brief Clase que contiene las rules para validacion  del formulario de busqueda de lote de tasas
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
namespace backend\models\tasas\replicar;

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
class ReplicarTasasForm extends Model
{

 
    public $ano_impositivo;
    


  
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

           [['ano_impositivo'], 'required'],

            

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
                

        'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'),
      

 
   
       
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
                           

                            'varios.ano_impositivo' => $modelo->ano_impositivo,
                          //  die($modelo->ano_impositivo),
                           
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
    /**
     * [validarCheck description] metodo que verifica si una tasa esta seleccionada en el gridview
     * @param  [type] $postCheck [description] variable para determinar si hay algo checkeado
     * @return [type]            [description] retorna true o false
     */
    public function validarCheck($postCheck)
    {
        if (count($postCheck) > 0){

            return true;
        }else{
            return false;
        }
    }
    /**
     * [verificarReplicacionTasas description] metodo que verifica si alguna de las tasas que se desean replicar ya se encuentran el el año impositivo seleccionado
     * @param  [type] $idTasa        [description]
     * @param  [type] $anoImpositivo [description]
     * @return [type]                [description]
     */
    public function verificarReplicacionTasas($idTasa, $anoImpositivo){
       // die($idTasa.' '.$anoImpositivo);
        $busqueda = Tasa::find()
                        ->where([
                        'id_impuesto' => $idTasa,
                        'ano_impositivo' => $anoImpositivo,
                        'inactivo' => 0,

                            ])
                        ->all();

            if($busqueda == true){
              //  die('es verdadero');
                return true;
            }else{
               // die('es falso');
                return false;
            }
    }
}
