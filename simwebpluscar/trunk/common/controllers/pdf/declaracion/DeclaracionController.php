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
         * Metodo que recaba todo al informacion necesaria para la emision del pdf con
         * la informacion de la declaracion. Se arma todos las modulos para luego emitir
         * un unico documente, la informacion de la declaracion viene de la solicitud
         * realizada en posteriores ocasiones, las informacion relevente de la declaracion
         * como lo son el rubro y los montos declarados, se gusraron en una estructura json
         * para facilitar y unificar la emision del documento. La informacion de este documento
         * servira de base para el Certificado de Declaracion. El cual debe hacer referencia
         * al numero de declaracion. El numero de declaracion tiene el formato:
         *
         * AA-123456-12-12-1234-12345.
         *
         * Dicho formato es la agrupacionde varios datos que esta contenido em el historico al
         * momento de aprobar la solicitud de declaracion.
         * @param  model $idHistorico modelo de la entidad HistoricoDeclaracion, es una busqueda
         * por el identificador de la entidad mas el identificador del contribuyente.
         * @return string retorna renderiza una vista en formato pdf. Este formato es el comprobante
         * de declaracion.
         */
        public function generarDeclaracionEstimadaSegunIdHistorico($idHistorico)
        {
             // Se busca la informacion del historico
            $historico = self::findHistorico($idHistorico);
            $nombre = $historico['serial_control'];


            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'DECLARACION DE INGRESOS BRUTOS',
                                                            'barcode' => $historico['serial_control'],
                                    ]);

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                    ]);




            // Informacion de la declaracion.
            $declaracionSearch = New DeclaracionBaseSearch($this->_id_contribuyente);
            $rangoFecha = $declaracionSearch->getRangoFechaDeclaracion($this->_año_impositivo);
            $periodoFiscal = $rangoFecha['fechaDesde'] . ' AL ' . $rangoFecha['fechaHasta'];

            // Esta informacion se sacara del historico que se guardo.
            $resumen = self::actionResumenDeclarado($historico);

            $htmlDeclaracion = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-declaracion-base-pdf',[
                                                            'resumen'=> $resumen,
                                                            'tipoDeclaracion' => 'ESTIMADA',
                                                            'periodoFiscal' => $periodoFiscal,
                                    ]);


            // informacion del pie de pagina.
            $htmlPiePagina = $this->renderPartial('@common/views/plantilla-pdf/declaracion/layout-piepagina-pdf',[
                                                            'director'=> Yii::$app->oficina->getDirector(),
                                                            'nombreCargo' => Yii::$app->oficina->getNombreCargo(),
                                                            'barcode' => $historico['serial_control'],
                                    ]);



            $nombrePDF = $nombre.'.pdf';

            $mpdf = new mPDF;

            $mpdf->SetHeader($nombre);
            $mpdf->WriteHTML($htmlEncabezado);
            $mpdf->WriteHTML($htmlContribuyente);
            $mpdf->WriteHTML($htmlDeclaracion);

            $mpdf->SetHTMLFooter($htmlPiePagina);

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
        private function actionResumenDeclarado($historicoModel)
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



    }
?>