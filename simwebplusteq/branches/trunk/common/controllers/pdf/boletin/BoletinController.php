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


    namespace common\controllers\pdf\boletin;

    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use backend\models\aaee\rubro\Rubro;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\declaracion\DeclaracionBaseSearch;
    use common\models\calculo\liquidacion\aaee\CalculoRubro;
    use common\models\calculo\liquidacion\aaee\LiquidacionActividadEconomica;
    use common\models\ordenanza\OrdenanzaBase;
    use common\models\calculo\recargo\Recargo;
    use common\models\calculo\interes\Interes;
    use backend\models\configuracion\descuento\DescuentoSearch;
    use common\models\pago\PagoSearch;



    #We will include the pdf library installed by composer
    #funciona asi, requerimiento
    use mPDF;

    // session_start();
    class BoletinController extends Controller
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
        public function generarBoletinDefinitiva()
        {
            // $barcode = 152222;
            $barcode = $nombrePDF = 'BD-' . $this->_id_contribuyente . '-' . $this->_año_impositivo . $this->_periodo;
            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'BOLETIN DE NOTIFICACION',

                                    ]);

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => true,
                                                            'showRepresentante' => true,
                                    ]);


            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = date('d-m-Y', strtotime($rangoFecha['fechaDesde'])) . ' AL ' . date('d-m-Y', strtotime($rangoFecha['fechaHasta']));

            $resumen = self::actionResumenDeclaracion('reales');

            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-declaracion-pdf',[
                                                            'resumen'=> $resumen,
                                                            'tipoDeclaracion' => 'DEFINITIVA',
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);


            // Deuda segun rubros
            $deudaSegunRubro = 0;
            $deudaSegunRubro = self::getSumaImpuesto('impuesto', $resumen);



            // Resumen de los pagos reconocidos al contribuyente.
            $pago = New PagoSearch();
            $pago->setIdContribuyente($this->_id_contribuyente);
            $resumenPago = $pago->getResumenPagoDefinitiva($this->_año_impositivo, $this->_periodo);

            $htmlCobro = $this->renderPartial('@common/views/plantilla-pdf/boletin/definitiva/layout-cobro-anticipado-pdf',[
                                                            'resumen'=> $resumenPago,
                                                            'resumenDeuda' => $deudaSegunRubro,

                                    ]);


            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $barcode,
                                    ]);





            // Nombre del archivo.
            $nombrePDF = 'BD-' . $this->_id_contribuyente . '-' . $this->_año_impositivo . $this->_periodo;
            $nombre = $nombrePDF;
            $nombrePDF .= '.pdf';

            $mpdf = new mPDF;

            $mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);
            $mpdf->WriteHTML($htmlCobro);
            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);

            $mpdf->Output($nombrePDF, 'I');
            exit;
        }






        /***/
        public function generarBoletinEstimada()
        {
            //$barcode = 152222;
            $barcode = 'BD-' . $this->_id_contribuyente . '-' . $this->_año_impositivo . $this->_periodo;
            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'BOLETIN DE NOTIFICACION',

                                    ]);

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => true,
                                                            'showRepresentante' => true,
                                    ]);


            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = date('d-m-Y', strtotime($rangoFecha['fechaDesde'])) . ' AL ' . date('d-m-Y', strtotime($rangoFecha['fechaHasta']));

            $resumen = self::actionResumenDeclaracion('estimado');

            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-declaracion-pdf',[
                                                            'resumen'=> $resumen,
                                                            'tipoDeclaracion' => 'ESTIMADA',
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);


            // Informacion de las cuotas por cobrar.
            foreach ( $resumen as $i => $r ) {
                $rubroCalculo[$r['rubro']] = $r['impuesto'];
            }
            $resumenCobro = self::actionResumenCobroPenalidad($rubroCalculo);

            $htmlCobro = $this->renderPartial('@common/views/plantilla-pdf/boletin/estimada/layout-cobro-anticipado-pdf',[
                                                            'resumen'=> $resumenCobro,
                                    ]);


            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/boletin/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $barcode,
                                    ]);





            // Nombre del archivo.
            $nombrePDF = 'BE-' . $this->_id_contribuyente . '-' . $this->_año_impositivo . $this->_periodo;
            $nombre = $nombrePDF;
            $nombrePDF .= '.pdf';

            //$html = $htmlEncabezado . $htmlContribuyente . $htmlDeclaracion . $htmlCobro . $htmlPiePagina;

            $mpdf = new mPDF;

            $mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);
            $mpdf->WriteHTML($htmlCobro);
            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);

           // $mpdf->WriteHTML($html);
            $mpdf->Output($nombrePDF, 'I');
            exit;
        }




        /**
         * Metodo que entrega un resumen de la declaracion, con sus respectivos calculos por
         * rubro.
         * @param  string $atributo descripcion del atributo que se utiliza para el calculo
         * - estimado.
         * - reales.
         * - sustitutiva.
         * @return array retorna un arreglo con la informacion de la declaracion y los calculos
         * por rubro.
         */
        private function actionResumenDeclaracion($atributo)
        {
            $resumen = [];
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $declaracion = $declaracionSearch->findRubrosRegistrados($this->_año_impositivo, $this->_periodo);
            $declaracionModel = $declaracion->asArray()->all();

            foreach ( $declaracionModel as $declaracion ) {
                $calculo = New CalculoRubro($declaracion);

                $calculoImpuesto = $calculo->getCalcularPorTipoDeclaracion($atributo);
                $minimo = $calculo->getMinimoTributableRubro();

                $resumen[] = [
                    'ano_impositivo' => $declaracion['actividadEconomica']['ano_impositivo'],
                    'id_rubro' => $declaracion['id_rubro'],
                    'rubro' => $declaracion['rubroDetalle']['rubro'],
                    'descripcion' => $declaracion['rubroDetalle']['descripcion'],
                    'alicuota' => $declaracion['rubroDetalle']['alicuota'],
                    'minimo_ut' => $declaracion['rubroDetalle']['minimo_ut'],
                    'minimo' => $minimo,
                    'impuesto' => $calculoImpuesto,
                    'estimado' => $declaracion['estimado'],
                    'reales' => $declaracion['reales'],
                    'sustitutiva' => $declaracion['sustitutiva'],
                ];
            }

            return $resumen;

        }



        /**
         * Metodo que realiza un resumen de las especificacioes de pago por periodo
         * y los momentos donde se aplican los recargo, e inetreses.
         * @param  array $rubroCalculo arreglo donde el indice es el codigo del rubro
         * y el valor del elemento es el calculo de la liquidacion del mismo.
         * @return array retorna un arreglo donde
         */
        private function actionResumenCobroPenalidad($rubroCalculo)
        {
            $suma = 0;
            $resumen = [];
            $exigibilidad = [];
            $exigibilidad = OrdenanzaBase::getExigibilidadLiquidacion($this->_año_impositivo, 1);

            // Total de lo liquidado.
            foreach ( $rubroCalculo as $key => $value ) {
                $suma = $suma + $value;
            }

            $montoPorPeriodo = (float)number_format($suma / (int)$exigibilidad['exigibilidad'], 2,  '.', '');

            // Se verifica que los periodos obtengan las mismas porciones, sino se le suma
            // la diferencia a uno de los periodos.
            $diferencia = 0;
            $diferencia = $suma - ( $montoPorPeriodo * (int)$exigibilidad['exigibilidad']);
            $diferencia = (float)number_format($diferencia, 2, '.', '');

            $recargo = New Recargo(1);
            $interes = New Interes(1);
            $descuento = New DescuentoSearch(1);

            for ( $i = 1; $i <= (int)$exigibilidad['exigibilidad']; $i++ ) {

                $monto = 0;
                $calculoRecargo = 0;
                $etiqueta = [];
                $etiquetaInt = [];
                $pagarEn = '';
                $recargoEn = '';
                $ipagarEn = '';
                $interesEn = '';

                if ( $i == 1 ) {
                    $monto = $montoPorPeriodo + $diferencia;
                } else {
                    $monto = $montoPorPeriodo;
                }
                $recargo->calcularRecargo($this->_año_impositivo, $i, $monto);
                $calculoRecargo = $recargo->getRecargo();
                $recargo->generarEtiquetaRecargo();
                $etiqueta = $recargo->getConfigPenalidad();

                $interes->setAnoImpositivo($this->_año_impositivo);
                $interes->setPeriodo($i);
                $interes->generarEtiquetaInteres();
                $etiquetaInt = $interes->getConfigPenalidad();

                // Se recibe un arreglo donde
                $config = $descuento->getMontoDescuentoAnual($this->_año_impositivo, $monto);

                if ( count($etiqueta) > 0 ) {
                    $pagarEn = $etiqueta[$i]['pagarEn'];
                    $recargoEn = $etiqueta[$i]['recargoEn'];
                }

                if ( count($etiquetaInt) > 0 ) {
                    $ipagarEn = $etiquetaInt[$i]['ipagarEn'];
                    $interesEn = $etiquetaInt[$i]['interesEn'];
                }

                $resumen[$i] = [
                    'periodo' => $i,
                    'descripcion' => $exigibilidad['unidad'],
                    'monto' => $monto,
                    'pagarEn' => $pagarEn,
                    'recargoEn' => $recargoEn,
                    'ipagarEn' => $ipagarEn,
                    'interesEn' => $interesEn,
                    'descuento' => isset($config['descuento']) ? $config['descuento'] : 0,
                    'fechaDesde' => isset($config['fecha_desde']) ? date('d-m-Y', strtotime($config['fecha_desde'])) : null,
                    'fechaHasta' => isset($config['fecha_hasta']) ? date('d-m-Y', strtotime($config['fecha_hasta'])) : null,
                ];
            }

            return $resumen;
        }



        /**
         * Metodo que suma el monto del impuesto segun el atributo.
         * @param string $atributo descripcion del atributo, 'estimado', 'reales'
         * @return doublo retorna monto de la suma del impuesto.
         */
        public function getSumaImpuesto($atributo, $resumen)
        {
            $suma = 0;
            foreach ( $resumen as $i => $r ) {
                $suma = $suma + $r[$atributo];
            }
            return $suma;
        }




    }
?>