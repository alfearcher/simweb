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
 *	@file SolicitudViewnInmuebleController.php
 *
 *	@author Alvaro Jose Fernandez Archer
 *
 *	@date 25-05-2016
 *
 *  @class SolicitudViewnInmuebleController
 *	@brief Clase
 *
 *
 *	@property
 *
 *
 *	@method
 * 	actionInicioView
 * 	MostrarSolicitudInscripcionInmueble
 *
 *	@inherits
 *
 */


 	namespace backend\controllers\inmueble\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\inmueble\SlInmueblesUrbanosForm;
	use backend\models\inmueble\SlInmueblesUrbanosSearch;
	use backend\models\inmueble\SlHistoricoAvaluosForm;
	use backend\models\inmueble\SlHistoricoAvaluosSearch;
	use backend\models\inmueble\SlCambioPropietarioForm;
	use backend\models\inmueble\SlCambioPropietarioSearch;
	use backend\models\inmueble\SlInmueblesRegistrosSearch;
	use backend\models\inmueble\SlInmueblesRegistrosForm;
	use backend\models\inmueble\SlCertificadoCatastralSearch;

	use backend\models\inmueble\solvencia\SolvenciaInmuebleSearch;
	// use common\conexion\ConexionController;
	use backend\controllers\MenuController;

	//session_start();		// Iniciando session


	/**
	 * Clase que permite renderizar las vistas detalles de las solicitudes por tipo de solicitud.
	 * Las solicitudes aqui representan las del impuesto de Actividades Economicas. El resultado es
	 * una vista con la informacion del detalle de la solicitud. El constructor de la clase recibe
	 * el modelo que posee entre sus datos el tipo de solicitud o id-tipo-solicitud.
	 */
	class SolicitudViewInmuebleController extends Controller
	{

		private $model;



		/**
		 * Constructor de la clase.
		 * @param model $mod modelo de la solicitud creada.
		 */
		public function __construct($mod)
		{
			$this->model = $mod;
		}


		/**
		 * Metodo que sirve de indice de renderizacion para la vista detalle particular.
		 * Aqui segun el parametro tipo de solicitud se renderiza hacia un metodo que se
		 * encargara de buscar los detalles de la solicitud y generar una vista.
		 * @return View Retorna una vista con la informacion del detalle de la solicitud o
		 * en caso de no encontrar nada false.
		 */
		public function actionInicioView()
		{
			if ( isset($this->model) && isset($_SESSION['idContribuyente']) ) {
// id de la configuracion tipo..
				if ( $this->model->tipo_solicitud == 20 ) {

					return self::actionMostrarSolicitudInscripcionInmueble();

				} elseif ( $this->model->tipo_solicitud == 21 ) {

					return self::actionMostrarSolicitudAvaluoInmueble();

				} elseif ( $this->model->tipo_solicitud == 22 ) {

					return self::actionMostrarSolicitudCertificadoCatastralInmueble();

				} elseif ( $this->model->tipo_solicitud == 23 ) {

					return self::actionMostrarSolicitudRenovacionCertificadoCatastralInmueble();

				} elseif ( $this->model->tipo_solicitud == 24 ) {

					return self::actionMostrarSolicitudSolvenciaInmueble();

				} elseif ( $this->model->tipo_solicitud == 25 ) {

					return self::actionMostrarSolicitudCambioNumeroCatastralInmueble();

				} elseif ( $this->model->tipo_solicitud == 26 ) {

					return self::actionMostrarSolicitudCambioPropietarioVendedorInmueble();

				} elseif ( $this->model->tipo_solicitud == 27 ) {

					return self::actionMostrarSolicitudIntegracionInmueble();

				} elseif ( $this->model->tipo_solicitud == 28 ) {

					return self::actionMostrarSolicitudDesintegracionInmueble();

				} elseif ( $this->model->tipo_solicitud == 29 ) {

					return self::actionMostrarSolicitudCambioPropiedadHorizontalInmueble();

				} elseif ( $this->model->tipo_solicitud == 30 ) {

					return self::actionMostrarSolicitudActualizacionDatosInmueble();

				} elseif ( $this->model->tipo_solicitud == 31 ) {

					return self::actionMostrarSolicitudModificarAvaluoInmueble();

				} elseif ( $this->model->tipo_solicitud == 65 ) {

					return self::actionMostrarSolicitudDesincorporacionInmueble();

				} elseif ( $this->model->tipo_solicitud == 67 ) {

					return self::actionMostrarSolicitudCambioPropietarioCompradorInmueble();

				} elseif ( $this->model->tipo_solicitud == 82 ) {

					return self::actionMostrarSolicitudSolvenciaInmuebleCreada();

				} elseif ( $this->model->tipo_solicitud == 84 ) {

					return self::actionMostrarSolicitudLinderosInmueble();

				} elseif ( $this->model->tipo_solicitud == 85 ) {

					return self::actionMostrarSolicitudRegistrosInmueble();

				}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Inscripcion de Actividades Economicas", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return View Retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostrarSolicitudInscripcionInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/inscripcion-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 21
		private function actionMostrarSolicitudAvaluoInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlHistoricoAvaluosSearch();
					$model = $modelSearch->findAvaluos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/avaluo-catastral-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 22
		private function actionMostrarSolicitudCertificadoCatastralInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/certificado-catastral-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 23
		private function actionMostrarSolicitudRenovacionCertificadoCatastralInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlCertificadoCatastralSearch($this->model->id_contribuyente);
					$model = $modelSearch->findCertificado($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/certificado-catastral-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,


						]);
			}


			return false;
		}

		// tipo solicitud 24
		private function actionMostrarSolicitudSolvenciaInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/solvencia-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 25
		private function actionMostrarSolicitudCambioNumeroCatastralInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/cambio-numero-catastral-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 26
		private function actionMostrarSolicitudCambioPropietarioVendedorInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					//$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					//$model = $modelSearch->findVendedor($this->model->nro_solicitud);

					$modelSearch = New SlCambioPropietarioSearch($this->model->id_contribuyente);
					$model = $modelSearch->findVendedor($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/cambio-propietario-vendedor-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 27
		private function actionMostrarSolicitudIntegracionInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findIntegracion($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/integracion-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 28
		private function actionMostrarSolicitudDesintegracionInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findDesintegracion($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/desintegracion-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 29
		private function actionMostrarSolicitudCambioPropiedadHorizontalInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/cambio-a-propiedad-horizontal-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 30
		private function actionMostrarSolicitudActualizacionDatosInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/cambio-otros-datos-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 31
		private function actionMostrarSolicitudModificarAvaluoInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/modificar-avaluo-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 65
		private function actionMostrarSolicitudDesincorporacionInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					$model = $modelSearch->findDesincorporacion($this->model->nro_solicitud);

					//die(var_dump($model));

					return $this->render('@backend/views/inmueble/desincorporacion-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 67
		private function actionMostrarSolicitudCambioPropietarioCompradorInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					// $modelSearch = New SlInmueblesUrbanosSearch($this->model->id_contribuyente);
					// $model = $modelSearch->findActualizacionDatos($this->model->nro_solicitud);

					$modelSearch = New SlCambioPropietarioSearch($this->model->id_contribuyente);
					$model = $modelSearch->findVendedor($this->model->nro_solicitud);

					return $this->render('@backend/views/inmueble/cambio-propietario-comprador-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 84
		private function actionMostrarSolicitudLinderosInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlHistoricoAvaluosSearch();
					$model = $modelSearch->findAvaluos($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/linderos-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}

		// tipo solicitud 85
		private function actionMostrarSolicitudRegistrosInmueble()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
					$modelSearch = New SlInmueblesRegistrosSearch();
					$model = $modelSearch->findInmueblesRegistros($this->model->nro_solicitud);



					return $this->render('@backend/views/inmueble/registros-inmuebles-urbanos/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}





		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Solvencia de Inmuebles", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return View Retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostrarSolicitudSolvenciaInmuebleCreada()
		{
			if ( $this->model->nivel_aprobacion == 2 || $this->model->nivel_aprobacion == 1 ) {
				$modelSearch = New SolvenciaInmuebleSearch($this->model->id_contribuyente, $this->model->id_impuesto);
				$modelSolvencia = $modelSearch->findSolicitudSolvencia($this->model->nro_solicitud);

				$dataProvider = $modelSearch->getDataProviderSolicitud($this->model->nro_solicitud);
				$modelSolvencia = $modelSolvencia->joinWith('estatusSolicitud E', true)
												 ->joinWith('inmueble V1', true, 'INNER JOIN');

				$tipoSolicitud = $modelSearch->getDescripcionTipoSolicitud($this->model->nro_solicitud);

				$modelSolvencia = $modelSolvencia->all();
				if ( isset($modelSolvencia) ) {
					$ultimoPago = $modelSearch->getDescripcionUltimoPago();

					$lapso = explode('-', $ultimoPago);
					$solvente = 'NO SOLVENTE';
					if ( $modelSearch->getEstaSolvente() ) {
						$solvente = 'SOLVENTE';
					}

					return $this->render('@backend/views/inmueble/solvencia/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $modelSolvencia,
													'tipoSolicitud' => $tipoSolicitud,
													'lapso' => $lapso,
													'solvente' => $solvente,

						]);
				}
			}

			return false;
		}


	}
?>