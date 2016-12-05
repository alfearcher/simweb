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
 *	@file PlanillaPdfController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class PlanillaPdfController
 *	@brief Clase que gestiona la generacion del pdf de las planillas
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


 	namespace common\controllers\pdf\planilla;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	// use common\models\numerocontrol\NumeroControlSearch;
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\planilla\PlanillaSearch;

	use mPDF;


	/**
	 * Clase controller que gestiona la emision del pdf de la planilla.
	 * Se envia el numero de planilla y se determina el impuesto de la
	 * misma, asi como su tipo de periodo ( periodo > 0 o periodo = 0 )
	 */
	class PlanillaPdfController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_planilla;
		private $_contribuyente;
		private $_cvb_planilla;
		private $_searchPlanilla;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de planilla de liquidacion.
		 */
		public function __construct($planilla)
		{
			$this->_planilla = (int)$planilla;
			$this->_searchPlanilla = New PlanillaSearch($this->_planilla);
		}




		/**
		 * Metodo que inicia la generacion del pdf de la planilla.
		 * @return pdf de la planilla respectiva.
		 */
		public function actionGenerarPlanillaPdf()
		{
			if ( $this->_planilla > 0 ) {
				$model = $this->_searchPlanilla->getDetallePlanilla();

				$result = $model->asArray()->all();

				$this->_contribuyente = ContribuyenteBase::findOne($result[0]['pagos']['id_contribuyente']);
// die(var_dump($this->_contribuyente));
				// Determinar tipo de periodo (periodo > 0 o peridoo = 0) y el tipo
				// de impuesto para renderizar a la planilla pdf correspondiente.
				if ( $result[0]['trimestre'] > 0 ) {
					if ( $result[0]['impuesto'] == 1 ) {	// Actividad Economica.

						return self::actionCrearPlanillaActEconPdf($result);

					} elseif ( $result[0]['impuesto'] == 2 || $result[0]['impuesto'] == 12 ) {	// Inmueble

					} elseif ( $result[0]['impuesto'] == 3 ) {	// Vehiculo

					}

				} else {
					// Solo las planillas con periodos igual a cero (periodo = 0)
					if ( $result[0]['impuesto'] == 9 || $result[0]['impuesto'] == 10 || $result[0]['impuesto'] == 11 ) {

						// Informacion del codigo presupuestario de la tasa. Tipo modelo
						$codigo = $this->_searchPlanilla->getDatosCodigoPresupuesto($result[0]['tasa']['id_codigo']);
						if ( count($codigo) > 0 ) {

							return self::actionCrearPlanillaTasaPdf($result, $codigo->toArray());

						}

					} else {

						return $model;

					}

				}

			}

			return false;
		}




		/***/
		public function actionCrearPlanillaTasaPdf($detallePlanilla, $detallePresupuesto = null)
		{
			$mpdf = new mPDF;
			$nombre = 'prueba2.pdf';

			// Logo de la Alcaldia.
			$htmlLogo = $this->renderPartial('@common/views/plantilla-pdf/planilla/layout/layout-identificador-alcaldia-pdf',[

            					]);

			// Los parametros siguientes se definen de la siguiente manera:
			// x, y, width, height.
			$mpdf->WriteFixedPosHTML($htmlLogo, 20, 10, 32, 32);

			// Informacion de la Oficina de Rentas
			// Los parametros sguientes son:
			// tipo de fuente, negrita o no, tamaño de la fuente.
			$mpdf->SetFont('Arial', '', 7);
	       	$mpdf->Text(15, 30, strtoupper(Yii::$app->oficina->getNombre()));		// Coorenadas x, y.

	       	// Informacion del RIF de la Alcaldia
			$mpdf->SetFont('Arial', '', 7);
	       	$mpdf->Text(28, 33, Yii::$app->ente->getRif());

	       	// Rectangulo angulo superior derecho
	       	$mpdf->RoundedRect(72, 12, 130, 22, 3, D);

	       	// Informacion que va adentro del rectangulo superior derecho.
	       	$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(109, 15, "INFORMACION GENERAL DEL CONTRIBUYENTE");

	       	$contribuyente = ContribuyenteBase::getContribuyenteDescripcion(
	       												$this->_contribuyente->tipo_naturaleza,
	       												$this->_contribuyente->razon_social,
	       												$this->_contribuyente->apellidos,
	       												$this->_contribuyente->nombres);
	       	$mpdf->SetFont('Arial', '', 8);

	       	$mpdf->Ln(2);	// Salto de linea.

	       	// Mover a 55 milimetro a la derecha.
	       	$mpdf->Cell(55);
	       	// Parametros significa lo siguiente:
	       	// width, height, texto, 0 => no se dibuja la linea, 1 => si,
	       	// La 'C' centrar
	       	$mpdf->Cell(128, 0, strtoupper($contribuyente), 0, 1, 'C');

	       	// Datos de la Direccion
	       	$mpdf->Ln(3);
	       	$mpdf->Cell(55);
			$mpdf->Cell(128, 0, strtoupper($this->_contribuyente->domicilio_fiscal), 0, 1, 'C');

			// Datos del Rif o Cedula del Contribuyente.
			$cedulaRif = ContribuyenteBase::getCedulaRifDescripcion(
												$this->_contribuyente->tipo_naturaleza,
			 									$this->_contribuyente->naturaleza,
			 									$this->_contribuyente->cedula,
			 									$this->_contribuyente->tipo);
			if ( $this->_contribuyente->tipo_naturaleza == 0 ) {
				$labelCedulaRif = 'CEDULA: ';
			} else {
				$labelCedulaRif = 'R.I.F: ';
			}

			$labelCatastro = 'Catastro: ';

			$mpdf->Ln(10);
	       	$mpdf->Cell(55);
			$mpdf->Cell(128, 0, $labelCedulaRif . strtoupper($cedulaRif) . '    ' . $labelCatastro, 0, 1, 'C');


			// Recuadro para imprimir la rafaga bancaria.
			// Validacion Terminal Caja.
	       	$mpdf->RoundedRect(5, 88, 110, 25, 3, D);

	       	// Validacion terminal caja
			$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(10, 92, 'VALIDACION TERMINAL: CAJA');


	       	// Informacion de la Cuenta Recaudadora
	       	$cuentaRecaudadora = '0128-0063-18-6300031652';
	       	$mpdf->SetFont('Arial', 'I', 8);
	       	$mpdf->Text(125, 85, 'Nro: Cuenta Recaudadora: ' . $cuentaRecaudadora);


	       	// Informacion de acceso web
	       	$accesoWeb = Yii::$app->ente->getPortalWeb();
	       	$mpdf->SetFont('Arial', 'I', 7);
	       	$mpdf->Text(138, 90, 'Ahora puede acceder desde el portal');

	       	$mpdf->SetFont('Arial', 'I', 7);
	       	$mpdf->Text(143, 93, $accesoWeb);


	       	// Informacion del Codigo validador Bamcario
	       	$mpdf->SetFillColor(225, 225, 225);
	       	$mpdf->RoundedRect(117, 98, 45, 8, 2, DF);

	       	$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(119, 103, 'CODIGO VERIFICADOR BANCARIO');


	       	// Donde se coloca el CVB
	       	$mpdf->RoundedRect(162, 98, 30, 8, 2, D);

	       	$cvb = '123456';
	       	$mpdf->SetFont('Arial', 'B', 8);
	       	$mpdf->Text(172, 103, $cvb);


	       	// Linea punteado inferior
	       	$mpdf->SetDash(1, 1);
	       	$mpdf->Line(5, 120, 200, 120);


	       	// Informacion del Operador
	       	$user = Yii::$app->identidad->getUsuario();
	       	$mpdf->SetFont('Arial', 'B', 6);
	       	$mpdf->Text(8, 118, 'Operador    ' . $user);


	       	// Informacion de tipo de copia
	       	$mpdf->SetFont('Arial', 'B', 6);
	       	$mpdf->Text(70, 118, 'ORIGINAL: CONTRIBUYENTE');


	       	// Informacion del momento de la descarga.
	       	$mpdf->SetFont('Arial', '', 6);
	       	$mpdf->Text(116, 110, date('Y-m-d H:i:s'));


			$mpdf->Output($nombre, 'I');
	       	exit;

		}



		/**
		 * Metodo que dibuja un rectangulo
		 * @param  mPDF $mpdf instancia de la clase.
		 * @return view retorna un rectangulo vacio.
		 */
		private function actionDibujarRectangulo($mpdf, $x, $y, $width, $height)
		{
			return $mpdf->RoundedRect($x, $y, 110, 25, 3, D);
		}


		/***/
		private function actionCrearPlanillaActEconPdf($model)
		{
			// Encabezado e identificador de la Alcaldia
			$htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/planilla/layout/layout-identificador-alcaldia-pdf',[

            					]);


			 $mpdf = new mPDF;

			 $nombre = 'prueba.pdf';
	        //$mpdf->SetHeader($nombrePDF);
	       // $mpdf->WriteHTML($htmlEncabezado);

	       //funciona
	       // $mpdf->Rect(18, 230, 100, 30, D);

	        // eje x, y, w=width, h=height, r=radius, estilo de la linea
	        // 											D = dibuja linea
	        // 											F
	        // 											DF
	       $mpdf->RoundedRect(18, 230, 120, 30, 3, D);
	       $mpdf->SetFont('Arial', 'B', 8);
	       $mpdf->Text(60,258,"Validacion terminal caja");

	       // Se coloca el QR
	       $mpdf->WriteFixedPosHTML($htmlEncabezado, 100, 220, 120, 30);


	       $mpdf->Output($nombre, 'I');
	       exit;
		}





		/**
		 * Metodo que arma el nombre del archivo del recibo. Archivo PDF.
		 * @param  Deposito $model modelo de tipo clase "Deposito".
		 * @return string retorna un nombre que se utilizara como nombre de
		 * archivo para el PDF.
		 */
		private function actionGenerarNombreArchivo($model)
		{
			$ceroAgregado = '0000000';
			$codigo = 'RC';
			$nombrePDF = $codigo . '-';
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


	}
?>