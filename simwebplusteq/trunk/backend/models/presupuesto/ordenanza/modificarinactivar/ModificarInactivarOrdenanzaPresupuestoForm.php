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
 *  @file ModificarInactivarOrdenanzaPresupuestoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 05/10/2016
 * 
 *  @class ModificarInactivarOrdenanzaPresupuestoForm
 *  @brief Clase que contiene las rules para validacion  del formulario de modificacion ordenanzas de presupuesto
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
namespace backend\models\presupuesto\ordenanza\modificarinactivar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\presupuesto\ordenanzas\OrdenanzaPresupuesto;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class ModificarInactivarOrdenanzaPresupuestoForm extends Model
{

    public $ano_impositivo;
    public $fecha_desde;
    public $fecha_hasta;
    public $observacion;
 
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

             [['fecha_desde', 'fecha_hasta'], 'required'],

              ['fecha_desde', 'verificarFechaDesde'],

           ['fecha_hasta', 'verificarFechaHasta'],

             ['fecha_desde',
           'compare',
           'compareAttribute' => 'fecha_hasta',
           'operator' => '<=',
           'message' => Yii::t('backend', '{attribute} no puede ser mayor ' . self::attributeLabels()['fecha_hasta'])],

            // [['codigo'], 'integer'],

            

            //['codigo', 'verificarCodigo'],

          //  ['codigo_contable', 'verificarCodigoContable'],
            
        ]; 
    } 


    /**
     * [busquedaOrdenanzaPresupuesto description] metodo que realiza la busqueda en la tabla ordenanza_presupuesto
     * @return [type] [description] retorna el dataprovider con la informacion
     */
    public function busquedaOrdenanzaPresupuesto()
    {
           $query = OrdenanzaPresupuesto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           'pagination' => [
        'pageSize' => 10,
    ],
        ]);
        $query->where([
            'inactivo' => 0,
            ])
        ->all();
         
        
        return $dataProvider;

    }
    /**
     * [busquedaDatosOrdenanzaPresupuesto description] metodo que realiza la busqueda de ordenanzas de puresupuesto en base a un id recibido por parametro
     * @param  [type] $idOrdenanza [description] id de la ordenanza
     * @return [type]              [description] retorna la informacion en caso de conseguirla, si no retorna false
     */
    public function busquedaDatosOrdenanzaPresupuesto($idOrdenanza){

        $busqueda = OrdenanzaPresupuesto::find()
                                        ->where([

                                          'id_presupuesto' => $idOrdenanza,
                                          'inactivo' => 0,

                                          ])
                                        ->all();

                      if ($busqueda == true){
                        return $busqueda;
                      }else{
                        return false;
                      }
    }


    /**
    * [verificarFechaDesde description] metodo que verifica que la fecha inicial no sea menor al año impositivo
    * @param  [type] $attribute [description] atributo 
    * @param  [type] $params    [description] parametros
    * @return [type]            [description] retorna mensaje de error
    */
    public function verificarFechaDesde($attribute, $params){
                $anoImpo = $_SESSION['anoImpo'];
            
                $fecha = date("Y", strtotime($this->fecha_desde));
                //die($this->ano_impositivo);
                if($fecha != $anoImpo){ 

                $this->addError($attribute, Yii::t('frontend', 'Fecha Inicial no coincide con año impositivo'));
                
                }

    }



    /**
     * [verificarFechaHasta description] metodo que verifica que la fecha final no sea mayor al año impositivo
     * @param  [type] $attribute [description]
     * @param  [type] $params    [description]
     * @return [type]            [description]
     */
    public function verificarFechaHasta($attribute, $params){
             $anoImpo = $_SESSION['anoImpo'];
            //die($this->ano_impositivo);
            $fecha = date("Y", strtotime($this->fecha_hasta));
                
            if($fecha != $anoImpo){ 

            $this->addError($attribute, Yii::t('frontend', 'Fecha Final no coincide con año impositivo'));
            
            }

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
                
        'ano_impositivo' => yii::t('frontend', 'Año Impositivo'),
        'fecha_desde' => Yii::t('frontend', 'Fecha Inicial'),
        'fecha_hasta' => Yii::t('frontend', 'Fecha Final'), 
        'descripcion' => Yii::t('frontend', 'Descripcion'),
       
                



              
                
        ];
    }







}
