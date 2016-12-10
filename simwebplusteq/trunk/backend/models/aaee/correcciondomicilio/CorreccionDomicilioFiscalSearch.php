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
 *  @file CorreccionDomicilioFiscal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-07-2016
 *
 *  @class CorreccionDomicilioFiscal
 *  @brief Clase Modelo principal
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

 	namespace backend\models\aaee\correcciondomicilio;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\contribuyente\ContribuyenteBase;

	class CorreccionDomicilioFiscalSearch
	{

		private $_id_contribuyente;



		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}


		/**
		 * Metodo que permite determinar si el contribuyente ya tiene una solicitud pendiente,
		 * con el objetivo no repetir la solicitud.
		 * @return Boolean Retorna true si ya posee una solicitud con las caracteristicas
		 * descriptas, caso contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliarPendiente()
		{
			$modelFind = null;
			$modelFind = CorreccionDomicilioFiscal::find()->where('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->_id_contribuyente])
														  ->andWhere(['IN', 'estatus', [0]])
														  ->count();
			return ( $modelFind > 0 ) ? true : false;
		}



		/**
		 * Metodo que realiza una busqueda para determinar si esta inscrito como contribuyente
		 * de Actividad Economica.
		 * @return boolean retorna true si ya esta inscrito, false en caso contrario.
		 */
		public function estaInscritoActividadEconomica()
		{
			$result = false;
			$inscripcion = New InscripcionActividadEconomicaSearch($this->_id_contribuyente);
			$result = $inscripcion->yaEstaInscritoActividadEconomica();
			return $result;
		}



		/**
		 * Metodo que retorna los datos del contribuyente, segun el identificador
		 * del contribuyente, lo recibido en este metodo es un arreglo donde el
		 * indice del arregloes un entero que comienza en cero (0), y el valor del
		 * arreglo es otro arrelo con los atributos del contribuyente.
		 * @return array retorna un arreglo con la estructura:
		 * array {
		 * 		[0] => array {
		 *   				atributo0 => valor0
		 *       			atributo1 => valor1
		 *
		 *            		atributon => valorn
		 *   			}
		 * }
		 */
		public function getDatosContribuyente()
		{
			$datos = ContribuyenteBase::getDatosContribuyenteSegunID($this->_id_contribuyente);
			return (isset($datos)) ? $datos[0] : null;
		}



		/**
		 * Metodo que permite determinar si un contribuyente es de tipo naturaleza "JURIDICO".
		 * @return boolean retorna un true si es de tipos naturaleza "JURIDICO", false en caso
		 * contrario.
		 */
		public function esUnContribuyenteJuridico()
		{
			$result = false;
			$tipoNaturaleza = '';
			$tipoNaturaleza = ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->_id_contribuyente);
			if ( strtoupper($tipoNaturaleza) == 'JURIDICO' ) {
				$result = true;
			}
			return $result;
		}



		/***/
		public function findSolicitudCorreccionDomicilio($nroSolicitud)
		{
			$findModel = CorreccionDomicilioFiscal::find()->where('nro_solicitud =:nro_solicitud', [':nro_solicitud' => $nroSolicitud])
														  ->andWhere('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->_id_contribuyente])
														  ->joinWith('estatusSolicitud')
														  ->one();
			return isset($findModel) ? $findModel : null;
		}

	}
 ?>