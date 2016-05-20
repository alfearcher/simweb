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
	 *
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
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionInicioView()
		{
			if ( isset($this->model) ) {
				if ( $this->model->nivel_aprobacion == 2 ) {
					if ( $this->model->impuesto == 1 ) {

						return self::actionMostarSolicitudActividadEconomica();

					} elseif ( $this->model->impuesto == 2 ) {

					} elseif ( $this->model->impuesto == 3 ) {

					} elseif ( $this->model->impuesto == 4 ) {

					} elseif ( $this->model->impuesto == 6 ) {

					} elseif ( $this->model->impuesto == 7 ) {

					} elseif ( $this->model->impuesto == 8 ) {

					} elseif ( $this->model->impuesto == 10 ) {

					} elseif ( $this->model->impuesto == 12 ) {

					} else {
						return false;
					}
				} elseif ( $this->model->nivel_aprobacion == 3 ) {

				}
			}

			return false;
		}



		/***/
		public function actionMostarSolicitudActividadEconomica()
		{
			if ( $this->model->impuesto == 1 ) {
				if ( $this->model->tipo_solicitud == 1 ) {
					$modelSearch = New InscripcionActividadEconomicaSearch($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcion($this->model->nro_solicitud);

					return $this->renderAjax('/aaee/inscripcion-actividad-economica/view-solicitud', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,

						]);
				} elseif ( $this->model->tipo_solicitud == 2 ) {

				}
			}

			return false;
		}







	}
?>