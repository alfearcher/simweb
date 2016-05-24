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
	// use common\conexion\ConexionController;
	use backend\controllers\MenuController;

	//session_start();		// Iniciando session


	/**
	 * Clase que permite renderizar las visatas detalles de las solicitudes por tipo de solicitud.
	 * Las solicitudes aqui representan las del impuesto de Actividades Economicas. El resultado es
	 * una vista con la informacion del detalle de la solicitud. El constructor de la clase recibe
	 * el modelo que posee entre sus datos el tipo de solicitud o id-tipo-soliictud.
	 */
	class SolicitudViewActividadEconomicaController extends Controller
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

				if ( $this->model->tipo_solicitud == 1 ) {

					return self::actionMostarSolicitudInscripcionActividadEconomica();

				} elseif ( $this->model->impuesto == 2 ) {

				} elseif ( $this->model->impuesto == 3 ) {

				} elseif ( $this->model->impuesto == 4 ) {

				} elseif ( $this->model->impuesto == 6 ) {

				} elseif ( $this->model->impuesto == 7 ) {

				} elseif ( $this->model->impuesto == 8 ) {

				} elseif ( $this->model->impuesto == 10 ) {

				} elseif ( $this->model->impuesto == 12 ) {

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
		 */
		private function actionMostarSolicitudInscripcionActividadEconomica()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New InscripcionActividadEconomicaSearch($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->model->nro_solicitud);

					return $this->renderAjax('/aaee/inscripcion-actividad-economica/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
			}

			return false;
		}







	}
?>