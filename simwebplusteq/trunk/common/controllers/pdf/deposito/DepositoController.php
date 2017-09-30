<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file DepositoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class DepositoController
 *	@brief Clase que gestiona la generacion del pdf del recibo de pago
 *
 *
 *	@property
 *
 *
 *	@method
 *
 *
 *	@inherits
 *
 */


 	namespace common\controllers\pdf\deposito;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\recibo\recibo\ReciboSearch;
	use backend\models\recibo\deposito\Deposito;
	// use common\models\numerocontrol\NumeroControlSearch;
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\historico\cvbrecibo\GenerarValidadorRecibo;
	use common\models\historico\cvbrecibo\GenerarValidadorReciboTresDigito;
	use common\models\historico\cvbrecibo\HistoricoCodigoValidadorBancarioForm;
	use common\models\rafaga\GenerarRafagaRecibo;
	use backend\models\historico\impresion\HistoricoImpresionSearch;
    use backend\models\historico\impresion\HistoricoImpresion;

	use mPDF;


	/**
	 * Clase controller que gestiona la emision del comprobante de recibo de pago en pdf
	 */
	class DepositoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_recibo;
		private $_id_contribuyente;
		private $_nro_control;
		private $_rafaga;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo identificador del recibo, numero que se genero al momneto de
		 * insertar el registro en la entidad "depositos", este numero permite el link con las
		 * otras entidasdes que estan relacionada con el recibo de pago y con el pago en si.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $nroControl numero que se genero al momento de crear el recibo como
		 * control de procesos en el sistama.
		 */
		public function __construct($recibo, $idContribuyente, $nroControl)
		{
			$this->_recibo = $recibo;
			$this->_id_contribuyente = $idContribuyente;
			$this->_nro_control = $nroControl;
			$this->_rafaga = '';
		}




		/**
		 * Metodo que renderiza un recibo de pago en pdf
		 * @return view retorna un pdf del recibo de pago.
		 */
		public function actionGenerarReciboPdf()
		{
			$this->_rafaga = '';
			$cvb = '';
			$mensajeCVB = "";	// Mensaje que se guarda en el historico del cvb.

            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'RECIBO DE PAGO MULTIPLE',

                                    ]);

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => true,
                                                            'showRepresentante' => false,
                                    ]);

            // Identificacion del pago.
            $deposito = Deposito::findOne($this->_recibo);

            // Mensaje del historico del cvb.
			if ( $deposito->estatus == 0 ) {
				// Recibo pendiente
				$mensajeCVB = Yii::t('backend', 'MOSTRANDO RECIBO PENDIENTE');

			} elseif ( $deposito->estatus == 1 ) {
				// Recibo pagado
				$mensajeCVB = Yii::t('backend', 'MOSTRANDO RECIBO PAGADO, FECHA: ' . $deposito->fecha);

			} else {
				// Recibo anulado
				$mensajeCVB = Yii::t('backend', 'MOSTRANDO RECIBO ANULADO');

			}


            // Se determina el codigo validador bancario del recibo.
            $validador = New GenerarValidadorReciboTresDigito($deposito);
            $cvb = $validador->getCodigoValidadorRecibo();

            // Se guarda el historico del documento.
            $historico = New HistoricoCodigoValidadorBancarioForm($deposito);
            $historico->guardarHistorico($mensajeCVB);


            $htmlIdentidadPago = $this->renderPartial('@common/views/plantilla-pdf/recibo/layout-identidad-pago-pdf', [
            												'model' => $deposito,
            												'cvb' => $cvb,
            					]);


            $searchDeuda = New ReciboSearch($this->_id_contribuyente);
            $deudas = $searchDeuda->getDepositoPlanillaPorAnoImpositivoSegunRecibo($this->_recibo);

            // Detalle del pago.
            // Detalle del pdf, planillas contenidas en el recibo.
            $htmlDetallePago = $this->renderPartial('@common/views/plantilla-pdf/recibo/layout-detalle-pago-pdf', [
            																'deudas' => $deudas,
            					]);


            // QR
            $barcode = $this->_recibo;
            $htmlQR = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-qr-pdf',[
            											'barcode' => $barcode,
            					]);

            // Nombre del archivo.
	        $nombrePDF = self::generarNombreArchivo($deposito);
	        if ( trim($nombrePDF) !== '' ) {
	        	$nombre = $nombrePDF . '.pdf';
	        }

	        $mpdf = new mPDF;

	        $mpdf->SetHeader($nombrePDF);
	        $mpdf->WriteHTML($htmlEncabezado);
	        $mpdf->WriteHTML($htmlContribuyente);
	        $mpdf->WriteHTML($htmlIdentidadPago);
	        $mpdf->WriteHTML($htmlDetallePago);

	        self::coletilla($mpdf, 13);
	        self::cuadroFormaPago($mpdf, 13);
	        self::identidadUsuarioPrint($mpdf, 13);

	       	//funciona
	       	// $mpdf->Rect(18, 230, 100, 30, D);

	        // eje x, y, w=width, h=height, r=radius, estilo de la linea
	        // 											D = dibuja linea
	        // 											F
	        // 											DF
	       	$mpdf->RoundedRect(16, 230, 120, 30, 3, D);
	       	$mpdf->SetFont('Arial', 'B', 8);
	       	$mpdf->Text(60,258,"Validacion terminal caja");


	       	// se coloca la rafaga en el documento.
	       	if ( $deposito->estatus == 1 ) {
	       		self::getPrintRafaga($mpdf);
	       	}

	       	// Se coloca el QR
	       	$mpdf->WriteFixedPosHTML($htmlQR, 112, 225, 120, 30);

	       	$mpdf->Output($nombre, 'I');
	       	exit;
		}



		/**
		 * Metodo que retorna la informacion del usuario que esta imprimiendo el
		 * documento.
		 * @param mPDF $mpdf instancia de la clase mPDF
		 * @param integer $y valor vertical de la vista.
		 * @return view
		 */
		public function identidadUsuarioPrint(mPDF $mpdf, $y)
		{
			$identidadUsuario = date('d-m-Y H:i:s') . ' - '. Yii::$app->identidad->getUsuario();
			$mpdf->SetFont('Verdana', 'N', 8);
			$mpdf->Text(16, 268, $identidadUsuario);
			//$mpdf->SetFooter(date('d-m-Y') . ' '. Yii::$app->identidad->getUsuario());
			return;
		}



		/**
		 * Metodo que retorna la coletilla propuesta por Banco Banesco
		 * @param mPDF $mpdf instancia de la clase mPDF
		 * @param integer $y valor vertical de la vista.
		 * @return view
		 */
		public function coletilla(mPDF $mpdf, $y)
		{
			$mpdf->SetY(177);
			$mpdf->SetFont('Arial', 'N', 7);
			$coletilla = Yii::t('backend', '"El depositante declara que los fondos utilizados en esta operación tienen un origen y un destino lícito, y que no provienen de ninguna actividad ilícita de las contempladas en la Ley Orgánica contra El Tráfico Ilícito y El Consumo de Sustancias Estupefacientes y Psicotrópicas y demás Leyes Penales Vigentes en la República Bolivariana de Venezuela. Los depósitos y pagos efectuados quedan sometidos a las condiciones generales vigentes en cada caso a la presente fecha, así como aquellas que en el futuro las modifique total o parcialmente, las cuales se encuentran a disposición del cliente en las Agencias Bancarias. El Banco no asumirá responsabilidad alguna por errores en que incurriere el depositante al momento de completar el formulario".');
			$mpdf->MultiCell(0, 3, utf8_decode((utf8_encode($coletilla))), 0, 'J', false);
			return;
		}



		/**
		 * Metodo que retorna una vista con la seccion inferior del recibo
		 * de pago donde se registrara las formas de pago del recibo.
		 * @param mPDF $mpdf instancia de la clase mPDF
		 * @param integer $y valor vertical de la vista.
		 * @return view
		 */
		public function cuadroFormaPago(mPDF $mpdf, $y)
		{
			// Se coloca el identificador de la forma de pago
	       	//$y = 17;
	       	$indicacionesFormaPago = Yii::t('backend', 'Forma de pago: Máximo un (1) cheque de otros Bancos por recibo, especificar monto y Nro. de cheque. Se permiten pagos mixtos.');

	       	$mpdf->SetY(180 + $y);
			$mpdf->SetFillColor(22, 86, 120); 						// set background color
			$mpdf->SetTextColor(255, 255, 255);
			$mpdf->SetFont('Arial', 'B', 8);
			//$mpdf->Cell(180, 5, 'Forma de Pago', 1, 0, 'C', true);

			/**
			 	RoumdedRect
			 	RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
			  	x, y: top left corner of the rectangle.
				w, h: width and height.
				r: radius of the rounded corners.
				corners: numbers of the corners to be rounded: 1, 2, 3, 4 or any combination (1=top left, 2=top right, 3=bottom right, 4=bottom left).
				style: same as Rect(): F, D (default), FD or DF.
			 */
			$mpdf->RoundedRect(15, 179 + $y, 180, 6, 3, DF);
			$mpdf->Text(92, 183 + $y, 'Forma de Pago');

			$mpdf->SetTextColor(0, 0, 0);
			$mpdf->SetY(185 + $y);
			$mpdf->Cell(180, 7, '', 0, 0, 'L', false);				// crea una celda con las especificaciones.
			$mpdf->SetFont('Arial', '', 8);
			$mpdf->Text(18, 189 + $y, utf8_decode((utf8_encode($indicacionesFormaPago))));		// Coorenadas x, y.

			/**
			$mpdf->SetFillColor(22, 86, 120); 						// set background color
			$mpdf->SetTextColor(255, 255, 255);
			$mpdf->SetY(192 + $y);
			$mpdf->Cell(40, 5, 'Efectivo', 1, 0, 'C', true);
			$mpdf->Cell(40, 5, 'Cargo a Cuenta', 1, 0, 'C', true);
			$mpdf->Cell(100, 5, 'Cheque otro banco', 1, 1, 'C', true);
			*/
			$mpdf->RoundedRect(15, 192 + $y, 180, 6, 3, DF);
			$mpdf->SetTextColor(255, 255, 255);					//	Fuente blanca
			$mpdf->Text(30, 196 + $y, 'Efectivo');
			$mpdf->Text(65, 196 + $y, 'Cargo a Cuenta');
			$mpdf->Text(130, 196 + $y, 'Cheque otro banco');

			// CheckBox
			$mpdf->SetFillColor(255, 255, 255);
			$mpdf->RoundedRect(25, 193 + $y, 3, 3, 0, DF);
			$mpdf->RoundedRect(60, 193 + $y, 3, 3, 0, DF);
			$mpdf->RoundedRect(125, 193 + $y, 3, 3, 0, DF);


			/**
			 	Line(float x1, float y1, float x2, float y2)
			 	x1 Abscisa del primer punto.
				y1 Ordenada del primer punto.
				x2 Abscisa del segundo punto.
				y2 Ordenada del segundo punto.
			 */
			$mpdf->SetDrawColor(255, 255, 255);
			$mpdf->SetLineWidth(0.3);
			$mpdf->Line(55, 192 + $y, 55, 198 + $y);
			$mpdf->Line(95, 192 + $y, 95, 198 + $y);

			$mpdf->SetTextColor(0, 0, 0);					// Fuente negra
			$mpdf->Text(31, 209 + $y, 'Monto');
			$mpdf->Text(70, 209 + $y, 'Monto');
			$mpdf->Text(115, 209 + $y, 'Nro. Cheque');
			$mpdf->Text(168, 209 + $y, 'Monto');

			// Lineas verticales
			$mpdf->SetDrawColor(22, 86, 120);
			$mpdf->SetLineWidth(0.4);
			$mpdf->Line(16, 210 + $y, 193, 210 + $y);
			$mpdf->SetLineWidth(0.2);
			$mpdf->Line(16, 205 + $y, 193, 205 + $y);

			$mpdf->Line(55, 198 + $y, 55, 210 + $y);
			$mpdf->Line(95, 198 + $y, 95, 210 + $y);
			$mpdf->Line(150, 198 + $y, 150, 210 + $y);

			$mpdf->SetDrawColor(0, 0, 0);

			/**
			$mpdf->SetDrawColor(0, 0, 0);
			$mpdf->SetTextColor(0, 0, 0);
			$mpdf->SetY(197 + $y);
			$mpdf->Cell(40, 5, 'Monto', 1, 0, 'C', false);
			$mpdf->Cell(40, 5, 'Monto', 1, 0, 'C', false);
			$mpdf->Cell(60, 5, 'Nro. Cheque', 1, 0, 'C', false);
			$mpdf->Cell(40, 5, 'Monto', 1, 1, 'C', false);

			$mpdf->SetY(202 + $y);
			$mpdf->Cell(40, 5, '', 1, 0, 'C', false);
			$mpdf->Cell(40, 5, '', 1, 0, 'C', false);
			$mpdf->Cell(60, 5, '', 1, 0, 'C', false);
			$mpdf->Cell(40, 5, '', 1, 1, 'C', false);
			*/
			return;
		}








		/**
		 * Metodo que arma el nombre del archivo del recibo. Archivo PDF.
		 * @param  Deposito $model modelo de tipo clase "Deposito".
		 * @return string retorna un nombre que se utilizara como nombre de
		 * archivo para el PDF.
		 */
		private function generarNombreArchivo($model)
		{
			$ceroAgregado = '0000000';
			$codigo = 'RC';
			$nombrePDF = $codigo;
			$preSerial = '';
			$serial = '';
			$parametros = [
				'recibo' => 7,
				'id_contribuyente' => 6,
				'nro_control' => 6,
			];

			foreach ( $parametros as $key => $value ) {
				if ( isset($model->$key) ) {
					$preSerial = '';
					$serial = '';
					$preSerial = $ceroAgregado . $model->$key;
					$serial = substr($preSerial, -($value));
					$nombrePDF = $nombrePDF . '-' . $serial;
				} else {
					$nombrePDF = '';
				}
			}
			return $nombrePDF;
		}



		/**
		 * Metodo que se encarga de generar la rafaga del recibo actual.
		 * @return array
		 */
		public function generarRafagaReciboActual()
		{
			$this->_rafaga = '';
			$generarRafaga = New GenerarRafagaRecibo($this->_recibo);
            $rafaga = $generarRafaga->getRafaga();
            if ( count($rafaga) > 0 && count($generarRafaga->getErrores()) == 0 ) {
            	$this->_rafaga = $rafaga;
            } else {
            	$this->_rafaga = [];
            }
		}



		/**
		 * Metodo que arma y genera la impresion de la rafaga del recibo para
		 * mostrarlo en el documento pdf.
		 * @return view
		 */
		private function getPrintRafaga(mPDF $mpdf)
		{
			self::generarRafagaReciboActual();
			if ( count($this->_rafaga) > 0 ) {
				$rafaga = $this->_rafaga;

				$mpdf->SetFont('Arial', 'N', 8);
		       	// Texto de la rafaga.
		       	$mpdf->Text(18, 234, $rafaga['alcaldia']);
		       	$mpdf->Text(18, 238, $rafaga['id_contribuyente'] . ' ' . $rafaga['contribuyente']);
		       	$mpdf->Text(18, 242, 'R'. $rafaga['recibo'] . ' - total: ' . Yii::$app->formatter->asDecimal($rafaga['monto'], 2) . ' -  fecha pago: ' . date('d-m-Y', strtotime($rafaga['fecha'])));

		       	// Formas de pagos del Recibo.
		       	foreach ( $rafaga['forma_pago'] as $formaPago ) {
		       		$forma .= $formaPago['descripcion'] . ' : ' . $formaPago['aporte'] . '  -  ';
		       	}

		       	$mpdf->Text(18, 246, $forma);
		       	$mpdf->Text(18, 254, 'impreso por: ' . $rafaga['usuario'] . ' ' . $rafaga['fecha_hora']);
		    }
	       	return;
		}



		/**
		 * Netodo setter para obtener la rafaga
		 * @return array arreglo con la informacion generada de la rafaga.
		 */
		private function getRafagaActual()
		{
			return $this->_rafaga;
		}



		/***/
        public function guardarHistoricoImpresion(Deposito $deposito)
        {
            $result = false;

            $impresionModel = New HistoricoImpresion();
            $impresionModel->documento = 'RECIBO';
            $impresionModel->nro_documento = $deposito['recibo'];
            $impresionModel->usuario = Yii::$app->identidad->getUsuario();
            $impresionModel->fecha_hora = date('Y-m-d H:i:s');
            $impresionModel->fuente_json = json_encode($deposito);
            $impresionModel->observacion = '';
            $impresionModel->nro_control = 0;
            $impresionModel->ip_maquina = isset(Yii::$app->request->userIP) ? Yii::$app->request->userIP : '';
            $impresionModel->host_name = isset(Yii::$app->request->userHost) ? Yii::$app->request->userHost : '';

            $historicoSearch = New HistoricoImpresionSearch();
            $result = $historicoSearch->guardar($impresionModel);
            return $result;
        }


	}
?>