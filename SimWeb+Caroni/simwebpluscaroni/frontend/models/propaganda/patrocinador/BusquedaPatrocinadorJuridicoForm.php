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
 *  @file BusquedaPatrocinadorJuridicoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 08/09/2016
 * 
 *  @class BusquedaPatrocinadorJuridicoForm
 *  @brief Clase que contiene las rules para validacion de la busqueda de patrocinador como contribuyente juridico
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
namespace frontend\models\propaganda\patrocinador;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\propaganda\Propaganda;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\contribuyente\ContribuyenteBase;
use common\models\propaganda\patrocinador\SlPropagandasPatrocinadores;
use common\models\propaganda\patrocinador\PropagandasPatrocinadores;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class BusquedaPatrocinadorJuridicoForm extends Model
{
    
    public $naturaleza;
    public $cedula;
    public $tipo;




    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [[ 'naturaleza', 'cedula', 'tipo'], 'required'],
            ['cedula', 'validarLongitud'],
            [['cedula', 'tipo'], 'integer'],
            
            ];
    } 

    /**
     * @inheritdoc
     */
   


    public function attributeLabels()
    {
        return [
               
                'naturaleza' => Yii::t('frontend', 'Naturaleza'), 
                'cedula' => yii::t('frontend', 'Cedula'),
                'cedula' => yii::t('frontend', 'Tipo'),
              
        ];      
    }
    
    /**
     * [validarLongitud description] funcion que valida la longitud de la cedula
     * @param  [type] $attribute [description] atributos
     * @param  [type] $params    [description] parametros
     * @return [type]            [description] devuelve un mensaje de error si la longitud sobrepasa el maximo
     */
    public function validarLongitud($attribute, $params){

    $longitud = strlen($this->cedula.$this->tipo);

      if ($longitud >9){
        $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 9 characters'));
      }
    }

    public function attributeSlPropagandasPatrocinadores()
    {

        return [
            'nro_solicitud',
            'id_contribuyente',
            'id_impuesto',
            'id_patrocinador',
            'origen',
            'usuario',
            'fecha_hora',
            'estatus',
            'fecha_hora_proceso',
            'user_funcionario',
         

            
            
          
        ];
    }


    public function attributePropagandasPatrocinadores()
    {

        return [
            'id_contribuyente',
            'id_impuesto',
            'id_patrocinador',
            'estatus',
     
          
        ];
    }

       /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchContribuyenteNatural($model)
    { 
       
        $buscar = ContribuyenteBase::find()
       
                                ->where([ 
            'naturaleza' => $model->naturaleza,
            'cedula' => $model->cedula,
            'tipo_naturaleza' => 0,
            'inactivo' => 0,
            
            ])
        ->all();
        
    if($buscar == true)
    {
        return $buscar;
    }else{
        return false;
    }

       
    }

    /**
     * [searchContribuyenteJuridico description] Metodo que realiza la busqueda del contribuyente juridico
     * @param  [type] $model [description] modelo que contiene la naturaleza, cedula y tipo de contribuyente a buscar
     * @return [type]        [description] retorna true si hay match y false si no encuentra informacion
     */
    public function searchContribuyenteJuridico($model)
    { 
       
        $buscar = ContribuyenteBase::find()
       
                                ->where([ 
            'naturaleza' => $model->naturaleza,
            'cedula' => $model->cedula,
            'tipo' => $model->tipo,
            'tipo_naturaleza' => 1,
            'inactivo' => 0,
            
            ])
        ->all();
        
    if($buscar == true)
    {
        return $buscar;
    }else{
        return false;
    }

       
    }

    public function buscarPropaganda($idPropaganda)
    {
       // die(var_dump($idPropaganda));
        $buscar = Propaganda::find()
                            ->where([
                            'id_impuesto' => $idPropaganda,
                            'tipos_propagandas.inactivo' => 0,

                            ])
                            ->joinWith('tipoPropaganda')
                            ->all();

            if ($buscar == true){
                //die(var_dump($buscar[0]->tipo_propaganda));
                return $buscar[0]->tipoPropaganda->descripcion;
            }else{
                return false;
            }
    }

    public function busquedaPatrocinador($idPatrocinador)
    {

        $query = ContribuyenteBase::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
            'id_contribuyente' => $idPatrocinador,
            'inactivo' => 0,
            ])
  
        ->all();
  
        return $dataProvider;

    }

    public function verificarPatrocinador($idPatrocinador, $idImpuesto)
    {

        $busqueda = SlPropagandasPatrocinadores::find()
                                        ->where([
                                            'id_patrocinador' => $idPatrocinador,
                                            'id_impuesto' => $idImpuesto,
                                            'estatus' =>0,
                                            ])
                                        ->all();
            if ($busqueda == true){
                return true;
            }else{
                return false;
            }
    }


    public function verificarPatrocinadorMaestro($idPatrocinador, $idImpuesto)
    {
        $busqueda = PropagandasPatrocinadores::find()
                                        ->where([
                                            'id_patrocinador' => $idPatrocinador,
                                            'id_impuesto' => $idImpuesto,
                                            'estatus' => 0,
                                            ])
                                        ->all();

            if($busqueda == true){
                return true;
            }else{
                return false;
            }
    }
    

    




   

  
}
