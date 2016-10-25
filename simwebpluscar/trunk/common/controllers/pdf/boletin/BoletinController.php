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
        public function generarBoletinEstimada()
        {

            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf');

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                    ]);


            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = $rangoFecha['fechaDesde'] . ' AL ' . $rangoFecha['fechaHasta'];

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
            $mpdf->WriteHTML($htmlPiePagina);


           // $mpdf->WriteHTML($html);
            $mpdf->Output($nombrePDF, 'I');
            exit;
        }




        /**
         * Metodo que entrega un resumen de la declaracion, con sus respectivos calculos por
         * rubro.
         * @param  string $atributo descripcion del atributo que se utuliza para el calculo
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

                $estimadaImpuesto = $calculo->getCalcularPorTipoDeclaracion($atributo);
                $minimo = $calculo->getMinimoTributableRubro();

                $resumen[] = [
                    'ano_impositivo' => $declaracion['actividadEconomica']['ano_impositivo'],
                    'id_rubro' => $declaracion['id_rubro'],
                    'rubro' => $declaracion['rubroDetalle']['rubro'],
                    'descripcion' => $declaracion['rubroDetalle']['descripcion'],
                    'alicuota' => $declaracion['rubroDetalle']['alicuota'],
                    'minimo_ut' => $declaracion['rubroDetalle']['minimo_ut'],
                    'minimo' => $minimo,
                    'impuesto' => $estimadaImpuesto,
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

            for ( $i = 1; $i <= (int)$exigibilidad['exigibilidad']; $i++ ) {

                $monto = 0;
                $calculoRecargo = 0;
                $etiqueta = [];
                $pagarEn = '';
                $recargoEn = '';

                if ( $i == 1 ) {
                    $monto = $montoPorPeriodo + $diferencia;
                } else {
                    $monto = $montoPorPeriodo;
                }
                $recargo->calcularRecargo($this->_año_impositivo, $i, $monto);
                $calculoRecargo = $recargo->getRecargo();
                $recargo->generarEtiquetaRecargo();
                $etiqueta = $recargo->getConfigPenalidad();

                if ( count($etiqueta) > 0 ) {
                    $pagarEn = $etiqueta[$i]['pagarEn'];
                    $recargoEn = $etiqueta[$i]['recargoEn'];
                }
                $resumen[$i] = [
                    'periodo' => $i,
                    'descripcion' => $exigibilidad['unidad'],
                    'monto' => $monto,
                    'pagarEn' => $pagarEn,
                    'recargoEn' => $recargoEn,
                ];
            }

            return $resumen;
        }




    }
?>