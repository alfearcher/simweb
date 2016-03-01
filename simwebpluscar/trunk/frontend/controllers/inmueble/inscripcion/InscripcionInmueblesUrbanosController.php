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
 *  @file InscripcionInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 29-02-2016
 * 
 *  @class InmueblesUrbanosController
 *  @brief Clase que permite controlar la solicitud del registro o inscripcion de inmuebles urbanos
 *  en el lado del contribuyente,
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  randKey
 *  InscripcionInmueblesUrbanos
 *
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace frontend\controllers\inmueble\inscripcion;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use yii\widgets\ActiveForm;
use yii\web\Response;
use common\models\Users;
use common\models\User;
use yii\web\Session;
use frontend\models\inmueble\inscripcion\InscripcionInmueblesUrbanosForm;

//use common\models\Users;

// mandar url
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;
// active record consultas..
use yii\db\ActiveRecord;
use common\conexion\ConexionController;
session_start();
/*********************************************************************************************************
 * InscripcionInmueblesUrbanosController implements the actions for InscripcionInmueblesUrbanosForm model.
 *********************************************************************************************************/
class InscripcionInmueblesUrbanosController extends Controller
{

    public $conn;
    public $conexion;
    public $transaccion;



     /**
     *REGISTRO (inscripcion) INMUEBLES URBANOS
     *Metodo para crear las cuentas de usuarios de los funcionarios
     *@return model 
     **/
     public function actionInscripcionInmueblesUrbanos()
     { 

         if ( isset(Yii::$app->user->identity->id_contribuyente) ) {
         //Creamos la instancia con el model de validación
         $model = new InscripcionInmueblesUrbanosForm();
    
         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null;
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         }
   
         if ($model->load(Yii::$app->request->post())){

              if($model->validate()){

                 //condicionales     
                  
                if (!\Yii::$app->user->isGuest){                                      
                      

                     $guardo = self::GuardarInscripcion($model);

                     if($guardo == true){

                          die('guardo');

                     } else {

                          die('no guardo');
                     }

                     
                      


                   }else{  

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                   } 

              }else{
                
                   $model->getErrors(); 
              }
         }
              return $this->render('inscripcion-inmuebles-urbanos', ['model' => $model, ]);  

        }  else {
                    echo "No hay Contribuyente!!!...<meta http-equiv='refresh' content='3; ".Url::toRoute(['site/login'])."'>";
        }    
 
     } // cierre del metodo inscripcion de inmuebles

    

     /**
      * [GuardarInscripcion description]
      * @param [type] $model [description]
      */
     public function GuardarInscripcion($model)
     {
        
        
       
                     //$id_impuesto = $model->id_impuesto;                   //clave principal de la tabla no sale en el formulario identificador del inpuesto inmobiliario
                     $id_contribuyente = $model->id_contribuyente;         //identidad del contribuyente
                     $ano_inicio = $model->ano_inicio;                     //anio de inicio
                     $direccion = $model->direccion;                       //direccion
                     //$av_calle_esq_dom = $model->av_calle_esq_dom;         //avenida. calle. esquina. domicilio
                     $casa_edf_qta_dom = $model->casa_edf_qta_dom;         //casa. edificio. quinta. domicilio
                     $piso_nivel_no_dom = $model->piso_nivel_no_dom;       //piso. nivel. numero. domicilio
                     $apto_dom = $model->apto_dom;                         //apartamento. domicilio
                     $medidor = $model->medidor;                           //medidor
                     $observacion = $model->observacion;                   //observaciones
                     $tipo_ejido = $model->tipo_ejido;                     //tipo ejido


                     // $propiedad_horizontal = $model->propiedad_horizontal; //propiedad horizontal
                     // $estado_catastro = $model->estado_catastro;           //Estado catastro
                     // $municipio_catastro = $model->municipio_catastro;     //Municipio catastro
                     // $parroquia_catastro = $model->parroquia_catastro;     //Parroquia catastro
                     // $ambito_catastro = $model->ambito_catastro;           //Ambito catastro
                     // $sector_catastro = $model->sector_catastro;           //Sector catastro
                     // $manzana_catastro = $model->manzana_catastro;         //Manzana catastro

                     // $catastro1 = array(['estado' => $estado_catastro, 'municipio'=> $municipio_catastro, 'parroquia'=>$parroquia_catastro, 'ambito'=>$ambito_catastro, 'sector'=>$sector_catastro, 'manzana' =>$manzana_catastro]);
                     // $catastro = "".$catastro1[0]['estado']."-".$catastro1[0]['municipio']."-".$catastro1[0]['parroquia']."-".$catastro1[0]['ambito']."-".$catastro1[0]['sector']."-".$catastro1[0]['manzana']."";
                     
                     
                     // if ($propiedad_horizontal == 0) {

                     //      $parcela_catastro = $model->parcela_catastro;                                     //Parcela catastro
                     //      $subparcela_catastro = 0;                                                         //Sub parcela catastro
                     //      $nivel_catastro = 0;                                                              //Nivel catastro
                     //      $unidad_catastro = 0;                                                             //Unidad catastro     
                     // }else{ 

                     //      $parcela_catastro = $model->parcela_catastro;                                     //Parcela catastro
                     //      $subparcela_catastro = $model->subparcela_catastro;                               //Sub parcela catastro
                     //      $nivel_c1 = $model->nivela;
                     //      $nivel_c2 = $model->nivelb;
                     //      $nivel_catastro1 = array(['nivela' =>$nivel_c1 , 'nivelb'=>$nivel_c2 ]);              //Nivel catastro
                     //      $nivel_catastro = "".$nivel_catastro1[0]['nivela']."".$nivel_catastro1[0]['nivelb']."";
                     //      $unidad_catastro = $model->unidad_catastro;                                       //Unidad catastro  
                          
                     // } 

                   //--------------TRY---------------
                        $arrayDatos = ['id_contribuyente' => $id_contribuyente,
                                       'ano_inicio' => $ano_inicio,
                                       'direccion' => $direccion,
                                       'medidor' => $medidor,
                                       'observacion' => $observacion,
                                       'tipo_ejido' => $tipo_ejido,
                                       //direcciones
                                       //'av_calle_esq_dom' => $av_calle_esq_dom,
                                       'casa_edf_qta_dom' => $casa_edf_qta_dom,
                                       'piso_nivel_no_dom' => $piso_nivel_no_dom,
                                       'apto_dom' => $apto_dom,

                                       //'manzana_limite' => $manzana_limite,
                                       //'nivel' => $nivel,
                                       //otros datos
                                       //'tlf_hab' => $tlf_hab,
                                       
                                       // 'id_sim' => $id_sim,
                                       // 'inactivo' => $inactivo,
                                       // 'catastro' => $catastro,
                                       // 'id_habitante' => $id_habitante,
                                       
                                       // 'propiedad_horizontal' => $propiedad_horizontal,
                                       // //catastro inmueble
                                       // 'estado_catastro' => $estado_catastro,
                                       // 'municipio_catastro' => $municipio_catastro,
                                       // 'parroquia_catastro' => $parroquia_catastro,
                                       // 'ambito_catastro' => $ambito_catastro,
                                       // 'sector_catastro' => $sector_catastro,
                                       // 'manzana_catastro' => $manzana_catastro,
                                       // //parcelas 
                                       // 'parcela_catastro' => $parcela_catastro,
                                       // 'subparcela_catastro' => $subparcela_catastro,
                                       // 'nivel_catastro' => $nivel_catastro,
                                       // 'unidad_catastro' => $unidad_catastro,

                                       // 'liquidado' => $liquidado, 
                                       // 'lote_1' => $lote_1,
                                       // 'lote_2' => $lote_2,
                                       // 'lote_3' => $lote_3, 
                                       // 'nivel' => $nivel,
                                       ]; 

                        $tableName = 'sl_inmuebles'; 


                        $conn = New ConexionController();

                        $this->conexion = $conn->initConectar('dbsim');     // instancia de la conexion (Connection)
                        $this->conexion->open();  

                        $transaccion = $this->conexion->beginTransaction();

                        if ( $conn->guardarRegistro($this->conexion, $tableName,  $arrayDatos) ){  

                            //$transaccion->commit(); 
                            $this->conexion->close(); 
                            $tipoError = 0; 
                            // $msg = Yii::t('backend', 'SUCCESSFUL REGISTRATION OF THE URBAN PROPERTY!');//REGISTRO EXITOSO DE LAS PREGUNTAS DE SEGURIDAD
                            // $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                            return true; //$this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
            
                        }else{ 

                            //$transaccion->roolBack();
                            $this->conexion->close();
                            $tipoError = 0; 
                            // $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                            // $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("inmueble/inmuebles-urbanos/index")."'>";                     
                            return false; //$this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                        }   

                       
     }


}