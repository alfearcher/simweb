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
 *  @file BusquedaSolicitudLicenciaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-01-2017
 *
 *  @class BusquedaSolicitudLicenciaForm
 *  @brief Clase Modelo del formulario para buscar las solicites de licencias.
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

	namespace backend\models\solicitud\especial\aaee\licencia;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use backend\models\aaee\licencia\LicenciaSolicitud;
	use backend\models\aaee\licencia\LicenciaSolicitudSearch;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use common\models\planilla\PlanillaSearch;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\historico\licencia\HistoricoLicencia;
	use common\models\deuda\DeudaSearch;
	use common\models\deuda\Solvente;



	/**
	* Clase del formulario que permite buscar las solicitudes de emision de licencia.
	*/
	class BusquedaSolicitudLicenciaForm extends Model
	{
		public $tipo;		// Tipos de licencias.
		public $fecha_desde;
		public $fecha_hasta;
		public $id_contribuyente;


		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['tipo', 'fecha_desde', 'fecha_hasta', 'id_contribuyente',], 'safe'],
	        	[['id_contribuyente',],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['tipo',],
	        	  'string', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['fecha_desde'],
	        	  'date', 'format' => 'dd-MM-yyyy',
	        	  'message' => Yii::t('backend','formatted date no valid')],
	        	[['fecha_hasta'],
	        	  'date', 'format' => 'dd-MM-yyyy',
	        	  'message' => Yii::t('backend','formatted date no valid')],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'required',
	        	  'when' => function($model) {
	        	  				if ( $model->tipo !== null ) {
	        	  					return true;
	        	  				}
	        	  			}
	        	 ],
	        	[['fecha_hasta'], 'required',
	        	  'when' => function($model) {
	        	  				if ( $model->fecha_desde !== null ) {
	        	  					return true;
	        	  				}
	        				}
	        	, 'message' => Yii::t('backend', '{attribute} is required')],
	        	[['fecha_desde'], 'required',
	        	  'when' => function($model) {
	        	  				if ( $model->fecha_hasta !== null ) {
	        	  					return true;
	        	  				}
	        				}
	        	, 'message' => Yii::t('backend', '{attribute} is required')],
	        	//['fecha_desde', 'date', 'timestampAttribute' => 'fecha_desde','message' => 'hola'],
	        	//['fecha_hasta', 'date', 'timestampAttribute' => 'fecha_hasta'],
	        	['fecha_desde',
	        	 'compare',
	        	 'compareAttribute' => 'fecha_hasta',
	        	 'operator' => '<=',
	        	 'enableClientValidation' => false],
	        	// [['fecha_hasta'], 'compare',
	        	//   'compareAttribute' => 'fecha_desde', 'operator' => '>=', 'type' => 'date'],

	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }




	    /**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return [type] [description]
	     */
	    private function getListaFuncionarioAutorizado()
	    {
	    	return [
	    		'admin',
	    		'jperez',
	    	];
	    }



	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario)
	    {
	    	$listaUsuarioAutorizado = self::getListaFuncionarioAutorizado();
	    	if ( count($listaUsuarioAutorizado) > 0 ) {
	    		foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    			if ( $value == $usuario ) {
	    				return true;
	    			}
	    		}
	    	}
	    	return false;
	    }




	    /**
	     * [findSolicitudLicencaModel description]
	     * @return [type] [description]
	     */
		public function findSolicitudLicencaModel()
		{
			return $findModel = LicenciaSolicitud::find()->alias('L');
		}




		/**
		 * [findSolicitudLicencia description]
		 * @return [type] [description]
		 */
		public function findSolicitudLicencia()
		{
			$findModel = self::findSolicitudLicencaModel();

			$model = $findModel->select([
									'L.nro_solicitud',
									'L.fecha_hora',
									'tipo',
									'L.ano_impositivo',
									'L.id_contribuyente',
									'licencia',
									'P.planilla',

								])
							   ->distinct('L.nro_solicitud')
							   ->joinWith('solicitud S')
							   ->joinWith('planillas P')
							   ->groupBy('L.nro_solicitud')
							   ->orderBy([
							   		'L.nro_solicitud' => SORT_ASC,
							   	]);


			return $model;
		}



		/**
		 * [getDatoSolicitudLicencia description]
		 * @param  [type] $params [description]
		 * @return [type]         [description]
		 */
		public function getDatoSolicitudLicencia($params)
		{
			$findModel = self::findSolicitudLicencia();
			$this->load($params);

			if ( $this->id_contribuyente > 0 ) {

				$model = $findModel->where('L.estatus =:estatus',
											[':estatus' => 0])
								   ->andWhere('L.id_contribuyente =:id_contribuyente',
								   			[':id_contribuyente' => $this->id_contribuyente]);

				return $model->asArray()->all();

			} else {

				$model = $findModel->where('L.estatus =:estatus',
											[':estatus' => 0])
								   ->andWhere('L.tipo =:tipo',[':tipo' => $this->tipo])
								   ->andWhere(['BETWEEN', 'date(L.fecha_hora)',
								   						  		date('Y-m-d',strtotime($this->fecha_desde)),
				    	      			 						date('Y-m-d',strtotime($this->fecha_hasta))
								   	]);

				return $model->asArray()->all();
			}

			return null;
		}




		/**
		 * Metodo que permite obtener los datos basicos de las solicitudes seleccionads
		 * por el usuario.Para generar una vista previa de las solicitudes
		 * @param  array $chkNroSolicituds arreglo de numero de solicitudes de licnecias
		 * @return LicenciaSolicitud
		 */
		public function getSolicitudLicenciaSeleccionada($chkNroSolicituds)
		{
			$findModel = self::findSolicitudLicencia();

			$model = $findModel->where('L.estatus =:estatus',
											[':estatus' => 0])
								   ->andWhere(['IN', 'L.nro_solicitud', $chkNroSolicituds]);

			return $model->asArray()->all();
		}





		/**
		 * [armarDataProvider description]
		 * @return [type] [description]
		 */
		public function armarDataProvider($params, $preView = false)
		{
			$data = [];
			$provider = [];
			if ( !$preView ) {
				$results = self::getDatoSolicitudLicencia($params);
			} else {
				$results = self::getSolicitudLicenciaSeleccionada($params);
			}


			if ( $results !== null && count($results) > 0 ) {

				foreach ( $results as $result ) {

					$bloquear = 0;
					$observacion = self::determinarCondicionSolicitud($result);
					if ( count($observacion) > 0 ) {
						$bloquear = 1;
					}

					$data[$result['nro_solicitud']] = [

						'nro_solicitud' => $result['nro_solicitud'],
						'fecha_hora' => $result['fecha_hora'],
						'tipo' => $result['tipo'],
						'ano_impositivo' => $result['ano_impositivo'],
						'id_contribuyente' => $result['id_contribuyente'],
						'contribuyente' => ContribuyenteBase::getContribuyenteDescripcionSegunID($result['id_contribuyente']),
						'licencia' => $result['licencia'],
						'planilla' => $result['planilla'],
						'bloquear' => $bloquear,
						'observacion' => $observacion,

					];
				}

			}
			$provider = New ArrayDataProvider([
						'key' => 'nro_solicitud',
						'allModels' => $data,
						'pagination' => false,
			]);
			return $provider;
		}







		/***/
		public function getDataProvider($params)
		{
			$query = self::getSolicitudLicencia();

			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);

			$dataProvider->setSort([
				'attributes' => [
					'nro_solicitud' => [
						'asc' => ['L.nro_solicitud' => SORT_ASC],
						'desc' => ['L.nro_solicitud' => SORT_DESC],
					],
					'id_contribuyente' => [
						'asc' => ['L.id_contribuyente' => SORT_ASC],
						'desc' => ['L.id_contribuyente' => SORT_DESC],
					],

				],
			]);

			$this->load($params);

			if ( $this->id_contribuyente > 0 ) {
				$query->filterWhere(['=', 'L.id_contribuyente', $this->id_contribuyente]);

			} else {
				$query->filterWhere(['=', 'tipo', $this->tipo])
				      ->andFilterWhere(['BETWEEN', 'date(L.fecha_hora)',
		    				      		 date('Y-m-d',strtotime($this->fecha_desde)),
				    	      			 date('Y-m-d',strtotime($this->fecha_hasta))])
				      ->andFilterWhere(['=', 'L.estatus', 0]);
			}

			return $dataProvider;
		}





		/**
		 * Metodo que permite determinar si la planilla esta pagada o no.
		 * @param  integer $planilla numero de la planilla.
		 * @return boolean
		 */
		public function estaPagadaLaPlanilla($planilla)
		{
			$searchPlanilla = New PlanillaSearch($planilla);
			$result = $searchPlanilla->condicionPlanilla();
			if ( count($result) > 0 ) {
				if ( $result[0]['pago'] == 1 || $result[0]['pago'] == 7 ) {
					return true;
				}
			}
			return false;
		}






		/**
		 * Metodo que permite determinar si un contribuyente de actividad economica
		 * posee licencia de ejercicio de actividad. Se tomara como valido si la longitud
		 * del numero de licencia encontrado es superior a 2 caracteres.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return boolean
		 */
		public function poseeLicencia($idContribuyente)
		{
			$licencia = ContribuyenteBase::getLicenciaSegunID($idContribuyente);
			if ( $licencia == false ) {
				return false;
			} else {
				if ( strlen($licencia['id_sim']) > 2 ) {
					return true;
				}
			}

			return false;
		}



		/**
		 * [determinarCondicionSolicitud description]
		 * @param  [type] $result [description]
		 * @return [type]         [description]
		 */
		public function determinarCondicionSolicitud($result)
		{
			$observacion = [];
			if ( !self::estaPagadaLaPlanilla($result['planilla']) ) {
				$observacion[] = Yii::t('backend', 'La planilla ' . $result['planilla'] . ' no esta pagada');
			}

			if ( !self::poseeLicencia($result['id_contribuyente']) ) {
				$observacion[] = Yii::t('backend', 'El contribuyente no posee un numero de licencia valido');
			}

			if ( !self::solventeConActividadEconomica($result['id_contribuyente']) ) {
				$observacion[] = Yii::t('backend', 'El contribuyente aparece como no solvente para el impuesto de ACTIVIDAD ECONOMICA (Estimada)');
			}


			// Lista de objetos que posee el contribuyente.
			// En este caso solo vehiculo.
			$impuestos = [3];
			$descripcion = '';

			foreach ( $impuestos as $i => $impuesto ) {

				$listaObjeto = self::getListaObjeto($result['id_contribuyente'], $impuesto);
				if ( count($listaObjeto) > 0 ) {
					foreach ( $listaObjeto as $objeto ) {
						if ( $impuesto == 3 ) {
die(var_dump($objeto));
							$idImpuesto = $objeto['id_vehiculo'];
							$descripcion = 'El vehiculo de placa: ' . $objeto['placa'];
						}

						if ( !self::estaSolventeObjeto($impuesto, $idImpuesto, $result['id_contribuyente']) ) {
							$observacion[] = $descripcion .  ', NO ESTA SOLVENTE';
						}
					}
				}

			}


			// Deuda de otros impuestos. Impuesto menores.
			$deudaOtroImpuesto = self::getDeudaOtroImpuesto($result['id_contribuyente']);
			if ( count($deudaOtroImpuesto) > 0 ) {
				foreach ( $deudaOtroImpuesto as $deudas ) {
					foreach ($deudas as $planilla ) {
						$observacion[] = 'Presenta deuda en ' . $planilla['descripcion_impuesto'] . ', con planilla: ' . $planilla['planilla'];
					}
				}
			}



			// Deuda de propaganda.
			$deudaObjeto = self::getDeudaPorObjeto($result['id_contribuyente']);
			if ( count($deudaObjeto) > 0 ) {
				foreach ( $deudaObjeto as $key => $value ) {
					foreach ( $value as $i => $objeto ) {
						if ( $objeto['impuesto'] == 3 ) {
							$observacion[] = 'Presenta deuda en ' . $objeto['descripcion'] . ', objeto: ' . $objeto['id_vehiculo'] . ' - ' . $objeto['placa'] . ', deuda: ' . $objeto['t'];

						} elseif ( $objeto['impuesto'] == 4 ) {
							$observacion[] = 'Presenta deuda en ' . $objeto['descripcion'] . ', objeto: ' . $objeto['id_impuesto'] . ' - ' . $objeto['nombre_propaganda'] . ', deuda: ' . $objeto['t'];

						}
					}
				}

			}


			return $observacion;
		}





		/**
		 * Metodo para obtener el dataprovider del historico de licencias.
		 * @param  array $nroSolicitudes arreglo de numero de solicitudes
		 * @return ActiveDataProvider
		 */
		public function getDataProviderHistoricoLicencia($nroSolicitudes)
		{
			$query = HistoricoLicencia::find();

			$dataProvider = New ActiveDataProvider([
				'query' => $query,
			]);

			$query->filterWhere(['IN', 'nro_solicitud', $nroSolicitudes]);

			return $dataProvider;
		}




		/**
		 * Metodo que permite obtener las deudas por objetos de los contribuyentes.
		 * Los objetos se entiende por Propaganda.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return array retorna arreglo con los datos de las deudas o un vacio.
		 */
		private function getDeudaPorObjeto($idContribuyente)
		{
			$impuestos = [4];
			$deuda = null;

			$deudaSearch = New DeudaSearch($idContribuyente);
			foreach ( $impuestos as $key => $value ) {
				$deudaObjeto = null;
				$deudaObjeto = $deudaSearch->getDeudaPorListaObjeto($value);
				if ( count($deudaObjeto) > 0 ) {
					$deuda[$value] =  $deudaObjeto;
				}

			}

			return $deuda;

		}



		/**
		 * Metodo que determina si los objetos relacinados al contribuyente estan
		 * solvente. Aqui los objetos son inmuebles y vehiculos.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return array retorna un arreglo indicando los objetos que no estan solventes.
		 */
		private function getListaObjeto($idContribuyente, $impuesto = 0)
		{
			$solvente = New Solvente();
			$solvente->setIdContribuyente($idContribuyente);
			if ( $impuesto > 0 ) {
				$impuestos = [$impuesto];
			} else {
				$impuestos = [3];
			}

			$lista = null;

			foreach ( $impuestos as $key => $value ) {
				$solvente->setImpuesto($value);
				$lista[$value] = $solvente->getListaObjetoContribuyente();
			}

			return $lista;
		}




		/**
		 * Metodo que permite determinar si un objeto esta solvente.
		 * @param integer $impuesto identificador del impuesto.
		 * @param integer $idImpuesto identificador del objeto.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return booolen.
		 */
		private function estaSolventeObjeto($impuesto, $idImpuesto, $idContribuyente)
		{
			$solvente = New Solvente();
			$solvente->setIdContribuyente($idContribuyente);
			$solvente->setImpuesto($impuesto);
			$solvente->setIdImpuesto($idImpuesto);
			return $solvente->verificarCondicionObjeto();

		}




		/**
		 * Metodo que determina si el contribuyente esta solvente con el impuesto
		 * de Actividad Economica, en lo referente a Estimada.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return boolean.
		 */
		private function solventeConActividadEconomica($idContribuyente)
		{
			$solvente = New Solvente();
			$solvente->setIdContribuyente($idContribuyente);
			$solvente->setImpuesto(1);

			return $solvente->estaSolventeActividadEconomica();
		}





		/**
		 * Metodo que busca deudas eb otros impuestos especificados.
		 * Retorna un arreglo de planillas en deuda.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param inteher $impuesto identificador del impuesto.
		 * @return array
		 */
		private function getDeudaOtroImpuesto($idContribuyente, $impuesto = 0)
		{
			$deuda = '';
			if ( $impuesto > 0 ) {
				$impuestos = [$impuesto];
			} else {
				$impuestos = [6, 7, 9, 10, 11];
			}

			$deudaSearch = New DeudaSearch($idContribuyente);
			foreach ( $impuestos as $key => $value ) {
				$deuda[$value] = $deudaSearch->getDetalleDeudaObjetoPorPlanilla($impuesto, 0, '=');
			}

			return $deuda;
		}



	}
?>