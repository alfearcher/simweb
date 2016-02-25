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
 *  @file DesintegracionInmueblesForm.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 27-07-2015
 * 
 *  @class DesintegracionInmueblesForm
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

namespace frontend\models;

use Yii;
use backend\models\inmueble\InmueblesConsulta;
use common\conexion\ConexionController;
use backend\models\inmueble\Solvencias;
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
class DesintegracionInmueblesForm extends \yii\db\ActiveRecord
{

    public $conn;
    public $conexion;
    public $transaccion;   
    public $nivela;
    public $nivelb;
    public $validacion; 
    public $operacion; 
    public $ano_traspaso;
    public $tipo_naturaleza;
    public $naturaleza;
    public $cedula;
    public $tipo;
    public $naturalezaBuscar;
    public $cedulaBuscar;
    public $tipoBuscar;
    
    public $ano_traspaso1;
    public $tipo_naturaleza1;
    public $naturalezaBuscar1;
    public $cedulaBuscar1;
    public $tipoBuscar1;

    public $variablephp;
    public $datosVendedor;
    public $inmuebleVendedor;
    public $datosVContribuyente;
    public $datosVInmueble;
    public $direccion;
    public $direccion2;
    public $fecha_inicio;


    public static function tableName()
    {
        return 'inmuebles';
    }
 


    public function rules()
    {

        return [ 

            [['id_contribuyente','id_impuesto', 'validacion'], 'integer','message' => Yii::t('backend', 'only integers')],
            //[['estado_catastro', 'municipio_catastro', 'parroquia_catastro', 'sector_catastro', 'manzana_catastro', 'parcela_catastro', 'subparcela_catastro', 'unidad_catastro'], 'string'],
            
            [['direccion'], 'string', 'max' => 255,'message' => Yii::t('backend', 'Only 255 character')],
            [['direccion'], 'required', 'message' => Yii::t('backend', 'Cannot be blank')],                
            //Validacion 
            //[['direccion', 'direccion2'], 'cercanos'],
        ]; 
    } 

    
    public function attributeLabels()
    {
        return [ 
            'id_impuesto' => Yii::t('backend', 'Id Tax'), 
            'id_contribuyente' => Yii::t('backend', 'Id taxpayer'),
            'ano_inicio' => Yii::t('backend', 'Year home'),
            'direccion' => Yii::t('backend', 'Street Address'),
            'liquidado' => Yii::t('backend', 'liquidated'),
            'manzana_limite' => Yii::t('backend', 'Quadrant Limit'),
            'lote_1' => Yii::t('backend', 'Lote 1'),
            'lote_2' => Yii::t('backend', 'Lote 2'),
            'nivel' => Yii::t('backend', 'Level'),
            'lote_3' => Yii::t('backend', 'Lote 3'),
            'av_calle_esq_dom' => Yii::t('backend', 'Avenue Street Corner Home'),
            'casa_edf_qta_dom' => Yii::t('backend', 'House Building Quint Home'),
            'piso_nivel_no_dom' => Yii::t('backend', 'Flat Level Number Home'),
            'apto_dom' => Yii::t('backend', 'Apartment Home'),
            'tlf_hab' => Yii::t('backend', 'Phone Room'),
            'medidor' => Yii::t('backend', 'Meter'),
            'id_sim' => Yii::t('backend', 'Id Sim'),
            'observacion' => Yii::t('backend', 'Observation'),
            'inactivo' => Yii::t('backend', 'Inactive'),
            'catastro' => Yii::t('backend', 'Cadastre'),
            'id_habitante' => Yii::t('backend', 'Id Habitante'),
            'tipo_ejido' => Yii::t('backend', 'Type Ejido'),
            'propiedad_horizontal' => Yii::t('backend', 'Horizontal Property'),
            
            'estado_catastro' => Yii::t('backend', 'Edo.'),
            'municipio_catastro' => Yii::t('backend', 'Mnp.'),
            'parroquia_catastro' => Yii::t('backend', 'Prq.'),
            'ambito_catastro' => Yii::t('backend', 'Amb.'),
            'sector_catastro' => Yii::t('backend', 'Sct.'),
            'manzana_catastro' => Yii::t('backend', 'Mzn.'),
            
            'parcela_catastro' => Yii::t('backend', 'Plot'),
            'subparcela_catastro' => Yii::t('backend', 'Subplot'),
            'nivel_catastro' => Yii::t('backend', 'Level'),
            'unidad_catastro' => Yii::t('backend', 'Unit'),
            'validacion' => Yii::t('backend', 'v'),
            
            'ano_traspaso1'=> Yii::t('backend', 'Handover year'),
            'tipo_naturaleza1'=> Yii::t('backend', 'Type nature'),
            'naturalezaBuscar1'=> Yii::t('backend', 'Nature'),
            'cedulaBuscar1'=> Yii::t('backend', 'Identification Card'),
            'tipoBuscar1'=> Yii::t('backend', 'Type'),
            
            'ano_traspaso'=> Yii::t('backend', 'Handover year'), 
            'tipo_naturaleza'=> Yii::t('backend', 'Type nature'),           
            'naturalezaBuscar'=> Yii::t('backend', 'Nature'),
            'cedulaBuscar'=> Yii::t('backend', 'Identification Card'),
            'tipoBuscar'=> Yii::t('backend', 'Type'),
        ]; 
    }
    /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */ 
    public function cercanos($attribute, $params)
    {
  
          //Buscar  id contribuyente, id impuesto y nombre o razón social que se repite. en el numero de catastro
          $conn = New ConexionController(); // instancia de la conexion (Connection)
          

          $this->conexion = $conn->initConectar('dbsim');     
          $this->conexion->open(); 
          $transaccion = $this->conexion->beginTransaction();
          
          
              $subparcela_catastro = 0;
              $nivel_catastro = 0;
              $unidad_catastro = 0;
              $sql1 = 'SELECT id_impuesto, id_contribuyente, estado_catastro,municipio_catastro, parroquia_catastro, ambito_catastro, sector_catastro, manzana_catastro, parcela_catastro FROM inmuebles WHERE ';
              $sql1 .= 'id_contribuyente = "'.$this->id_contribuyente.'"';
              $sql1 .= 'and id_impuesto = "'.$this->direccion.'"';


              $sql2 = 'SELECT id_impuesto, id_contribuyente, estado_catastro,municipio_catastro, parroquia_catastro, ambito_catastro, sector_catastro, manzana_catastro, parcela_catastro FROM inmuebles WHERE ';
              $sql2 .= 'id_contribuyente = "'.$this->id_contribuyente.'"';
              $sql2 .= 'and id_impuesto = "'.$this->direccion2.'"';
          
          
          //$buscar1 = $conn->buscarRegistro($this->conexion, $sql1);
          //$buscar2 = $conn->buscarRegistro($this->conexion, $sql2); 

          $buscar1 = InmueblesConsulta::find()->where(['id_contribuyente'=>$this->id_contribuyente, 'id_impuesto' => $this->direccion ])->asArray()->one();
          $buscar2 = InmueblesConsulta::find()->where(['id_contribuyente'=>$this->id_contribuyente, 'id_impuesto' => $this->direccion2 ])->asArray()->one();

         // echo'<pre>'; var_dump($buscar1, $buscar2); echo '</pre>'; die();


          if($buscar1["estado_catastro"] != $buscar2["estado_catastro"] ) {
 
             $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 
             if($buscar1["municipio_catastro"] != $buscar2["municipio_catastro"] ) {

                $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 
                if($buscar1["parroquia_catastro"] != $buscar2["parroquia_catastro"] ) {

                   $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 
                   if($buscar1["ambito_catastro"] != $buscar2["ambito_catastro"] ) {
                      
                      $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 
                      if($buscar1["sector_catastro"] != $buscar2["sector_catastro"] ) {
                        
                         $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 
                         if($buscar1["manzana_catastro"] != $buscar2["manzana_catastro"] ) {
                            
                            $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar1['id_contribuyente'].'  has already allocated about this property Cadastre. ')); 


                         }

                      }
                   }
                }
             }

          }


 
    }

    /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function catastro_existe($attribute, $params)
    {
  
          //Buscar  id contribuyente, id impuesto y nombre o razón social que se repite. en el numero de catastro
          $conn = New ConexionController(); // instancia de la conexion (Connection)
          

          $nivel_c1 = $this->nivela;
          $nivel_c2 = $this->nivelb;
          $nivel_catastro1 = array(['nivela' =>$nivel_c1 , 'nivelb'=>$nivel_c2 ]);                 
          $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";


          $this->conexion = $conn->initConectar('dbsim');     
          $this->conexion->open(); 
          $transaccion = $this->conexion->beginTransaction();
          
          if ($this->propiedad_horizontal == 1) {
              $sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE estado_catastro = '.$this->estado_catastro.' ';
              $sql .= 'and municipio_catastro = "'.$this->municipio_catastro.'"';
              $sql .= 'and parroquia_catastro = "'.$this->parroquia_catastro.'"';
              $sql .= 'and ambito_catastro = "'.$this->ambito_catastro.'"';
              $sql .= 'and sector_catastro = "'.$this->sector_catastro.'"';
              $sql .= 'and manzana_catastro = "'.$this->manzana_catastro.'"';
              $sql .= 'and parcela_catastro = "'.$this->parcela_catastro.'"';
              $sql .= 'and subparcela_catastro = "'.$this->subparcela_catastro.'"';
              $sql .= 'and nivel_catastro = "'.$nivel_catastro.'"';
              $sql .= 'and unidad_catastro = "'.$this->unidad_catastro.'"'; 
          }else{ 
              $subparcela_catastro = 0;
              $nivel_catastro = 0; 
              $unidad_catastro = 0;
              $sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE estado_catastro = '.$this->estado_catastro.' ';
              $sql .= 'and municipio_catastro = "'.$this->municipio_catastro.'"';
              $sql .= 'and parroquia_catastro = "'.$this->parroquia_catastro.'"';
              $sql .= 'and ambito_catastro = "'.$this->ambito_catastro.'"';
              $sql .= 'and sector_catastro = "'.$this->sector_catastro.'"';
              $sql .= 'and manzana_catastro = "'.$this->manzana_catastro.'"';
              $sql .= 'and parcela_catastro = "'.$this->parcela_catastro.'"';
              $sql .= 'and subparcela_catastro = "'.$subparcela_catastro.'"';
              $sql .= 'and nivel_catastro = "'.$nivel_catastro.'"';
              $sql .= 'and unidad_catastro = "'.$unidad_catastro.'"'; 
          } 

          $buscar = $conn->buscarRegistro($this->conexion, $sql);
//echo'<pre>'; var_dump($buscar); echo '</pre>'; die();
          if ($buscar != null){ 

                  //echo'<pre>'; var_dump($buscar[0]['id_contribuyente']); echo '</pre>'; die();
                  $this->addError($attribute, Yii::t('backend', 'The Contributor '.$buscar[0]['id_contribuyente'].'  has already allocated about this property Cadastre. Tax: '.$buscar[0]['id_impuesto'])); //el contribuidor (id) ya ha asignado catastro sobre este inmueble
          } 
                             
          $this->conexion->close(); 
   
    } 

    /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function inmuebleSolventeA($attribute, $params)
    {
  
          //solvencias y pago_detalles
          
            $table = Solvencias::find()
                                    ->where("id_contribuyente=:id_contribuyente", [":id_contribuyente" => $this->id_contribuyente])
                                    ->andwhere("id_impuesto=:id_impuesto", [":id_impuesto" => $this->direccion])
                                    ->andwhere("impuesto=:impuesto", [":impuesto" => 2])
                                    ->andwhere("id_impuesto=:id_impuesto", [":id_impuesto" => $this->ano_traspaso1])
                                    //->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 
                                    
            //$sql = 'SELECT id_impuesto, id_contribuyente FROM inmuebles WHERE manzana_limite=:manzana_limite and catastro=:catastro';
            //$inmuebles = Inmuebles::findBySql($sql, [':manzana_limite' => $this->manzana_limite, 'catastro'=> $this->catastro])->all();
                 

            //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){
                    
                    $this->addError($attribute, Yii::t('backend', 'The taxpayer: '.$table[0]['id_contribuyente'].' has already assigned cadestre. Tax: '.$table[0]['id_impuesto']));//Impuesto: '.$table->id_impuesto; 
            }
                            
          
     } 
    
     /**
      * @param  [type]
      * @param  [type]
      * @return [type]
      */
     public function inmuebleSolventeB($attribute, $params)
     {
  
          //Buscar el email en la tabla 

          $table = Solvencias::find()
                                    ->where("id_contribuyente=:id_contribuyente", [":id_contribuyente" => $this->id_contribuyente])
                                    ->andwhere("id_impuesto=:id_impuesto", [":id_impuesto" => $this->direccion])
                                    ->andwhere("impuesto=:impuesto", [":impuesto" => 2])
                                    //->andwhere("id_impuesto=:id_impuesto", [":id_impuesto" => $this->ano_traspaso])
                                    //->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 

            $table = Pagos::find()
                                    ->where("id_contribuyente=:id_contribuyente", [":id_contribuyente" => $this->id_contribuyente])
                                    //->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    ->asArray()->all(); 

            $table = PagosDetalle::find()
                                    ->where("id_pago=:id_pago", [":id_pago" => 0 ])
                                    ->andwhere("id_impuesto=:id_impuesto", [":id_impuesto" => $this->direccion])
                                    ->andwhere("impuesto=:impuesto", [":impuesto" => 2])
                                    ->andwhere("ano_impositivo=:ano_impositivo", [":ano_impositivo" => $this->ano_traspaso])
                                    //->andWhere("inactivo=:inactivo", [":inactivo" => 0])
                                    //id_pago, id_impuesto, impuesto, ano_impositivo, trimestre
                                    ->asArray()->all(); 

            //Si la consulta no cuenta (0) mostrar el error
            if ($table != null){

                    $this->addError($attribute, Yii::t('backend', 'The taxpayer: '.$table[0]['id_contribuyente'].' has already assigned cadestre. Tax: '.$table[0]['id_impuesto']));//Impuesto: '.$table->id_impuesto; 
            } 
         
           
     }

     /**
      * @param  [type]
      * @param  [type]
      * @return [type]
      */
     public function catastro_cambio($attribute, $params)
     {
  
          //Buscar el email en la tabla 
         
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
  
    /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
     public function catastro_cambio2($attribute, $params)
     {
  
          //Buscar el email en la tabla 

         
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
     
     /**
      * @return [type]
      */
     public function datosVendedor(){

          $datosVendedor = ContribuyentesForm::find()->where(['naturaleza'=>$this->naturalezaBuscar])
                                             ->andWhere(['cedula'=>$this->cedulaBuscar])
                                             ->andWhere(['tipo'=>$this->tipoBuscar])->asArray()->all();
          return $datosVendedor;
     }

     /**
      * @return [type]
      */
     public function getGenderOptions(){
        return array('M' => 'Male', 'F' => 'Female');
    }


}
