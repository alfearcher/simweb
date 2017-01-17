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
 *  @file IncripcionPropagandaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @class IncripcionPropagandaSearch
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

 	namespace backend\models\propaganda\inscripcionpropaganda;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\propaganda\inscripcionpropaganda\InscripcionPropaganda;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomica;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\propaganda\uso\UsoPropaganda;
	use backend\models\propaganda\clase\ClasePropaganda;
	use backend\models\propaganda\tipo\TipoPropaganda;
	use backend\models\propaganda\mediodifusion\MedioDifusion;
	use backend\models\propaganda\mediotransporte\MedioTransporte;
	use yii\helpers\ArrayHelper;




	class InscripcionPropagandaSearch
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
			$modelFind = InscripcionPropaganda::find()->where('id_contribuyente =:id_contribuyente',
						 										[':id_contribuyente' => $this->_id_contribuyente])
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
		public function findSolicitudInscripcionPropaganda($nroSolicitud)
		{
			$findModel = InscripcionPropaganda::find()->where('nro_solicitud =:nro_solicitud',
			 													[':nro_solicitud' => $nroSolicitud])
													  ->andWhere('id_contribuyente =:id_contribuyente',
													  			[':id_contribuyente' => $this->_id_contribuyente])
													  ->joinWith('estatusSolicitud')
													  ->one();
			return isset($findModel) ? $findModel : null;
		}



		/***/
		public function getListaUsoPropaganda($uso = [])
		{
			if ( count($uso) > 0 ) {
				$modelUso = UsoPropaganda::find()->where(['IN', 'uso_propaganda', $uso])
			                                 ->all();
			} else {
				$modelUso = UsoPropaganda::find()->all();
			}

			return ArrayHelper::map($modelUso,'uso_propaganda','descripcion');

		}

	}
 ?>