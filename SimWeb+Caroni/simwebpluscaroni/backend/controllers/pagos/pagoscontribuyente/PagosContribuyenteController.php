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
 *  @file PagosContribuyenteController.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 26/11/16
 *
 *  @class PagosContribuyenteController
 *  @brief Controlador que renderiza la vista el detalle de los pagos de un contribuyente
 *
 *
 *
 *
 *  @property
 *
 *
 *
 *
 *  @inherits
 *
 */

namespace backend\controllers\pagos\pagoscontribuyente;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\conexion\ConexionController;
use common\mensaje\MensajeController;
use backend\models\presupuesto\cargarpresupuesto\registrar\CargarPresupuestoForm;
use common\models\deuda\DeudaSearch;
use backend\models\deudas\deudascontribuyente\DeudasContribuyenteForm;
use yii\data\ArrayDataProvider;
use common\models\contribuyente\ContribuyenteBase;
use mPDF;
/**
 * Site controller
 */

session_start();

//$_SESSION['idContribuyente'] = yii::$app->user->identity->id_contribuyente;

//die($idContribuyente);



class PagosContribuyenteController extends Controller
{

  public $layout = 'layout-main';

  /**
   * [actionVerificarDeudasContribuyente description] metodo que renderiza las vistas con las deudas del contribuyente
   * @return [type] [description] renderizacion de vista con deudas del contribuyente
   */
  public function actionVerificarPagosContribuyente()
  {

    //die('llegue a pagos');


      if (isset($_SESSION['idContribuyente'])){

        $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');
      $monto = 0;
      $model = new DeudaSearch($idContribuyente);

      $dataProvider = $model->getDeudaGeneralPorImpuesto();
    // die(var_dump($dataProvider));

        if($dataProvider == null) {

            return MensajeController::actionMensaje(501);
        }else{
      foreach($dataProvider as $key=>$value){

        $monto = $value['t'] + $monto;


        $array[] = [
          'impuesto' => $value['impuesto'],
          'descripcion' => $value['descripcion'],
          'monto' => $value['t'],
        ];

      }




      //die(var_dump($st));
     //die(var_dump($array));


        $dataProvider = new ArrayDataProvider([
            'allModels' => $array,
           // 'Models' => $st,
            'sort' => [



            ],


        ]);

          return $this->render('/deudas/deudascontribuyente/view-deuda-general', [
            'dataProvider' => $dataProvider,
            'total' => $monto,


            ]);

          }

      }else{

        return MensajeController::actionMensaje(938);


     }

  }

/**
 * [actionVerificarImpuesto description] metodo que recibe el impuesto y determina que vista renderizara
 * @param  [type] $searchRecibo [description]
 * @param  [type] $postJson     [description]
 * @return [type]               [description]
 */
  public function actionVerificarImpuesto()
  {
    //die('aqui');

      $variables = yii::$app->request->post('id');

      $postJson = $variables;

    $html = null;
      //$impuestos = [2,3];
      // Lo siguiente crea un objeto json.
      $jsonObj = json_decode($postJson);
      //die(var_dump($jsonObj));

      if ( $jsonObj->{'impuesto'} == 9 ) {
       // die('es 9');
        $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 10 ) {

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 11 ) {

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaTasa($jsonObj->{'impuesto'});

      } elseif ( $jsonObj->{'impuesto'} == 1 ) {

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaObjeto($jsonObj->{'impuesto'});

     } elseif ( $jsonObj->{'impuesto'} == 3 ) {
  //  die('bueno');

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaObjeto($jsonObj->{'impuesto'});

     } elseif ( $jsonObj->{'impuesto'} == 2 ) {
     // die('bueno');

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaObjeto($jsonObj->{'impuesto'});
     } elseif ( $jsonObj->{'impuesto'} == 12 ) {
     // die('bueno');

          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaObjeto($jsonObj->{'impuesto'});
     } elseif ( $jsonObj->{'impuesto'} == 4 ) {


          // Se buscan todas la planilla que cumplan con esta condicion
          $html = self::actionGetViewDeudaObjeto($jsonObj->{'impuesto'});

     }

      return $html;

  }

    /**
     * [actionGetViewDeudaTasa description] metodo que renderiza la vista de la deuda especifica de las tasas
     * @param  [type] $impuesto [description] impuesto de la tasa
     * @return [type]           [description] retorna la vista
     */
    public function actionGetViewDeudaTasa($impuesto)
    {

      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento

      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda segun Impuesto');

      $provider = $model->getDetalleDeudaTasa($impuesto);
    //die(var_dump($provider));
          foreach($provider as $key=>$value){

              $monto = ($value['monto'] + $value['recargo'] + $value['interes']) - ($value['descuento'] - $value['monto_reconocimiento']);



              $array[] = [
              'planilla' => $value['pagos']['planilla'],
              'impuesto' => $value['tasa']['descripcion'],
              'ano_impositivo' => $value['ano_impositivo'],
              'periodo' => $value['trimestre'],
              'unidad' => $value['exigibilidad']['unidad'],
              'monto' => $monto,
              ];

          }

            $_SESSION['datosPdf'] = $array;
              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [



                  ],


              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-especifica-tasa', [
                'dataProvider' => $dataProvider,


              ]);
    }

    /**
     * [actionGetViewDeudaObjeto description] metodo que renderiza la vista con la deuda por objeto general
     * @param  [type] $impuesto [description] impuesto del objeto
     * @return [type]           [description] retorna la vista con la deuda
     */
    public function actionGetViewDeudaObjeto($impuesto)
    {

      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento

      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda segun Impuesto');

      $provider = $model->getDeudaPorImpuestoPeriodo($impuesto);
   //   die(var_dump($provider));
      if ($provider == 0){
          //die('hjola');
      }else{
          foreach($provider as $key=>$value){





              $array[] = [
              'id_impuesto' => $value['id_impuesto'],
              'impuesto' => $value['impuesto'],
              'descripcion' => $value['descripcion'],
              'monto' => $value['t'],
              'tipo' => $value['tipo'],
              ];

          }

          //die(var_dump($array).'hola');

              $_SESSION['datosPdf'] = $array;

              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [



                  ],


              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-general-vehiculo', [
                'dataProvider' => $dataProvider,


              ]);
              }
    }


    public function actionViewDeudaEspecificaPorObjeto()
    {
      $variables = yii::$app->request->post('id');
        //die(var_dump($variables));
        $postJson = $variables;

    $html = null;
      //$impuestos = [2,3];
      // Lo siguiente crea un objeto json.
      $jsonObj = json_decode($postJson);
      //die(var_dump($jsonObj));

      if ( $jsonObj->{'impuesto'} == 3 ) { 
       

        if($jsonObj->{'tipo'} == 'periodo>0'){
       
        $html = self::actionGetViewDeudaEspecifica($jsonObj->{'impuesto'}, $jsonObj->{'id_impuesto'}, '>');

        }elseif ($jsonObj->{'tipo'} == 'periodo=0'){

          $html = self::actionGetViewDeudaPlanilla($jsonObj->{'impuesto'}, 0, '=');

        }

      } elseif ( $jsonObj->{'impuesto'} == 2 ) {

           if($jsonObj->{'tipo'} == 'periodo>0'){
        //die('es 9');
        $html = self::actionGetViewDeudaEspecifica($jsonObj->{'impuesto'}, $jsonObj->{'id_impuesto'}, '>');

        }elseif ($jsonObj->{'tipo'} == 'periodo=0'){

          $html = self::actionGetViewDeudaPlanilla($jsonObj->{'impuesto'}, 0, '=');

        }

      } elseif ( $jsonObj->{'impuesto'} == 12 ) {

            if($jsonObj->{'tipo'} == 'periodo>0'){

          // Se buscan todas la planilla que cumplan con esta condicion
         $html = self::actionGetViewDeudaEspecifica($jsonObj->{'impuesto'}, $jsonObj->{'id_impuesto'}, '>');

            } elseif ($jsonObj->{'tipo'} == 'periodo=0'){

          $html = self::actionGetViewDeudaPlanilla($jsonObj->{'impuesto'},0 , '=');

         }

         } elseif ( $jsonObj->{'impuesto'} == 1){
          //die('hola');

            if($jsonObj->{'tipo'} == 'periodo>0'){
              //die('mayor');

                $html = self::actionGetViewDeudaEspecifica($jsonObj->{'impuesto'}, 0, '>');

            }elseif($jsonObj->{'tipo'} == 'periodo=0'){
              //die('igual');
                $html = self::actionGetViewDeudaPlanilla($jsonObj->{'impuesto'},0 , '=');
            }

         }elseif ( $jsonObj->{'impuesto'} == 4){
          //die('hola');

            if($jsonObj->{'tipo'} == 'periodo>0'){
              //die('mayor');

                $html = self::actionGetViewDeudaEspecifica($jsonObj->{'impuesto'}, $jsonObj->{'id_impuesto'}, '>');

            }elseif($jsonObj->{'tipo'} == 'periodo=0'){
              //die('igual');
                $html = self::actionGetViewDeudaPlanilla($jsonObj->{'impuesto'},0 , '=');
            }

         }

      return $html;

    }


    public function actionGetViewDeudaEspecifica($impuesto, $idImpuesto, $tipo){
     // die(var_dump($impuesto.' '.$idImpuesto.' '.$tipo));
      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento
      $buscarDatos = 0;
      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda Especifica por Objeto');
      
      if ($impuesto == 1){

        $provider = $model->getDetalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto, $tipo);
        //die(var_dump($provider));
          foreach($provider as $key=>$value){

            $monto = ($value['tmonto'] + $value['trecargo'] + $value['tinteres']) - ($value['tdescuento'] - $value['tmonto_reconocimiento']);
         $array[] = [
                  'id_impuesto' => $value['id_impuesto'],
                   'planilla' => $value['planilla'],
                   'descripcion' => $value['descripcion_impuesto'],
                   'monto' => $monto,



              ];

          }

           

              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [



                  ],


              ]);



              return $this->render('/deudas/deudascontribuyente/view-deuda-especifica-actividad-economica', [
                'dataProvider' => $dataProvider,



              ]);

     }else{


      $provider = $model->getDeudaPorListaObjeto($impuesto);
       // die(var_dump($provider));
        if($provider == null){

          return MensajeController::actionMensaje(501);
        }else{ 


         foreach($provider as $key=>$value){ 

            if($impuesto == 12){

              $informacion = $value['direccion'];
              $idObjeto = $value['id_impuesto'];
            }elseif ($impuesto == 3){

              $idObjeto = $value['id_vehiculo'];
              $informacion = $value['placa'];
            
            }elseif ($impuesto == 2) {

              $idObjeto = $value['id_impuesto'];
              $informacion = $value['direccion'];

            }elseif ($impuesto == 4){

              $idObjeto = $value['id_impuesto'];
              $informacion = $value['descripcion'];
            }

            

              $array[] = [

                 

              'id_objeto' => $idObjeto,
            
              'informacion' => $informacion,
             // 'trimestre' => $value['trimestre'],
              'monto' => $value['t'],
             

              ];

          }

           

              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [



                  ],


              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-especifica', [
                'dataProvider' => $dataProvider,



              ]);
              }
        }
    }


    public function actionGetViewDeudaPlanilla($impuesto, $idImpuesto, $tipo)
    {

      $monto = 0;
      $recargo = 0;
      $interes = 0;
      $descuento = 0;
      $montoR = 0; //monto reconocimiento

      $idContribuyente = $_SESSION['idContribuyente'];
        //die('hay uno seteado');

      $model = new DeudaSearch($idContribuyente);
      $caption = Yii::t('frontend', 'Deuda Especifica por Objeto');

      $provider = $model->getDetalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto, $tipo);
    //  die(var_dump($provider).'hola');
          foreach($provider as $key=>$value){


            $monto = ($value['tmonto'] + $value['trecargo'] + $value['tinteres']) - ($value['tdescuento'] - $value['tmonto_reconocimiento']);


              $array[] = [

              'descripcion' => $value['descripcion'],
              'planilla' => $value['planilla'],
              'impuesto' => $value['descripcion_impuesto'],
              'ano_impositivo' => $value['ano_impositivo'],
            //  'trimestre' => $value['trimestre'],
              'monto' => $value['tmonto'],
              'descuento' => $value['tdescuento'],
              'recargo' => $value['trecargo'],

              //'id_impuesto' => $value['id_impuesto'],
              //'impuesto' => $value['impuesto'],
           // 'descripcion' => $value['descripcion'],
             'monto_reconocimiento' => $value['tmonto_reconocimiento'],
              'monto_total' => $monto,
              ];

          }

              $_SESSION['datosPdf'] = $array;

              $dataProvider = new ArrayDataProvider([
                  'allModels' => $array,
                 // 'Models' => $st,
                  'sort' => [



                  ],


              ]);

              return $this->render('/deudas/deudascontribuyente/view-deuda-objeto-tasa', [
                'dataProvider' => $dataProvider,


              ]);

    }

    public function actionGenerarPdfDeudaEspecifica(){
      $datos = $_SESSION['datosPdf'];
      //die(var_dump($_SESSION['datosPdf']).'hola');



        $mpdf=new mPDF();


        $modelo = ContribuyenteBase::findOne([$_SESSION['idContribuyente']]);

        $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [


                                ]);

        $htmlContribuyente = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf', [
                                                  'model' => $modelo,

                                ]);




        $htmlCuerpo = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-deuda-especifica-pdf', [

                                                            'datos' => $datos,


                                    ]);

        $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-piepagina-pdf', [




        ]);


        $mpdf->WriteHTML($htmlEncabezado);
        $mpdf->WriteHTML($htmlContribuyente);
        $mpdf->WriteHTML($htmlCuerpo);
        $mpdf->WriteHTML($htmlPiePagina);
        $mpdf->Output();

        exit;

    }


    public function actionGenerarPdfDeudaTasa(){
      $datos = $_SESSION['datosPdf'];
      //die(var_dump($_SESSION['datosPdf']).'hola');



        $mpdf=new mPDF();

        $modelo = ContribuyenteBase::findOne([$_SESSION['idContribuyente']]);

        $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [


                                ]);




         $htmlContribuyente = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf', [
                                                  'model' => $modelo,

                                ]);


        $htmlCuerpo = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-deuda-tasa-pdf', [

                                                            'datos' => $datos,


                                    ]);


        $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-piepagina-pdf', [




        ]);






        $mpdf->WriteHTML($htmlEncabezado);
        $mpdf->WriteHTML($htmlContribuyente);
        $mpdf->WriteHTML($htmlCuerpo);
        $mpdf->WriteHTML($htmlPiePagina);

        $mpdf->Output();

        exit;

    }



    public function actionGenerarPdfDeudaEspecificaTasa(){
      $datos = $_SESSION['datosPdf'];
      //die(var_dump($_SESSION['datosPdf']).'hola');



        $mpdf=new mPDF();

        $modelo = ContribuyenteBase::findOne([$_SESSION['idContribuyente']]);

        $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [


                                ]);


        $htmlContribuyente = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf', [
                                                  'model' => $modelo,

                                ]);


        $htmlCuerpo = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-deuda-especifica-tasa-pdf', [

                                                            'datos' => $datos,


                                    ]);


        $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/deudas/layout-piepagina-pdf', [




        ]);






        $mpdf->WriteHTML($htmlEncabezado);
        $mpdf->WriteHTML($htmlContribuyente);
        $mpdf->WriteHTML($htmlCuerpo);
        $mpdf->WriteHTML($htmlPiePagina);

        $mpdf->Output();

        exit;

    }









}


















?>
