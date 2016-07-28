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
 *	@file SolicitudViewVehiculoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 19-09-2015
 *
 *  @class SolicitudViewVehiculoController
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


 	namespace backend\controllers\vehiculo\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use frontend\models\vehiculo\solicitudes\SlVehiculos;
	use frontend\models\vehiculo\solicitudes\SlVehiculosForm;
	// use common\conexion\ConexionController;
	use backend\controllers\MenuController;
	use backend\models\vehiculo\VehiculoSearch;
	use backend\models\vehiculo\VehiculosForm;
	use yii\db\Query;

	//session_start();		// Iniciando session


	/**
	 * Clase que permite renderizar las visatas detalles de las solicitudes por tipo de solicitud.
	 * Las solicitudes aqui representan las del impuesto de Actividades Economicas. El resultado es
	 * una vista con la informacion del detalle de la solicitud. El constructor de la clase recibe
	 * el modelo que posee entre sus datos el tipo de solicitud o id-tipo-solicitud.
	 */
	class SolicitudViewVehiculoController extends Controller
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
			//die('llego');
			if ( isset($this->model) && isset($_SESSION['idContribuyente']) ) {

				if ( $this->model->tipo_solicitud == 32 ) {

					return self::actionMostarSolicitudInscripcionVehiculo();

				} elseif ( $this->model->tipo_solicitud == 35 ) {

					return self::actionMostarSolicitudCambioPropietarioVendedorVehiculo();

				} elseif ( $this->model->tipo_solicitud == 66 ) {

					return self::actionMostarSolicitudCambioPropietarioCompradorVehiculo();

				} elseif ( $this->model->tipo_solicitud == 36 ) {

					return self::actionMostarSolicitudCambioPlacaVehiculo();

				} elseif ( $this->model->tipo_solicitud == 37 ) {

					return self::actionDesincorporacionVehiculo();

				} elseif ( $this->model->tipo_solicitud == 38 ) {

					return self::actionActualizarDatosVehiculo();

				} elseif ( $this->model->tipo_solicitud == 68 ) {

					return self::actionSolicitudReposicionCalcomaniaExtravio();

				} elseif ( $this->model->tipo_solicitud == 10 ) {

				} elseif ( $this->model->tipo_solicitud == 12 ) {

				}
			}

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Inscripcion de Vehiculos", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionMostarSolicitudInscripcionVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/inscripcion/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}


			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Cambio de propietarios del Vehiculo como vendedor", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionMostarSolicitudCambioPropietarioVendedorVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudCambioPropietarioVendedor($this->model->nro_solicitud);
					$modelRelacion = self::busquedaRelacionVehiculoSlCambioPropietario($model->id_impuesto, $model->id_comprador);
					//die(var_dump($modelRelacion));
					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/cambiopropietario/view-solicitud-cambio-propietario-vendedor', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $modelRelacion,
													'search' => $search,
													//'modelRelacion' => ,

						]);
			}

			return false;
		}

			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Cambio de propietarios del Vehiculo como comprador", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionMostarSolicitudCambioPropietarioCompradorVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudCambioPropietarioComprador($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/cambiopropietario/view-solicitud-cambio-propietario-comprador', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}

			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Cambio de placa de Vehiculo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionMostarSolicitudCambioPlacaVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudCambioPlaca($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/cambioplaca/view-solicitud-cambio-placa', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}

			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Desincorporacion de Vehiculo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionDesincorporacionVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudDesincorporacionVehiculo($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/desincorporacion/view-solicitud-desincorporacion-vehiculo', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}

			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Actualizar Datos del Vehiculo", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionActualizarDatosVehiculo()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudActualizarDatosVehiculo($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/actualizardatos/view-solicitud-actualizar-datos-vehiculo', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}

			/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Cambiar Calcomania por Daño o Extravio", y de renderizar una vista del detalle de la solicitud
		 * Se utiliza un parametro adicional "nivel de aprobacion", este determinara un nivel mas
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
		private function actionSolicitudReposicionCalcomaniaExtravio()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlVehiculosForm($this->model->id_contribuyente);
					$model = $modelSearch->findSolicitudReposicionCalcomaniaExtravio($this->model->nro_solicitud);

					$search = new VehiculoSearch();


					return $this->render('/vehiculo/solicitudes/reposicioncalcomania/view-solicitud-reposicion-calcomania-extravio', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'search' => $search,

						]);
			}

			return false;
		}


		public function busquedaRelacionVehiculoSlCambioPropietario($idImpuesto, $idComprador)
		{
			$query = New Query();

			//$row = $query->select('*')->from('ordenanzas')->all();

			// Select ordenanzas_detalles.* from ordenanzas
			// INNER JOIN ordenazas_detalles on ordenanzas.id_ordenanza=ordenanzas_detalles.id_ordenanza

			$model = $query->select('*')
						 ->from('vehiculos')
					     ->join('INNER JOIN', 'sl_cambios_propietarios', 'vehiculos.id_vehiculo = sl_cambios_propietarios.id_impuesto')
					     ->where(['id_vehiculo' => $idImpuesto])
					     ->join('INNER JOIN', 'contribuyentes', 'contribuyentes.id_contribuyente = sl_cambios_propietarios.id_comprador')
					     ->where(['id_contribuyente' => $idComprador])
					     
					     ->all();

			return $model;
										
		}


	}
?>