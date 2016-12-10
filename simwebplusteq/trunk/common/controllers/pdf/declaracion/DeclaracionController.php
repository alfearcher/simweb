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


    namespace common\controllers\pdf\declaracion;

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
    use backend\models\aaee\historico\declaracion\HistoricoDeclaracionSearch;



    #We will include the pdf library installed by composer
    #funciona asi, requerimiento
    use mPDF;

    // session_start();
    class DeclaracionController extends Controller
    {
	   public $layout = 'layout-main';

	   private $_año_impositivo;
       private $_periodo;
       private $_id_contribuyente;


        /**
         * Metodo constructor de la clase.
         * @param integer $idContribuyente identificador del contribuyente.
         * @param integer $añoImpositivo año impositivo declarado.
         * @param integer $periodo identificador del periodo declarado.
         */
        public function __construct($idContribuyente, $añoImpositivo, $periodo)
        {
            $this->_id_contribuyente = $idContribuyente;
            $this->_año_impositivo = $añoImpositivo;
            $this->_periodo = $periodo;
        }





        /**
         * Metodo que permite renderizar al metodo que generar y emitira el
         * comprobante de declaracion.
         * @param  integer $idHistorico identificador del historico de declaracion;
         * @return
         */
        public function actionGenerarComprobanteSegunHistorico($idHistorico)
        {
            $findHistoricoModel = self::findHistorico($idHistorico);
            if ( count($findHistoricoModel) > 0 ) {

                if ( $findHistoricoModel['tipo_declaracion'] == 1 ) {

                    // Comprobante de declaracion estimada.
                    self::actionGenerarComprobanteEstimadaSegunHistorico($findHistoricoModel);

                } elseif ( $findHistoricoModel['tipo_declaracion'] == 2 ) {

                    // Comprobante de declaracion definitiva.
                    self::actionGenerarComprobanteDefinitivaSegunHistorico($findHistoricoModel);

                }
            }
        }




        /***/
        public function actionGenerarCertificadoDeclaracionSegunHistorico($idHistorico)
        {
            $findHistoricoModel = self::findHistorico($idHistorico);
            if ( count($findHistoricoModel) > 0 ) {

                if ( $findHistoricoModel['tipo_declaracion'] == 1 ) {

                    // Certificado de declaracion estimada.
                    self::actionGenerarCertificadoEstimada($findHistoricoModel);

                } elseif ( $findHistoricoModel['tipo_declaracion'] == 2 ) {

                    // Certificado de declaracion definitiva.
                    self::actionGenerarCertificadoDefinitiva($findHistoricoModel);

                }
            }
        }





        /**
         * Metodo que realiza la consulta del historico de la declaracion segun el
         * parametro del identificador del historico.
         * @param  integer $idHistorico identificador generado cuando
         * @return [type]             [description]
         */
        private function findHistorico($idHistorico)
        {
            $search = New HistoricoDeclaracionSearch($this->_id_contribuyente);
            return $historico = $search->findHistoricoDeclaracion($idHistorico);
        }




        /**
         * Metodo que genera un documento pdf que representa un certificado electronico
         * de declaracion de ingresos de estimada. Utiliza como base la infromacion del
         * historico, este historico se guarda cada vez que se realiza o modifica una
         * declaracion.
         * @param  model $historicoModel model de la entidad HistoricoDeclaracion
         * @return view retorna una documento pdf.
         */
        public function actionGenerarCertificadoEstimada($historicoModel)
        {
            $nombre = $historicoModel['serial_control'] . '-C';

            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'CERTIFICADO DE DECLARACION',
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);


            // Informacion del contribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => false,
                                                            'showRepresentante' => false,
                                    ]);


            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = date('d-m-Y', strtotime($rangoFecha['fechaDesde'])) . ' AL ' . date('d-m-Y', strtotime($rangoFecha['fechaHasta']));

            // Informacion del texto.
            $htmlTexto = $this->renderPartial('@common/views/plantilla-pdf/certificado/layout-certificado-declaracion-estimada-pdf',[
                                                            'historico' => $historicoModel,
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);



            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/certificado/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);


            $nombrePDF = $nombre.'.pdf';

            $mpdf = new mPDF;

            //$mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlTexto);

            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);
            $mpdf->Output($nombrePDF, 'I');
            exit;

        }





        /**
         * Metodo que genera un documento pdf que representa un certificado electronico
         * de declaracion de ingresos de estimada. Utiliza como base la infromacion del
         * historico, este historico se guarda cada vez que se realiza o modifica una
         * declaracion.
         * @param  model $historicoModel model de la entidad HistoricoDeclaracion
         * @return view retorna una documento pdf.
         */
        public function actionGenerarCertificadoDefinitiva($historicoModel)
        {
            $nombre = $historicoModel['serial_control'] . '-C';

            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'CERTIFICADO DE DECLARACION',
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);


            // Informacion del contribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => false,
                                                            'showRepresentante' => false,
                                    ]);


            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = date('d-m-Y', strtotime($rangoFecha['fechaDesde'])) . ' AL ' . date('d-m-Y', strtotime($rangoFecha['fechaHasta']));

            // Informacion del texto.
            $htmlTexto = $this->renderPartial('@common/views/plantilla-pdf/certificado/layout-certificado-declaracion-definitiva-pdf',[
                                                            'historico' => $historicoModel,
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);



            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/certificado/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);


            $nombrePDF = $nombre.'.pdf';

            $mpdf = new mPDF;

            //$mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlTexto);

            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);
            $mpdf->Output($nombrePDF, 'I');
            exit;

        }






        /**
         * Metodo que reciba todo al informacion necesaria para la emision del pdf con
         * la informacion del historico de la declaracion. Se arma todos las modulos para
         * luego emitir un unico documente, la informacion de la declaracion viene de la
         * solicitud realizada en posteriores procesos, las informacion relevente de la declaracion
         * como lo son el rubro y los montos declarados, se guardaron en una estructura json
         * para facilitar y unificar la emision del documento. La informacion de este documento
         * servira de base para el Certificado de Declaracion. El cual debe hacer referencia
         * al numero de declaracion (serial-control). El numero de declaracion tiene el formato:
         *
         * AA-123456-12-12-1234-12345.
         *
         * Dicho formato es la agrupacion de varios datos que esta contenido em el historico al
         * momento de aprobar la solicitud de declaracion.
         * @param  model $historcioModel modelo de la entidad HistoricoDeclaracion, es una busqueda
         * por el identificador de la entidad mas el identificador del contribuyente.
         * @return string retorna renderiza una vista en formato pdf. Este formato es el comprobante
         * de declaracion.
         */
        public function actionGenerarComprobanteEstimadaSegunHistorico($historicoModel)
        {
            $nombre = $historicoModel['serial_control'];


            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'DECLARACION DE INGRESOS BRUTOS',
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);

            // Informacion del contribuyente.
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

            // Esta informacion se sacara del historico que se guardo.
            $resumen = self::actionResumenDeclaradoEstimado($historicoModel);

            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-declaracion-base-pdf',[
                                                            'resumen'=> $resumen,
                                                            'tipoDeclaracion' => 'ESTIMADA',
                                                            'periodoFiscal' => $periodoFiscal,
                                                            'fechaEmision' => date('d-m-Y', strtotime($historicoModel['fecha_hora'])),
                                    ]);


            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);



            $nombrePDF = $nombre.'.pdf';

            $mpdf = new mPDF;

            $mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);

            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);
            $mpdf->Output($nombrePDF, 'I');
            exit;
        }








        /**
         * Metodo que reciba todo al informacion necesaria para la emision del pdf con
         * la informacion del historico de la declaracion. Se arma todos las modulos para
         * luego emitir un unico documente, la informacion de la declaracion viene de la
         * solicitud realizada en posteriores procesos, las informacion relevente de la declaracion
         * como lo son el rubro y los montos declarados, se guardaron en una estructura json
         * para facilitar y unificar la emision del documento. La informacion de este documento
         * servira de base para el Certificado de Declaracion. El cual debe hacer referencia
         * al numero de declaracion (serial-control). El numero de declaracion tiene el formato:
         *
         * AA-123456-12-12-1234-12345.
         *
         * Dicho formato es la agrupacion de varios datos que esta contenido em el historico al
         * momento de aprobar la solicitud de declaracion.
         * @param  model $historcioModel modelo de la entidad HistoricoDeclaracion, es una busqueda
         * por el identificador de la entidad mas el identificador del contribuyente.
         * @return string retorna renderiza una vista en formato pdf. Este formato es el comprobante
         * de declaracion.
         */
        public function actionGenerarComprobanteDefinitivaSegunHistorico($historicoModel)
        {
            $nombre = $historicoModel['serial_control'];


            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'DECLARACION DE INGRESOS BRUTOS',
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);

            // Informacion del contribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => true,
                                                            'showRepresentante' => true,
                                    ]);




            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = $rangoFecha['fechaDesde'] . ' AL ' . $rangoFecha['fechaHasta'];

            // Esta informacion se sacara del historico que se guardo.
            $resumen = self::actionResumenDeclaradoDefinitiva($historicoModel);

            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-declaracion-base-definitiva-pdf',[
                                                            'resumen'=> $resumen,
                                                            'tipoDeclaracion' => 'DEFINITIVA',
                                                            'periodoFiscal' => $periodoFiscal,
                                                            'fechaEmision' => $historicoModel['fecha_hora'],
                                    ]);


            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $historicoModel['serial_control'],
                                    ]);



            $nombrePDF = $nombre.'.pdf';

            $mpdf = new mPDF;

            $mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);

            //$mpdf->SetHTMLFooter($htmlPiePagina);
            $mpdf->WriteFixedPosHTML($htmlPiePagina, 15, 220, 180, 30);
            $mpdf->Output($nombrePDF, 'I');
            exit;
        }








        /**
         * Metodo que arma los datos basicos de la declaracion que existe en el historico.
         * @param  model $historicoModel modelo de la consulta realizada sobre el historico de declaracion
         * esta informacion es la guardada durante la solicitud de declaracion. Luego de renderizara a una
         * vista que genere el pdf respectivo.
         * @return array retorna un arreglo de los datos basicos de la declaracion
         */
        private function actionResumenDeclaradoEstimado($historicoModel)
        {
            $resumen = [];

            // $historicoModel['json_rubro'], es una estructura json guardada, donde se
            // coloco un resumen de los datos de la declaracion que se hizo cuando se
            // realizo la solicitud de declaracion.
            $jsonRubros = json_decode($historicoModel['json_rubro'], true);

            foreach ( $jsonRubros as $rubro ) {

                // Se buscan los datos faltantes de la declaracion, estos datos son propios del rubro
                // y no tienen que ver con la declaracion realizada por el usuario.
                $rubroModel = Rubro::findOne($rubro['id_rubro']);

                $resumen[] = [
                    'rubro' => $rubro['rubro'],
                    'descripcion' => $rubro['descripcion'],
                    'estimado' => $rubro['estimado'],
                    'alicuota' => $rubroModel->alicuota,
                    'minimo_ut' => $rubroModel->minimo_ut,
                    'id_contribuyente' => $rubro['id_contribuyente'],
                    'ano_impositivo' => $rubro['ano_impositivo'],
                    'exigibilidad_periodo' => $rubro['id_contribuyente'],
                    'id_impuesto' => $rubro['id_impuesto'],
                    'nro_solicitud' => $rubro['nro_solicitud'],

                ];

            }

            return $resumen;

        }



        /***/
        private function actionResumenDeclaradoDefinitiva($historicoModel)
        {
            $resumen = [];

            // $historicoModel['json_rubro'], es una estructura json guardada, donde se
            // coloco un resumen de los datos de la declaracion que se hizo cuando se
            // realizo la solicitud de declaracion.
            $jsonRubros = json_decode($historicoModel['json_rubro'], true);

            foreach ( $jsonRubros as $rubro ) {

                // Se buscan los datos faltantes de la declaracion, estos datos son propios del rubro
                // y no tienen que ver con la declaracion realizada por el usuario.
                $rubroModel = Rubro::findOne($rubro['id_rubro']);

                $resumen[] = [
                    'rubro' => $rubro['rubro'],
                    'descripcion' => $rubro['descripcion'],
                    //'estimado' => $rubro['estimado'],
                    'reales' => $rubro['reales'],
                    'alicuota' => $rubroModel->alicuota,
                    'minimo_ut' => $rubroModel->minimo_ut,
                    'id_contribuyente' => $rubro['id_contribuyente'],
                    'ano_impositivo' => $rubro['ano_impositivo'],
                    'exigibilidad_periodo' => $rubro['id_contribuyente'],
                    'id_impuesto' => $rubro['id_impuesto'],
                    'nro_solicitud' => $rubro['nro_solicitud'],

                ];

            }

            return $resumen;

        }



    }
?>