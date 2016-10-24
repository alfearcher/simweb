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
 *  @file DeclaracionBaseSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-09-2016
 *
 *  @class DeclaracionBaseSearch
 *  @brief Clase Modelo principal
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */


    namespace common\models\aaee\boletin;

    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use backend\models\aaee\rubro\Rubro;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\declaracion\DeclaracionBaseSearch;
    use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;
    use common\models\ordenanza\OrdenanzaBase;

    #We will include the pdf library installed by composer
    #funciona asi, requerimiento
    use mPDF;

    // session_start();
    class Boletin extends Controller
    {
	   public $layout = 'layout-main';

	   private $_año_impositivo;
       private $_periodo;
       private $_id_contribuyente;


        /***/
        public function __construct($idContribuyente, $añoImpositivo, $periodo)
        {
            $this->_id_contribuyente = $idContribuyente;
            $this->_año_impositivo = $añoImpositivo;
            $this->_periodo = $periodo;
        }




        /***/
        public function generarBeletinEstimada()
        {
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);

            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $declaracion = $declaracionSearch->findRubrosRegistrados($this->_año_impositivo, $this->_periodo);
            $declaracionModel = $declaracion->asArray()->all();

            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = $rangoFecha['fechaDesde'] . ' AL ' . $rangoFecha['fechaHasta'];

            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf');


            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                    ]);


            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-declaracion-pdf',[
                                                            'declaracionModel'=> $declaracionModel,
                                                            'tipoDeclaracion' => 'ESTIMADA',
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);

            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                    ]);


            $ordenanza = New OrdenanzaBase();
            $exigibilidad = $ordenanza->getExigibilidadLiquidacion($this->_año_impositivo, $this->_periodo);

            $htmlCobro = $this->renderPartial('@common/views/plantilla-pdf/boletin/estimada/layout-cobro-anticipado-pdf',[
                                                            'exigibilidad'=> $exigibilidad,
                                    ]);



            //$html = $encabezado . $contribuyente . $hmtlDeclaracion . $htmlCobro;

            //return $html;
            $mpdf = new mPDF;

            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);
            $mpdf->WriteHTML($htmlCobro);
            $mpdf->WriteHTML($htmlPiePagina);
            $mpdf->Output();
            //exit;
        }



        /***/
        public function generarDataBoletin()
        {
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);

            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $declaracion = $declaracionSearch->findRubrosRegistrados($this->_año_impositivo, $this->_periodo);
            $declaracionModel = $declaracion->asArray()->all();

            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf');


            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                    ]);


            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-declaracion-pdf',[
                                                            'declaracionModel'=> $declaracionModel,
                                    ]);

            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                    ]);


            $ordenanza = New OrdenanzaBase();
            $exigibilidad = $ordenanza->getExigibilidadLiquidacion($this->_año_impositivo, $this->_periodo);

            $htmlCobro = $this->renderPartial('@common/views/plantilla-pdf/boletin/estimada/layout-cobro-anticipado-pdf',[
                                                            'exigibilidad'=> $exigibilidad,
                                    ]);



            //$html = $encabezado . $contribuyente . $hmtlDeclaracion . $htmlCobro;

            //return $html;
            $mpdf = new mPDF;

            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);
            $mpdf->WriteHTML($htmlCobro);
            $mpdf->WriteHTML($htmlPiePagina);
            $mpdf->Output();
            exit;

        }





    }
?>