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
 *	@file SolicitudViewnActividadEconomicaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 19-09-2015
 *
 *  @class SolicitudViewnActividadEconomicaController
 *	@brief Clase
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


 	namespace backend\controllers\aaee\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursalSearch;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscalSearch;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRifSearch;
	use backend\models\aaee\correccioncapital\CorreccionCapitalSearch;
	use backend\models\aaee\correccionreplegal\CorreccionRepresentanteLegalSearch;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocialSearch;
	use backend\models\aaee\autorizarramo\AutorizarRamoSearch;
	use backend\models\aaee\correccionfechainicio\CorreccionFechaInicioSearch;
	use backend\models\aaee\anexoramo\AnexoRamoSearch;
	use backend\models\aaee\desincorporaramo\DesincorporarRamoSearch;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseSearch;
	use backend\models\aaee\licencia\LicenciaSolicitudSearch;
	use backend\models\aaee\solvencia\SolvenciaActividadEconomicaSearch;
	use backend\controllers\MenuController;

	//session_start();		// Iniciando session


	/**
	 * Clase que permite renderizar las visatas detalles de las solicitudes por tipo de solicitud.
	 * Las solicitudes aqui representan las del impuesto de Actividades Economicas. El resultado es
	 * una vista con la informacion del detalle de la solicitud. El constructor de la clase recibe
	 * el modelo que posee entre sus datos el tipo de solicitud o id-tipo-solicitud.
	 */
	class SolicitudViewActividadEconomicaController extends Controller
	{

		private $_model;



		/**
		 * Constructor de la clase.
		 * @param model $mod modelo de la solicitud creada.
		 */
		public function __construct($mod)
		{
			$this->_model = $mod;
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
			//&& isset($_SESSION['idContribuyente'])
			if ( isset($this->_model) ) {

				if ( $this->_model->tipo_solicitud == 1 ) {

					return self::actionMostarSolicitudInscripcionActividadEconomica();

				} elseif ( $this->_model->tipo_solicitud == 2 ) {

					return self::actionMostarSolicitudInscripcionSucursal();

				} elseif ( $this->_model->tipo_solicitud == 3 ) {

					return self::actionMostarSolicitudLicencia();

				} elseif ( $this->_model->tipo_solicitud == 7 ) {

					return self::actionMostarSolicitudSolvenciaActividadEconomica();

				} elseif ( $this->_model->tipo_solicitud == 8 ) {

					return self::actionMostarSolicitudDeclaracionDefinitiva();

				} elseif ( $this->_model->tipo_solicitud == 9 ) {

					return self::actionMostarSolicitudAnexarRamo();

				} elseif ( $this->_model->tipo_solicitud == 10 ) {

					return self::actionMostarSolicitudDesincorporarRamo();

				} elseif ( $this->_model->tipo_solicitud == 12 ) {

					return self::actionMostarSolicitudCorreccionRazonSocial();

				} elseif ( $this->_model->tipo_solicitud == 13 ) {

					return self::actionMostarSolicitudCorreccionDomicilioFiscal();

				} elseif ( $this->_model->tipo_solicitud == 14 ) {

					return self::actionMostarSolicitudCorreccionRepresentanteLegal();

				} elseif ( $this->_model->tipo_solicitud == 15 ) {

					return self::actionMostarSolicitudCorreccionCapital();

				} elseif ( $this->_model->tipo_solicitud == 17 ) {

					return self::actionMostarSolicitudDeclaracionSustitutiva();

				} elseif ( $this->_model->tipo_solicitud == 69 ) {

					return self::actionMostarSolicitudCorreccionCedulaRif();

				} elseif ( $this->_model->tipo_solicitud == 70 ) {

					return self::actionMostarSolicitudAutorizarRamo();

				} elseif ( $this->_model->tipo_solicitud == 71 ) {

					return self::actionMostarSolicitudCorreccionFechaInicio();

				} elseif ( $this->_model->tipo_solicitud == 73 ) {

					return self::actionMostarSolicitudDeclaracionEstimada();

				} elseif ( $this->_model->tipo_solicitud == 'd' ) {

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
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudInscripcionActividadEconomica()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New InscripcionActividadEconomicaSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/inscripcion-actividad-economica/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Inscripcion de Sucursal", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudInscripcionSucursal()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New InscripcionSucursalSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/inscripcion-sucursal/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Domicilio Fiscal", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionDomicilioFiscal()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionDomicilioFiscalSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionDomicilio($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-domicilio-fiscal/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,

							]);
					}
			}

			return false;
		}





		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Cedula/Rif", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionCedulaRif()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionCedulaRifSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionCedulaRif($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-cedula-rif/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Capital", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionCapital()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionCapitalSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionCapital($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-capital/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Representante Legal", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionRepresentanteLegal()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionRepresentanteLegalSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionRepresentanteLegal($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-representante-legal/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Razon Social", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionRazonSocial()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionRazonSocialSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionRazonSocial($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-razon-social/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Autorizar Ramo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudAutorizarRamo()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New AutorizarRamoSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudAutorizarRamo($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/autorizar-ramo/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Correccion de Fecha de Inicio de Actividad", y de renderizar una vista del detalle
		 * de la solicitud.
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudCorreccionFechaInicio()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New CorreccionFechaInicioSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudCorreccionFechaInicio($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/correccion-fecha-inicio/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Anexar Ramo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudAnexarRamo()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New AnexoRamoSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudAnexoRamo($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/anexo-ramo/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Desincorporar Ramo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudDesincorporarRamo()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
					$modelSearch = New DesincorporarRamoSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudDesincorporarRamo($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/desincorpora-ramo/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Declaracion Estimada", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudDeclaracionEstimada()
		{
			if ( $this->_model->nivel_aprobacion == 2 || $this->_model->nivel_aprobacion == 1 ) {
					$modelSearch = New DeclaracionBaseSearch($this->_model->id_contribuyente);
					$model = $modelSearch->findSolicitudDeclaracion($this->_model->nro_solicitud);
					$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
					if ( isset($model) ) {
						return $this->render('@backend/views/aaee/declaracion/estimada/view-solicitud', [
														'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
														'model' => $model,
														'dataProvider' => $dataProvider,

							]);
					}
			}

			return false;
		}




		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Declaracion Definitiva", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudDeclaracionDefinitiva()
		{
			if ( $this->_model->nivel_aprobacion == 2 || $this->_model->nivel_aprobacion == 1 ) {
				$modelSearch = New DeclaracionBaseSearch($this->_model->id_contribuyente);
				$model = $modelSearch->findSolicitudDeclaracion($this->_model->nro_solicitud);
				$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);

				if ( isset($model) ) {
					return $this->render('@backend/views/aaee/declaracion/definitiva/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
													'model' => $model,
													'dataProvider' => $dataProvider,

						]);
				}
			}

			return false;
		}





		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Declaracion Sustitutiva", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudDeclaracionSustitutiva()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
				$modelSearch = New SustitutivaBaseSearch($this->_model->id_contribuyente);
				$model = $modelSearch->findSolicitudSustitutiva($this->_model->nro_solicitud);
				$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
				if ( isset($model) ) {
					return $this->render('@backend/views/aaee/declaracion/sustitutiva/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
													'model' => $model,
													'dataProvider' => $dataProvider,

						]);
				}
			}

			return false;
		}





		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Licencias", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudLicencia()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
				$modelSearch = New LicenciaSolicitudSearch($this->_model->id_contribuyente);
				$model = $modelSearch->findSolicitudLicencia($this->_model->nro_solicitud);
				$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
				$model = $model->all();

				if ( isset($model) ) {
					return $this->render('@backend/views/aaee/licencia/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
													'model' => $model,
													'dataProvider' => $dataProvider,

						]);
				}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Solvencias de ActividadEconomica", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprovacion", este determinara un nivel mas
		 * determinante de la vista.
		 * El nivel de aprobacion 3 renderizara un formulario con los datos originales de la solicitud
		 * inhabilitados y solo permitira la edicion de los campos que no fueron cargados en dicha
		 * solicitud, esto con la intencion de que el funcionario pueda complementar dicha informacion.
		 * @return view retorna un vista con la informacion de la solicitud sino encuentra dicha
		 * informacion retornara false.
		 * ---
		 * nivel de aprobacion 1: No aplica.
		 * nivel de aprobacion 2: la vista no permite la edicion de los campos.
		 * 	- Esquema de esta vista:
		 *  	* Nombre del campo : Valor del campo
		 * nivel de aprobacion 3: Muestra inhabilitado los datos suministrados previamente y habilita
		 * aquellos campos que no fueron cargados inicialmente.
		 */
		private function actionMostarSolicitudSolvenciaActividadEconomica()
		{
			if ( $this->_model->nivel_aprobacion == 2 ) {
				$modelSearch = New SolvenciaActividadEconomicaSearch($this->_model->id_contribuyente);
				$model = $modelSearch->findSolicitudSolvencia($this->_model->nro_solicitud);
				$dataProvider = $modelSearch->getDataProviderSolicitud($this->_model->nro_solicitud);
				$model = $model->all();

				if ( isset($model) ) {
					$ultimoPago = $modelSearch->getDescripcionUltimoPago();
					$lapso = explode('-', $ultimoPago);
					$solvente = 'NO SOLVENTE';
					if ( $modelSearch->getEstaSolvente() ) {
						$solvente = 'SOLVENTE';
					}
					$tipoSolicitud = $modelSearch->getDescripcionTipoSolicitud($model[0]->nro_solicitud);
					return $this->render('@backend/views/aaee/solvencia/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->_model->nro_solicitud),
													'model' => $model,
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