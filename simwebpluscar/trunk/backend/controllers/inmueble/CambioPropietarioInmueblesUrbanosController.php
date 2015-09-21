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
 *  @file CambioPropietarioInmueblesUrbanosController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class CambioPropietarioInmueblesUrbanosController
 *  @brief Clase que permite controlar el cambio de otros datos del inmueble urbano, 
 *  el cambio ha propiedad horizontal
 *
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  CambioNumeroCatastralInmuebles
 *  findModel
 *  
 *   
 *  
 *  @inherits
 *  
 */
namespace backend\controllers\inmueble;
error_reporting(0);
use Yii;
use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\ContribuyentesForm;
use backend\models\inmueble\InmueblesConsulta;
use backend\models\inmueble\InmueblesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\conexionController;

/**
 * CambiosInmueblesUrbanosController implements the CRUD actions for InmueblesUrbanosForm model.
 */
class CambioPropietarioInmueblesUrbanosController extends Controller
{   

    public $conn;
    public $conexion;
    public $transaccion; 


    /**
     *Metodo: CambioPropietarioInmuebles
     *Actualiza los datos del numero catastral del inmueble urbano.
     *si el cambio es exitoso, se redireccionara a la  vista 'inmueble/inmuebles-urbanos/view' de la pagina.
     *@param $id_impuesto, tipo de dato entero y clave primaria de la tabla inmueble,  variable condicional 
     *para el cambio de otros datos inmuebles
     *@return model 
     **/
    public function actionCambioPropietarioInmuebles($id_contribuyente)
    { 
        
        $modelContribuyente = $this->findModelContribuyente($id_contribuyente);
        $modelBuscar = $this->findModel($id_contribuyente);

        $model = $this->findModel($id_contribuyente);

//echo "".count($model);

         //Mostrará un mensaje en la vista cuando el usuario se haya registrado
         $msg = null; 
         $url = null; 
         $tipoError = null; 
    
         //Validación mediante ajax
         if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($model); 
         } 
         if ($modelContribuyente->load(Yii::$app->request->post()) && Yii::$app->request->isAjax){ 

              Yii::$app->response->format = Response::FORMAT_JSON;
              return ActiveForm::validate($modelContribuyente); 
         } 
         

$btn = Yii::$app->request->post();
//echo'<pre>'; var_dump($btn); echo '</pre>'; die();

         if ($model->load(Yii::$app->request->post())){
            //if ($modelContribuyente->load(Yii::$app->request->post())){

              //if($modelContribuyente->validate()){ 
           
                if($model->validate()){


//echo'<pre>'; var_dump($btn); echo '</pre>'; die();
                 //condicionales     
                  
                if (!\Yii::$app->user->isGuest){   
                     
                   
/*
CONTENIDO VENDEDOR (SELLER)
*/

/*$id_impuestoVenta = $model->direccion;
$id_contribueyenteVenta = $modelContribuyente->id_contribuyente;

if ($model->tipo_naturaleza == 0) {
    $tipo = $model->tipoBuscar;
} else {
    $tipo = 0;
}

$modelParametros = ContribuyentesForm::find()->where(['naturaleza'=>$model->naturalezaBuscar1])
                                             ->andWhere(['cedula'=>$model->cedulaBuscar1])
                                             ->andWhere(['tipo'=>$model->tipoBuscar1])->asArray()->all();                                         


$id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];
*/
/*
FIN SELLER
*/


/*
CONTENIDO DEL COMPRADOR (BUYER)
*/  
       

//echo'<pre>'; var_dump($btn['Next']); echo '</pre>'; die();
if ($btn['Next']!=null) {
$contador = 1;

$datosVContribuyente = ContribuyentesForm::find()->where(['naturaleza'=>$model->naturalezaBuscar])
                                             ->andWhere(['cedula'=>$model->cedulaBuscar])
                                             ->andWhere(['tipo'=>$model->tipoBuscar])->asArray()->all();  



    //echo'<pre>'; var_dump($datosVendedor); echo '</pre>'; die();    
}
if ($btn['Next']!=null) {
    
    if ($model->datosVendedor!=null) {
        
      
    
          $contador = $contador+1;
          $datosVInmueble = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$model->datosVendedor])->asArray()->all(); 

         
}}
if ($btn['Accept']!=null) {
$id_contribuyenteComprador = $model->id_contribuyente;

$ano_traspaso = $model->ano_traspaso;
$id_contribuyenteVendedor = $model->datosVendedor;
$id_impuestoVendedor = $model->inmuebleVendedor;
//$id_contribuyenteComprador = $modelParametros[0]['id_contribuyente'];                   

echo'<pre>'; var_dump($btn); echo '</pre>'; die(); 
}

/*
FIN BUYER
*/
                 }else{ 

                        $msg = Yii::t('backend', 'AN ERROR OCCURRED WHEN FILLING THE URBAN PROPERTY!');//HA OCURRIDO UN ERROR AL LLENAR LAS PREGUNTAS SECRETAS
                        $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['inmueble/inmuebles-urbanos/view', 'id' => $model->id_impuesto])."'>";                     
                        return $this->render("/mensaje/mensaje", ["msg" => $msg, "url" => $url, "tipoError" => $tipoError]);
                 } 

              }else{ 
                $model->getErrors();
                    //echo var_dump($btn);exit();
                    /*if ($model->tipo_naturaleza1 =="") {

                        $model->addError('tipo_naturaleza1', Yii::t('backend', 'prueba') ); 
                    }
                    if ($model->tipo_naturaleza =="") {

                        $model->addError('cedulaBuscar', Yii::t('backend', 'prueba') ); 
                    }*/
                   
              } 
            /*}else{ 

                   $model->getErrors(); 
              }
         }*/
        }
//echo'<pre>'; var_dump($datosVContribuyente); echo '</pre>'; die();    
         return $this->render('Cambio-propietario-inmuebles', [
                'model' => $model, 'modelContribuyente' => $modelContribuyente, 'modelBuscar' =>$modelBuscar, 'datosVContribuyente'=>$datosVContribuyente,
                'datosVInmueble'=>$datosVInmueble,
            ]); 
        
    } 
    

    /**
     * Finds the Inmuebles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inmuebles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InmueblesUrbanosForm::findOne($id)) !== null) {
            return $model; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function findModelContribuyente($id)
    {
        if (($modelContribuyente = ContribuyentesForm::findOne($id)) !== null) {
            return $modelContribuyente; 
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
