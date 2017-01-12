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
 *  @file SolicitudPlanillaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-06-2016
 *
 *  @class SolicitudPlanillaSearch
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

	namespace common\models\configuracion\solicitudplanilla;

 	use Yii;
	use yii\db\ActiveRecord;
	use yii\data\ArrayDataProvider;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;
	use common\models\planilla\PlanillaSearch;

	/**
	* 	Clase que permite localizar las planillas asociadas a una solicitud creada.
	*/
	class SolicitudPlanillaSearch extends SolicitudPlanilla
	{

		private $_nro_solicitud;
		private $_evento;


		/**
		 * Metodo constructor de la clase.
		 * @param Long $nroSolicitud identificador de la solicitud creada por el funcionario
		 * o por el contribuyente.
		 */
		public function __construct($nroSolicitud, $evento = '')
		{
			$this->_nro_solicitud = $nroSolicitud;
			$this->_evento = $evento;
		}


		/***/
		public function setEvento($evento = '')
		{
			$this->_evento = $evento;
		}


		/***/
		public function getEvento()
		{
			return $this->_evento;
		}



		 /**
         * Metodo que realiza la busqueda del modelo segun el parametro numero de solicitud
         * ($nroSolicitud) y evento asociado.
         * @param  String $evento, se refiere a:
         * - 	CREAR.
         * - 	APROBAR.
         * - 	NEGAR.
         * @return Active Record Retorna el modelo de la entidad segun el parametro numero de solicitud
         * ($nroSolicitud) y evento, si no se coloca el evento se retornan todos los registros.
         */
        public function findSolicitudPlanilla()
        {
            $modelFind = null;
            if ( trim($this->_evento) !== '' ) {
	            $modelFind = SolicitudPlanilla::find()->where('nro_solicitud =:nro_solicitud',
	            												 [':nro_solicitud' => $this->_nro_solicitud])
	            									  ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
	            									  ->andWhere('evento =:evento', [':evento' => $this->_evento]);
	        } else {
	        	$modelFind = SolicitudPlanilla::find()->where('nro_solicitud =:nro_solicitud',
	            												 [':nro_solicitud' => $this->_nro_solicitud])
	            									  ->andWhere('inactivo =:inactivo', [':inactivo' => 0]);
	        }
            return isset($modelFind) ? $modelFind : null;
        }



        /***/
        public function getArrayDataProvider()
        {
        	$planillas = self::getListaPlanillaSegunSolicitudCreada();
        	//$provider = null;
        	//if ( count($planillas) > 0 ) {
        		$provider = New ArrayDataProvider([
        				'allModels' => $planillas,
        				'pagination' => [
        					'pageSize' => 10,
    					],
        			]);
        	//}

        	return $provider;
        }



        /**
         * Metodo que determina segun numero de solicitud y evento, las planillas relacionadas
         * a las mismas, sino se indica el evento se buscan todos los registros relacionados.
         * Estas planillas son las que se crean cuando se ejecutan algunos de los eventos configurados:
         * - 	CREAR.
         * - 	APROBAR.
         * - 	NEGAR.
         * @return Array Retorna un arreglo multidimensional con un resumen de las planillas
         * contenidas de la solicitud y algunos datos basicos pertenecientes a cada planilla,
         * como:
         * - 	Id del Contribuyente.
         * - 	Suma del monto del impuesto
         * - 	Suma del Recargo.
         */
        public function getListaPlanillaSegunSolicitudCreada()
        {
			$planillas = null;
        	// Se obtiene(n) la(s) planilla(s), asociadas a la solicitud.
        	// Primero se obtiene el modelo de la entidad.
        	$model = self::findSolicitudPlanilla();
        	if ( $model !== null ) {
        		$datos = $model->asArray()->all();
        		foreach ( $datos as $dato ) {
        			$search = New PlanillaSearch($dato['planilla']);
        			$planillas[$dato['planilla']] = $search->getResumenGeneral();
        		}
        	}

        	return $planillas;
        }




	}


?>