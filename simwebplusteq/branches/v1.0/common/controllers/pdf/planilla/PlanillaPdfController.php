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
	use common\models\historico\cvbplanilla\GenerarValidadorPlanilla;
	use common\models\ordenanza\OrdenanzaBase;
	use common\models\descuento\AplicarDescuento;
	use common\models\descuento\AplicarDescuentoViviendaPrincipal;
	use common\models\calculo\actualizar\ActualizarPlanilla;
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
		private $_codigoValidador;
		private $_fechaVcto;
		private $_aplicarDescuento;						// Instacia de AplicarDescuento().
		private $_aplicarDescuentoViviendaPrincipal;		// Instancia de AplicarDescuentoViviendaPrincipal()
		private $_actualizarPlanilla;
		public $_objeto;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $planilla numero de planilla de liquidacion.
		 */
		public function __construct($planilla, $objeto = false)
		{
			$this->_planilla = (int)$planilla;
			$this->_searchPlanilla = New PlanillaSearch($this->_planilla);
			$this->_actualizarPlanilla = New ActualizarPlanilla($this->_planilla);
			$this->_aplicarDescuento = New AplicarDescuento($this->_planilla);
			$this->_aplicarDescuentoViviendaPrincipal = New AplicarDescuentoViviendaPrincipal($this->_planilla);
			$this->_objeto = $objeto;

		}



		/***/
		private function determinarFechaVctoPlanilla()
		{
			// Se detremina la fecha de vencimiento de la planilla.
			$fecha = OrdenanzaBase::getFechaVencimientoSegunFecha(date('Y-m-d'));
			$this->_fechaVcto = $fecha;

		}


		/***/
		private function determinarCodigoValidadorBancario()
		{
			// Se detremina el codigo validador bancario de la planilla.
			$generador = New GenerarValidadorPlanilla($this->_planilla);
       		$this->_codigoValidador = $generador->getCodigoValidadorBancarioPlanilla();
		}



		/***/
		public function getFechaVctoPlanilla()
		{
			return $this->_fechaVcto;
		}



		/***/
		public function getCodigoValidadorBancarioPlanilla()
		{
			return $this->_codigoValidador;
		}



		/**
		 * Metodo que inicia la generacion del pdf de la planilla.
		 * @return pdf de la planilla respectiva.
		 */
		public function actionGenerarPlanillaPdf()
		{
			return;
			if ( $this->_planilla > 0 ) {

				$this->_actualizarPlanilla->iniciarActualizacion();

				$this->_aplicarDescuentoViviendaPrincipal->iniciarDescuentoViviendaPrincipal();
				$this->_aplicarDescuento->iniciarDescuento();

				$model = $this->_searchPlanilla->getDetallePlanilla($this->_objeto);

				$result = $model->asArray()->all();

				self::determinarCodigoValidadorBancario();
				self::determinarFechaVctoPlanilla();

				$this->_contribuyente = ContribuyenteBase::findOne($result[0]['pagos']['id_contribuyente']);

				// Determinar tipo de periodo (periodo > 0 o peridoo = 0) y el tipo
				// de impuesto para renderizar a la planilla pdf correspondiente.
				if ( $result[0]['trimestre'] > 0 ) {
					if ( $result[0]['impuesto'] == 1 ) {	// Actividad Economica.

						self::actionCrearPlanillaActEconPdf($result);

					} elseif ( $result[0]['impuesto'] == 2 || $result[0]['impuesto'] == 12 ) {	// Inmueble

						self::actionCrearPlanillaInmueblePdf($result);

					} elseif ( $result[0]['impuesto'] == 3 ) {	// Vehiculo

						self::actionCrearPlanillaVehiculoPdf($result);

					}

				} else {
					// Solo las planillas con periodos igual a cero (periodo = 0)
					if ( $result[0]['impuesto'] == 9 || $result[0]['impuesto'] == 10 || $result[0]['impuesto'] == 11 ) {

						// Informacion del codigo presupuestario de la tasa. Tipo modelo
						$codigo = $this->_searchPlanilla->getDatosCodigoPresupuesto($result[0]['tasa']['id_codigo']);
						if ( count($codigo) > 0 ) {

							self::actionCrearPlanillaTasaPdf($result, $codigo->toArray());

						}

					} else {

						if ( !$this->_objeto ) {
							// Informacion del codigo presupuestario de la tasa. Tipo modelo
							$codigo = $this->_searchPlanilla->getDatosCodigoPresupuesto($result[0]['tasa']['id_codigo']);

							if ( count($codigo) > 0 ) {

								self::actionCrearPlanillaTasaPdf($result, $codigo->toArray());

							}

						} else {
							// Es un objeto, no es inmueble, ni vehiculo.
							if ( $result[0]['impuesto'] == 4 ) {

								self::actionCrearPlanillaPropagandaPdf($result);

							} if ( $result[0]['impuesto'] == 6 ) {

							}

						}
					}

				}

			}

			return false;
		}




		/***/
		private function actionCrearPlanillaInmueblePdf($detallePlanilla)
		{

			$y = 0;
			$datosVehiculo = $detallePlanilla[0]['inmueble'];

			$mpdf = new mPDF;
			$nombre = 'PL' . $detallePlanilla[0]['pagos']['planilla'] . ' - ' . date('Y-m-d H:i:s') . '.pdf';

			self::actionViewEncabezadoPrincipal($mpdf, 0, $datosVehiculo);
			$mpdf->Ln(8);

			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla);

			self::actionGetViewRafaga($mpdf);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf);
			self::actionGetViewCodigoValidador($mpdf);

			self::actionGetViewInfoRestante($mpdf);

			// Parte inferior
			$mpdf->Ln(71);

			$y = 132;
			self::actionViewEncabezadoPrincipal($mpdf, $y, $datosVehiculo);
			$mpdf->Ln(15);
			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla , $y);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y);

			self::actionGetViewRafaga($mpdf, $y);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y);

			self::actionGetViewCodigoValidador($mpdf, $y);
			self::actionGetViewInfoRestante($mpdf, strtoupper(Yii::$app->oficina->getNombre()), $y);

			$mpdf->Output($nombre, 'I');
	       	exit;

		}





		/***/
		private function actionCrearPlanillaVehiculoPdf($detallePlanilla)
		{

			$y = 0;
			$datosVehiculo = $detallePlanilla[0]['vehiculo'];

			$mpdf = new mPDF;
			$nombre = 'PL' . $detallePlanilla[0]['pagos']['planilla'] . ' - ' . date('Y-m-d H:i:s') . '.pdf';

			self::actionViewEncabezadoPrincipal($mpdf, 0, $datosVehiculo);
			$mpdf->Ln(8);

			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla);

			self::actionGetViewRafaga($mpdf);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf);
			self::actionGetViewCodigoValidador($mpdf);

			self::actionGetViewInfoRestante($mpdf);

			// Parte inferior
			$mpdf->Ln(71);

			$y = 132;
			self::actionViewEncabezadoPrincipal($mpdf, $y, $datosVehiculo);
			$mpdf->Ln(15);
			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla , $y);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y);

			self::actionGetViewRafaga($mpdf, $y);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y);

			self::actionGetViewCodigoValidador($mpdf, $y);
			self::actionGetViewInfoRestante($mpdf, strtoupper(Yii::$app->oficina->getNombre()), $y);

			$mpdf->Output($nombre, 'I');
	       	exit;

		}



		/***/
		private function actionCrearPlanillaPropagandaPdf($detallePlanilla)
		{

			$y = 0;
			$datosPropaganda = $detallePlanilla[0]['propaganda'];

			$mpdf = new mPDF;
			$nombre = 'PL' . $detallePlanilla[0]['pagos']['planilla'] . ' - ' . date('Y-m-d H:i:s') . '.pdf';

			self::actionViewEncabezadoPrincipal($mpdf, 0);
			$mpdf->Ln(8);

			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla);

			self::actionGetViewRafaga($mpdf);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf);
			self::actionGetViewCodigoValidador($mpdf);

			self::actionGetViewInfoRestante($mpdf);

			// Parte inferior
			$mpdf->Ln(71);

			$y = 132;
			self::actionViewEncabezadoPrincipal($mpdf, $y);
			$mpdf->Ln(15);
			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla , $y);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y);

			self::actionGetViewRafaga($mpdf, $y);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y);

			self::actionGetViewCodigoValidador($mpdf, $y);
			self::actionGetViewInfoRestante($mpdf, strtoupper(Yii::$app->oficina->getNombre()), $y);

			$mpdf->Output($nombre, 'I');
	       	exit;

		}




		/***/
		private function actionCrearPlanillaActEconPdf($detallePlanilla)
		{

			$y = 0;

			$mpdf = new mPDF;
			$nombre = 'PL' . $detallePlanilla[0]['pagos']['planilla'] . ' - ' . date('Y-m-d H:i:s') . '.pdf';

			self::actionViewEncabezadoPrincipal($mpdf);
			$mpdf->Ln(8);

			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla);

			self::actionGetViewRafaga($mpdf);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf);
			self::actionGetViewCodigoValidador($mpdf);

			self::actionGetViewInfoRestante($mpdf);

			// Parte inferior
			$mpdf->Ln(71);

			$y = 132;
			self::actionViewEncabezadoPrincipal($mpdf, $y);
			$mpdf->Ln(15);
			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla , $y);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y);

			self::actionGetViewRafaga($mpdf, $y);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y);

			self::actionGetViewCodigoValidador($mpdf, $y);
			self::actionGetViewInfoRestante($mpdf, strtoupper(Yii::$app->oficina->getNombre()), $y);

			$mpdf->Output($nombre, 'I');
	       	exit;

		}





		/**
		 * Metodo que arma la planilla de periodos iguales a cero que fueron liquidadas
		 * @param  array $detallePlanilla arreglo con los detalles de la planilla de liquidacion.
		 * Posee entre otros datos, el impuesto, exigibilidad, etc.
		 * @param  array $detallePresupuesto arreglo con los datos del codigo presupuestario al cual
		 * esta asociado la planilla.
		 * @return pdf retorna una vista en pdf de la planilla de liqiudacion.
		 */
		public function actionCrearPlanillaTasaPdf($detallePlanilla, $detallePresupuesto = null)
		{

			$y = 0;

			$mpdf = new mPDF;
			$nombre = 'PL' . $detallePlanilla[0]['pagos']['planilla'] . ' - ' . date('Y-m-d H:i:s') . '.pdf';

			self::actionViewEncabezadoPrincipal($mpdf);

			$mpdf->Ln(8);

			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla);

			self::actionGetViewRafaga($mpdf);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf);
			self::actionGetViewCodigoValidador($mpdf);

			self::actionGetViewInfoRestante($mpdf);

			// Parte inferior
			$mpdf->Ln(71);

			$y = 132;
			self::actionViewEncabezadoPrincipal($mpdf, $y);
			$mpdf->Ln(10);
			self::actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y);
			self::actionGetSubTituloDetalle($mpdf, $detallePlanilla , $y);
			self::actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y);
			self::actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y);

			self::actionGetViewRafaga($mpdf, $y);
			self::actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y);

			self::actionGetViewCodigoValidador($mpdf, $y);
			self::actionGetViewInfoRestante($mpdf, strtoupper(Yii::$app->oficina->getNombre()), $y);

			$mpdf->Output($nombre, 'I');
	       	exit;

		}





		/**
		 * Metodo que renderiza una vista con la siguiente informacion:
		 * Encabezado:
		 * - Fecha Emision.
		 * - Fecha Vencimiento.
		 * - ID.
		 * - Nro Liquidacion.
		 * - Control.
		 * Ademas de los datos de cada uno de estos encabezados.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  array $detallePlanilla arreglo con los detalles de la planilla de liquidacion.
		 * Posee entre otros datos, el impuesto, exigibilidad, etc.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista superior de la planilla con la informacion indicada.
		 */
		private function actionGetViewPrimerEncabezado($mpdf, $detallePlanilla, $y = 0)
		{
			$mpdf->SetY(35 + $y);
			// Primeros datos de los detalles de la planilla.
			// Esto es el encabezado inicial:
			// Fecha Emision
			// Fecha vcto
			// ID
			// Nro Liquidacion
			// Control.
			$mpdf->SetFont('Arial', 'B', 7);

			$mpdf->Cell(-10);
			$mpdf->SetFillColor(205, 205, 205);
			$mpdf->Cell(35, 5, 'FECHA EMISION', 1, 0, 'C', true);

			$mpdf->Cell(35, 5, 'FECHA VENCIMIENTO', 1, 0, 'C', true);

			$mpdf->Cell(45, 5, 'ID', 1, 0, 'C', true);

			$mpdf->Cell(45, 5, 'Nro. LIQUIDACION', 1, 0, 'C', true);

			$mpdf->Cell(35, 5, 'CONTROL', 1, 1, 'C', true);

			// Ahora se solocan los datos de los encabezados anteriores.
			$mpdf->SetFont('Arial', 'B', 8);
			$mpdf->Cell(-10);
			// Fecha Emision
			$mpdf->Cell(35, 5, date('d-m-Y'), 1, 0, 'C');
			// Fecha Vcto
			$fechaVcto = date('d-m-Y', strtotime(self::getFechaVctoPlanilla()));		// Invocar metodo que devuelva la fecha final de un mes.
			$mpdf->SetFont('Arial', 'B', 10);
			$mpdf->Cell(35, 5, $fechaVcto, 1, 0, 'C');
			// ID Contribuyente
			$mpdf->Cell(45, 5, $detallePlanilla[0]['pagos']['id_contribuyente'], 1, 0, 'C');
			// Nro Liquidacion
			$mpdf->Cell(45, 5, $detallePlanilla[0]['pagos']['planilla'], 1, 0, 'C');
			// Control
			$control = $detallePlanilla[0]['id_impuesto'];
			$mpdf->Cell(35, 5, $control, 1, 1, 'C');

		}




		/**
		  * Metodo que renderiza una vista de la planilla de liquidacion que contiene:
		 * - Logo de la Alcaldia.
		 * - Descripcion de la oficona recaudadora.
		 * - Rif de la Alcaldia.
		 * - Rectangulo superior derecho.
		 * 	 + Etiqueta que indica "INFORMACION GENRAL DEL CONTRIBUYENTE"
		 *   + Descripcion del Contribuyente.
		 *   + Domicilio principal del contribuyente, en caso de que la planilla
		 * sea de Inmuebles Urbano debe mostrar la direccion del inmueble.
		 * 	 + Rif o Cedula de contribuyente.
		 *   + Si la planilla es de Vehiculos de mostrar:
		 *   	a - Placa.
		 *    	b - Marca.
		 *      c - Modelo.
		 *      d - Color.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @param  array  $datosObjeto arreglo con la informacion particular de un objeto imponible
		 * ( sea Inmueble o Vehiculo).
		 * @return pdf retorna pdf de la vista superior de la planilla con la informacion indicada.
		 */
		private function actionViewEncabezadoPrincipal($mpdf, $y = 0, $datosObjeto = [])
		{
			// Logo de la Alcaldia.
			$htmlLogo = $this->renderPartial('@common/views/plantilla-pdf/planilla/layout/layout-identificador-alcaldia-pdf');

			// Los parametros siguientes se definen de la siguiente manera:
			// x, y, width, height.
			$mpdf->WriteFixedPosHTML($htmlLogo, 20, 8 + $y, 32, 32);

			// Informacion de la Oficina de Rentas
			// Los parametros sguientes son:
			// tipo de fuente, negrita o no, tamaño de la fuente.
			$mpdf->SetFont('Arial', '', 7);
	       	$mpdf->Text(15, 28 + $y, strtoupper(Yii::$app->oficina->getNombre()));		// Coorenadas x, y.

	       	// Informacion del RIF de la Alcaldia
			$mpdf->SetFont('Arial', '', 7);
	       	$mpdf->Text(28, 31 + $y, Yii::$app->ente->getRif());

	       	// Rectangulo angulo superior derecho
	       	$mpdf->RoundedRect(72, 8 + $y, 130, 22, 3, D);

	       	// Informacion que va adentro del rectangulo superior derecho.
	       	$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(109, 11 + $y, "INFORMACION GENERAL DEL CONTRIBUYENTE");

	       	$cedulaRif = ContribuyenteBase::getCedulaRifDescripcion(
												$this->_contribuyente->tipo_naturaleza,
			 									$this->_contribuyente->naturaleza,
			 									$this->_contribuyente->cedula,
			 									$this->_contribuyente->tipo);

	       	$contribuyente = ContribuyenteBase::getContribuyenteDescripcion(
	       												$this->_contribuyente->tipo_naturaleza,
	       												$this->_contribuyente->razon_social,
	       												$this->_contribuyente->apellidos,
	       												$this->_contribuyente->nombres);

	       	$placa = '';
			$marca = '';
			$modelo = '';
			$añoVehiculo = '';
			$color = '';
			$domicilio = '';
			$labelVehiculo = '';
			$domicilio = trim($this->_contribuyente->domicilio_fiscal);

	       	if ( count($datosObjeto) > 0 ) {
				if ( isset($datosObjeto['placa']) ) {
					$placa = 'PLACA: ' . $datosObjeto['placa'];
					$marca = 'MARCA: ' . $datosObjeto['marca'];
					$modelo = 'MODELO: ' . $datosObjeto['modelo'];
					$añoVehiculo = 'AÑO: ' . $datosObjeto['ano_vehiculo'];
					$color = 'COLOR: ' . $datosObjeto['color'];
					$labelVehiculo = $marca . ' ' . $modelo . ' ' . $añoVehiculo . ' ' . $color;

				} elseif ( isset($datosObjeto['direccion']) ) {
					$domicilio = trim($datosObjeto['direccion']);

				}
			} else {
				$domicilio = trim($this->_contribuyente->domicilio_fiscal);
			}


	       	if ( $this->_contribuyente->tipo_naturaleza == 0 ) {
				$labelCedulaRif = 'CEDULA: ' . strtoupper($cedulaRif) . '  ' . $placa;
			} else {
				$labelCedulaRif = 'R.I.F: ' . strtoupper($cedulaRif) . '  ' . $placa;
			}

			$labelCatastro = 'Catastro: ';

			$mpdf->SetFont('Arial', '', 7);

	       	// Mover a 60 milimetro a la derecha.
	       	//$mpdf->Cell(60);
	       	// Parametros significa lo siguiente:
	       	// width, height, texto, 0 => no se dibuja la linea, 1 => si,
	       	// La 'C' centrar

			if ( $y == 0 ) {
				$mpdf->SetY(14);
			} else {
				$mpdf->SetY(146);
			}
			$mpdf->Cell(60);
	       	$mpdf->Cell(125, 2, strtoupper($contribuyente), 0, 1, 'C');

	       	// Datos de la Domicilio principal
	       	$mpdf->Ln(1);
	       	$mpdf->Cell(60);
	       	$justificar = 'J';
	       	$espacio = 3;

	       	$lengDomicilio = strlen(trim($domicilio));
	       	if ( $lengDomicilio <= 85 ) {
	       		$justificar = 'C';
	       		$espacio = 2;
	       	}
			$mpdf->MultiCell(125, $espacio, $domicilio, 0, $justificar);
			$mpdf->Cell(0, 0, '', 0, 1, 'C');

			$mpdf->Text(100, 26 + $y, $labelCedulaRif . '    ' . $labelCatastro);

			if ( trim($labelVehiculo) !== '' ) {
				$mpdf->Text(80, 29 + $y, $labelVehiculo);
			}
		}




		/**
		 * Metodo que renderiza una vista con la etiqueta de un subtitulo.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  array $detallePlanilla arreglo con los detalles de la planilla de liquidacion.
		 * Posee entre otros datos, el impuesto, exigibilidad, etc.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetSubTituloDetalle($mpdf, $detallePlanilla, $y = 0)
		{
			$titulo = '';
			if ( $detallePlanilla[0]['impuesto'] == 9 || $detallePlanilla[0]['impuesto'] == 10 || $detallePlanilla[0]['impuesto'] == 11 ) {
				$titulo = 'DETALLE DE PAGO DE IMPUESTOS VARIOS';
			} else {
				$titulo = 'DETALLE DE PAGO DE ' . strtoupper($detallePlanilla[0]['impuestos']['descripcion'])  ;
			}

			// Segunda encabezado con datos.
			$mpdf->SetFont('Arial', 'B', 7);

			$mpdf->Cell(-10);
			$mpdf->SetFillColor(205, 205, 205);
			$mpdf->Cell(195, 5, $titulo, 1, 1, 'C', true);

		}





		/**
		 * Metodo que renderiza una vista con la siguiente informacion:
		 * Encabezado:
		 * - Año.
		 * - Impuesto.
		 * - Codigo.
		 * - Monto Impuesto o Tasa.
		 * - Recargos.
		 * - Interes.
		 * - SubTotal.
		 * Ademas de los datos de cada uno de los encabezados mencionados.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  array $detallePlanilla arreglo con los detalles de la planilla de liquidacion.
		 * Posee entre otros datos, el impuesto, exigibilidad, etc.
		 * @param  array $detallePresupuesto arreglo con los datos del codigo presupuestario al cual
		 * esta asociado la planilla.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewSegundoDetalle($mpdf, $detallePlanilla, $detallePresupuesto, $y = 0)
		{
			$sumaMonto = 0;
			$sumaRecargo = 0;
			$sumaInteres = 0;
			$sumaDescuento = 0;
			$sumaReconocimiento = 0;
			$subTotal = 0;
			$lapsos = [];

			//Detalle que van debajo del subtitulo
			$mpdf->SetFont('Arial', 'B', 7);

			$mpdf->Cell(-10);
			$mpdf->Cell(10, 5, 'AÑO', 0, 0, 'C');

			if ( $detallePlanilla[0]['trimestre'] > 0 ) {
				$mpdf->Cell(40, 5, 'PERIODO(S)', 0, 0, 'C');
				$lapsos = $this->_searchPlanilla->getArmarLapso($detallePlanilla);

				$mpdf->Cell(45, 5, 'MONTO IMPUESTO O TASA', 0, 0, 'C');
				$mpdf->Cell(30, 5, 'RECARGOS', 0, 0, 'C');
				$mpdf->Cell(30, 5, 'INTERES', 0, 0, 'C');
				$mpdf->Cell(40, 5, 'SUBTOTAL', 0, 1, 'C');

			} elseif ( $detallePlanilla[0]['trimestre'] == 0 ) {
				$mpdf->Cell(30, 5, 'IMPUESTO', 0, 0, 'C');
				$mpdf->Cell(24, 5, 'CODIGO', 0, 0, 'C');
				$mpdf->Cell(35, 5, 'MONTO IMPUESTO O TASA', 0, 0, 'C');
				$mpdf->Cell(28, 5, 'RECARGOS', 0, 0, 'C');
				$mpdf->Cell(28, 5, 'INTERES', 0, 0, 'C');
				$mpdf->Cell(40, 5, 'SUBTOTAL', 0, 1, 'C');

			}
			$mpdf->Rect(5, 50 + $y, 195, 5);


			$mpdf->SetX(5);
		 	$mpdf->SetFont('Arial', '', 7);
			// Datos del encabezado anterior.
			//$mpdf->Cell(-10);

			if ( $detallePlanilla[0]['trimestre'] > 0 ) {

				// Esto permitira saber cuanto periodos faltan para completar los doces.
				$espacioPeriodo = 30;

				foreach ( $lapsos as $key => $value ) {		// año => arreglo de periodos.
					$sumaMonto = 0;
					$sumaRecargo = 0;
					$sumaInteres = 0;
					$sumaDescuento = 0;
					$sumaReconocimiento = 0;
					$subTotal = 0;

					$espacioFaltante = 0;

					// Contabilizacion de la deuda por año.
					foreach ( $detallePlanilla as $detalle ) {
						if ( $key == $detalle['ano_impositivo'] ) {

							$sumaMonto = (float)$detalle['monto'] + $sumaMonto;
							$sumaRecargo = (float)$detalle['recargo'] + $sumaRecargo;
							$sumaInteres = (float)$detalle['interes'] + $sumaInteres;
							$sumaDescuento = (float)$detalle['descuento'] + $sumaDescuento;
							$sumaReconocimiento = (float)$detalle['monto_reconocimiento'] + $sumaReconocimiento;

						}
					}

					$espacioFaltante = $espacioPeriodo;

					// Totalizacion de impuesto + recargo + interes.
					$subTotal = (float)(($sumaMonto + $sumaRecargo + $sumaInteres));

					$mpdf->SetX(5);
					// Se coloca el año respectivo.
					$mpdf->Cell(10, 3, $key, 0, 0, 'C');

					// Lo siguiente muestra los periodo uno al lado de otro.
					foreach ( $value as $i => $periodo ) {
						if ( $periodo >= 10 ) {
							$espacio = 4;
							$espacioFaltante = $espacioFaltante - $espacio;

						} else {
							$espacio = 2;
							$espacioFaltante = $espacioFaltante - $espacio;

						}
						$mpdf->Cell($espacio, 3, $periodo, 0, 0, 'C');
					}
					if ( $espacioFaltante > 0 ) {
						$mpdf->Cell($espacioFaltante, 3, '', 0, 0, 'C');
					}

					$mpdf->Cell(60, 3, number_format($sumaMonto, 2), 0, 0, 'C');
					$mpdf->Cell(25, 3, number_format($sumaRecargo, 2), 0, 0, 'C');
					$mpdf->Cell(30, 3, number_format($sumaInteres, 2), 0, 0, 'C');
					$mpdf->Cell(40, 3, number_format($subTotal, 2), 0, 1, 'C');

				}


			} elseif ( $detallePlanilla[0]['trimestre'] == 0 ) {

				foreach ( $detallePlanilla as $detalle ) {
					$sumaMonto = (float)$detalle['monto'] + $sumaMonto;
					$sumaRecargo = (float)$detalle['recargo'] + $sumaRecargo;
					$sumaInteres = (float)$detalle['interes'] + $sumaInteres;
					$sumaDescuento = (float)$detalle['descuento'] + $sumaDescuento;
					$sumaReconocimiento = (float)$detalle['monto_reconocimiento'] + $sumaReconocimiento;
				}

				$subTotal = (float)(($sumaMonto + $sumaRecargo + $sumaInteres));

				$mpdf->Cell(10, 5, $detallePlanilla[0]['ano_impositivo'], 0, 0, 'C');
				$mpdf->Cell(30, 5, $detallePlanilla[0]['impuestos']['descripcion'], 0, 0, 'C');
				$mpdf->Cell(24, 5, $detallePresupuesto['codigo'], 0, 0, 'C');
				$mpdf->Cell(35, 5, number_format($sumaMonto, 2), 0, 0, 'C');
				$mpdf->Cell(28, 5, number_format($sumaRecargo, 2), 0, 0, 'C');
				$mpdf->Cell(28, 5, number_format($sumaInteres, 2), 0, 0, 'C');
				$mpdf->Cell(40, 5, number_format($subTotal, 2), 0, 1, 'C');

			}




		}







		/**
		 * Metodo que renderiza una vista con la siguiente informacion:
		 * Encabezado:
		 * - Rec/Ret.
		 * - Descuento.
		 * - Impuestos o Tasa.
		 * - Recargos.
		 * - Interes.
		 * - Total a pagar (Bs.F).
		 * Ademas de los datos de cada uno de los encabezados.
		 * Se realiza la contabilizacion total de la deuda de la planilla y por conceptos:
		 * - Retenciones y/o Reconocimiento.
		 * - Impuestos
		 * - Descuento
		 * - Recargo
		 * - Interes
		 * - Total
		 * Se coloca la observacion de la planilla que viene con ella.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  array $detallePlanilla arreglo con los detalles de la planilla de liquidacion.
		 * Posee entre otros datos, el impuesto, exigibilidad, etc.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewTercerDetalle($mpdf, $detallePlanilla, $y = 0)
		{
			$sumaMonto = 0;
			$sumaRecargo = 0;
			$sumaInteres = 0;
			$sumaDescuento = 0;
			$sumaReconocimiento = 0;

			// Tercer detalle de la planilla
			$mpdf->SetFont('Arial', 'B', 6);

			$mpdf->Cell(-10);
			$mpdf->SetFillColor(205, 205, 205);
			$mpdf->Cell(30, 5, 'RET/REC', 1, 0, 'C', true);
			$mpdf->Cell(30, 5, 'DESCUENTO', 1, 0, 'C', true);
			$mpdf->Cell(35, 5, 'IMPUESTO O TASA', 1, 0, 'C', true);
			$mpdf->Cell(30, 5, 'RECARGOS', 1, 0, 'C', true);
			$mpdf->Cell(30, 5, 'INTERES', 1, 0, 'C', true);
			$mpdf->Cell(40, 5, 'TOTAL A PAGAR(Bs.F.)', 1, 1, 'C', true);

			// Datos del encabezado anterio
			foreach ( $detallePlanilla as $detalle ) {
				$sumaMonto = (float)$detalle['monto'] + $sumaMonto;
				$sumaRecargo = (float)$detalle['recargo'] + $sumaRecargo;
				$sumaInteres = (float)$detalle['interes'] + $sumaInteres;
				$sumaDescuento = (float)$detalle['descuento'] + $sumaDescuento;
				$sumaReconocimiento = (float)$detalle['monto_reconocimiento'] + $sumaReconocimiento;
			}

			$total = (float)(($sumaMonto + $sumaRecargo + $sumaInteres) - ($sumaDescuento + $sumaReconocimiento));

			$mpdf->SetFont('Arial', '', 6);

			$mpdf->Cell(-10);
			$mpdf->Cell(30, 3, number_format($sumaReconocimiento, 2), 0, 0, 'C');
			$mpdf->Cell(30, 3, number_format($sumaDescuento, 2), 0, 0, 'C');
			$mpdf->Cell(35, 3, number_format($sumaMonto, 2), 0, 0, 'C');
			$mpdf->Cell(30, 3, number_format($sumaRecargo, 2), 0, 0, 'C');
			$mpdf->Cell(30, 3, number_format($sumaInteres, 2), 0, 0, 'C');
			$mpdf->SetFont('Arial', 'B', 9);
			$mpdf->Cell(40, 3, number_format($total, 2), 0, 0, 'C');

			$mpdf->SetX(5);
			$mpdf->Cell(195, 5, '', 1, 1, 'C');

			// Campo donde se muestra la observacion de la planilla.
			$mpdf->Cell(-10);
			$mpdf->SetFont('Arial', '', 6);
			//$detallePlanilla[0]['descripcion']
			// echo $detallePlanilla[0]['descripcion'];
			// die();
			$mpdf->MultiCell(195, 5, utf8_decode((utf8_encode($detallePlanilla[0]['descripcion']))), 0, 'J');

			$mpdf->Cell(-10);
			//$mpdf->Rect(5, 65 + $y, 195, 5);


		}





		/**
		 * Metodo que renderiza la siguiente informacion:
		 * - Etiqueta con la descripcion:"VALIDACION TERMINAL: CAJA".
		 * - Recuadro donde se colocara la rafag validador del banco.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewRafaga($mpdf, $y = 0)
		{
			// Recuadro para imprimir la rafaga bancaria.
			// Validacion Terminal Caja.
	       	$mpdf->RoundedRect(5, 100 + $y, 110, 25, 3, D);

	       	// Validacion terminal caja
			$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(10, 103 + $y, 'VALIDACION TERMINAL: CAJA');
		}





		/**
		 * Metodoq ue renderiza una vista con la siguiente informacion:
		 * - Cuenta recaudadora.
		 * - Informacion del acceso a la pagina web.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewInfoCuentaRecaudadoraPaginaWeb($mpdf, $y = 0)
		{
			// Informacion de la Cuenta Recaudadora
	       	$cuentaRecaudadora = '';//Yii::$app->ente->getCuentaRecaudadoraPrincipal(0);
	       	$mpdf->SetFont('Arial', 'I', 8);
	       	//$mpdf->Text(125, 102 + $y, 'Nro: Cuenta Recaudadora: ' . $cuentaRecaudadora);

	       	// Informacion de acceso web
	       	$accesoWeb = Yii::$app->ente->getPortalWeb();
	       	$mpdf->SetFont('Arial', 'I', 7);
	       	$mpdf->Text(138, 107 + $y, 'Ahora puede acceder desde el portal');

	       	$mpdf->SetFont('Arial', 'I', 7);
	       	$mpdf->Text(137, 110 + $y, $accesoWeb);

		}



		/**
		 * Metodo que renderiza una vista con la siguiente informacion:
		 * - Etiqueta con la descripcion: "CODIGO VERIFICADOR BANCARIO".
		 * - Recuadro donde se coloca la etiqueta y donde aparece el Codigo Validador Bancario.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return  pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewCodigoValidador($mpdf, $y = 0)
		{
			// Informacion del Codigo validador Bamcario
	       	$mpdf->SetFillColor(225, 225, 225);
	       	$mpdf->RoundedRect(117, 115 + $y, 45, 8, 2, DF);

	       	$mpdf->SetFont('Arial', 'B', 7);
	       	$mpdf->Text(119, 120 + $y, 'CODIGO VERIFICADOR BANCARIO');


	       	// Donde se coloca el CVB
	       	$mpdf->RoundedRect(162, 115 + $y, 30, 8, 2, D);

	       	$mpdf->SetFont('Arial', 'B', 10);
	       	$mpdf->Text(172, 120 + $y, self::getCodigoValidadorBancarioPlanilla());

		}




		/**
		 * Metodo que renderiza la siguiente informacion:
		 * - Datos del usuario.
		 * - Linea punteada inferior.
		 * - Etiqueta que indica el tipo de copia del documento.
		 * @param MPDF $mpdf instancia de la clase MPDF.
		 * @param  string  $captionCopia etiqueta.
		 * @param  integer $y entero que permite variar la posicion vetrical de la opcion.
		 * @return  pdf retorna pdf de la vista de la planilla con la informacion indicada.
		 */
		private function actionGetViewInfoRestante($mpdf, $captionCopia = 'ORIGINAL: CONTRIBUYENTE', $y = 0)
		{
			// Linea punteado inferior
	       	$mpdf->SetDash(1, 1);
	       	$mpdf->Line(5, 132 + $y, 200, 132 + $y);


	       	// Informacion del Operador
	       	$user = Yii::$app->identidad->getUsuario();
	       	$mpdf->SetFont('Arial', '', 6);
	       	$mpdf->Text(8, 130 + $y, 'Operador    ' . $user);


	       	// Informacion de tipo de copia
	       	$mpdf->SetFont('Arial', 'B', 6);
	       	$mpdf->Text(70, 130 + $y, $captionCopia);


	       	// Informacion del momento de la descarga.
	       	$mpdf->SetFont('Arial', '', 6);
	       	$mpdf->Text(116, 127 + $y, date('Y-m-d H:i:s'));

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