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
 *	@file SolicitudViewPropagandaController.php
 *
 *	@author Manuel Alejandro Zapata Canelon
 *
 *	@date 05-07-2016
 *
 *  @class SolicitudViewPropagandaController
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


 	namespace backend\controllers\propaganda\solicitud;


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
	use frontend\models\propaganda\solicitudes\SlPropagandasForm;
	use yii\db\Query;
	use common\models\propaganda\patrocinador\SlAnulacionesPatrocinadores;
	//session_start();		// Iniciando session


	/**
	 * Clase que permite renderizar las visatas detalles de las solicitudes por tipo de solicitud.
	 * Las solicitudes aqui representan las del impuesto de Actividades Economicas. El resultado es
	 * una vista con la informacion del detalle de la solicitud. El constructor de la clase recibe
	 * el modelo que posee entre sus datos el tipo de solicitud o id-tipo-solicitud.
	 */
	class SolicitudViewPropagandaController extends Controller
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

				if ( $this->model->tipo_solicitud == 39 ) {

					return self::actionMostarSolicitudInscripcionPropaganda();

				} elseif ( $this->model->tipo_solicitud == 41 ) {

					return self::actionMostarSolicitudCambioDatosPropaganda();

				} elseif ( $this->model->tipo_solicitud == 42 ) {

					return self::actionMostarSolicitudDesincorporacionPropaganda();
				
				} elseif ($this->model->tipo_solicitud == 72){
					
					return self::actionMostarSolicitudAsignacionPatrocinadorPropaganda();
				
				} elseif ($this->model->tipo_solicitud == 74){

					return self::actionMostrarSolicitudAnulacionPatrocinadorPropaganda();
				}



			 }
		 	

			return false;
		}



		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Inscripcion de Propagandas", y de renderizar una vista del detalle de la solicitud
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
		private function actionMostarSolicitudInscripcionPropaganda()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlPropagandasForm($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcionPropaganda($this->model->nro_solicitud);

	//die(var_dump($model));


					return $this->render('/propaganda/solicitudes/inscripcion/view-solicitud-inscripcion-propaganda', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													

						]);
			}

			return false;
		}


		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Renovacion de datos de la Propagandas", y de renderizar una vista del detalle de la solicitud
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
		private function actionMostarSolicitudCambioDatosPropaganda()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlPropagandasForm($this->model->id_contribuyente);
					$model = $modelSearch->findInscripcionPropaganda($this->model->nro_solicitud);
					//die(var_dump($model));
					$modelPropaganda = $modelSearch->findCambioDatosPropagandaMaestro($this->model->id_impuesto);
					//die(var_dump($modelPropaganda));

	//die(var_dump($model));


					return $this->render('/propaganda/solicitudes/cambiodatos/view-solicitud-cambio-datos-propaganda', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
													'modelPropaganda' => $modelPropaganda,

						]);
			}

			return false;
		}


		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Renovacion de datos de la Propagandas", y de renderizar una vista del detalle de la solicitud
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
		private function actionMostarSolicitudDesincorporacionPropaganda()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlPropagandasForm($this->model->id_contribuyente);
					$model = $modelSearch->findDesincorporacionPropaganda($this->model->nro_solicitud);
			


					return $this->render('/propaganda/solicitudes/desincorporacion/view-solicitud-desincorporacion-propaganda', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $model,
												

						]);
			}

			return false;
		}

		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Asignacion de patrocinadores", y de renderizar una vista del detalle de la solicitud
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
		private function actionMostarSolicitudAsignacionPatrocinadorPropaganda()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlPropagandasForm($this->model->id_contribuyente);
					$model = $modelSearch->findPatrocinadorPropaganda($this->model->nro_solicitud);
					$idPatrocinador = self::busquedaIdPatrocinador($this->model->nro_solicitud);
					//die(var_dump($idPatrocinador));
					$modelRelacion = self::busquedaRelacionSlPropagandaPatrocinador($this->model->id_impuesto, $idPatrocinador);
					//die(var_dump($modelRelacion));
					//die(var_dump($modelRelacion));
					return $this->render('/propaganda/solicitudes/patrocinador/view-solicitud-asignar-patrocinador-propaganda', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $modelRelacion,
					]);
			}

			return false;
		}


		/**
		 * Metodo particular que se encarga de buscar los datos de la solicitud particular sobre
		 * "Anulacion de patrocinadores", y de renderizar una vista del detalle de la solicitud
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
		private function actionMostrarSolicitudAnulacionPatrocinadorPropaganda()
		{
			if ( $this->model->nivel_aprobacion == 2 ) {
					$modelSearch = New SlPropagandasForm($this->model->id_contribuyente);
					$model = $modelSearch->findAnularPatrocinadorPropaganda($this->model->nro_solicitud);
					$idPatrocinador = self::busquedaIdPatrocinadorAnulaciones($this->model->nro_solicitud);
					//die(var_dump($idPatrocinador));
					$modelRelacion = self::busquedaRelacionSlAnularPatrocinador($this->model->id_impuesto, $idPatrocinador);
					//die(var_dump($modelRelacion));
					//die(var_dump($modelRelacion));
					return $this->render('/propaganda/solicitudes/patrocinador/view-solicitud-anular-patrocinador-propaganda', [
													'caption' => Yii::t('frontend', 'Request Nro. ' . $this->model->nro_solicitud),
													'model' => $modelRelacion,
					]);
			}

			return false;
		}

		public function busquedaRelacionSlPropagandaPatrocinador($idPropaganda, $idPatrocinador)
		{
			//die(var_dump($idPropaganda).'y'. $idPatrocinador. 'y'. $NroSolicitud);


				$query = New Query();

		

			$model = $query->select('*')
						 ->from('propagandas')
					     ->Join('INNER JOIN', 'sl_propagandas_patrocinadores', 'sl_propagandas_patrocinadores.id_impuesto =  propagandas.id_impuesto')
					     ->Join('INNER JOIN', 'contribuyentes', 'contribuyentes.id_contribuyente = sl_propagandas_patrocinadores.id_patrocinador')
					     
					     ->where(['sl_propagandas_patrocinadores.id_impuesto' => $idPropaganda])
					     ->andWhere(['contribuyentes.id_contribuyente' => $idPatrocinador])
					    
					     
					     ->all();

			return $model;
		}

		public function busquedaRelacionSlAnularPatrocinador($idPropaganda, $idPatrocinador)
		{
			//die(var_dump($idPropaganda).'y'. $idPatrocinador);


				$query = New Query();

		

			$model = $query->select('*')
						 ->from('propagandas')
					     ->Join('INNER JOIN', 'sl_anulaciones_patrocinadores', 'sl_anulaciones_patrocinadores.id_impuesto =  propagandas.id_impuesto')
					     ->Join('INNER JOIN', 'contribuyentes', 'contribuyentes.id_contribuyente = sl_anulaciones_patrocinadores.id_patrocinador')
					     
					     ->where(['sl_anulaciones_patrocinadores.id_impuesto' => $idPropaganda])
					     ->andWhere(['contribuyentes.id_contribuyente' => $idPatrocinador])
					    
					     
					     ->all();

					     //die(var_dump(expression))

			return $model;
		}

		public function busquedaIdPatrocinador($NroSolicitud)
		{
			//die(var_dump($NroSolicitud ));


				$query = New Query();

		

			$model = $query->select('*')
						 ->from('sl_propagandas_patrocinadores')
					   
					     ->where(['nro_solicitud' => $NroSolicitud])
					     ->andWhere(['estatus' => 0])
					     
					    
					     
					     ->all();


			return $model[0]['id_patrocinador'];
		}

		public function busquedaIdPatrocinadorAnulaciones($NroSolicitud)
		{
			//die('llegue');

			$model = SlAnulacionesPatrocinadores::find()
												->where([
													'nro_solicitud' => $NroSolicitud,
													'estatus' => 0,

													])
												
												->all();
												//die(var_dump($model));

							if ($model == true){
								return $model[0]->id_patrocinador;

							}else{
								return false;
							}
		}







		





	}
?>