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
 *  @file InscripcionActividadEconomicaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-05-2016
 *
 *  @class InscripcionActividadEconomicaSearch
 *  @brief Clase Modelo que maneja la politica de busqueda
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

	namespace backend\models\aaee\inscripcionactecon;

 	use Yii;
	//use yii\base\Model;
	//use yii\db\ActiveRecord;
	use common\models\contribuyente\ContribuyenteBase;

	/**
	* 	Clase
	*/
	class InscripcionActividadEconomicaSearch extends InscripcionActividadEconomica
	{

		private $id_contribuyente;



		/**
		 * Metodo constructor de la clase
		 * @param long $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->id_contribuyente = $idContribuyente;
		}



		/**
		 * Metodo que permite determinar si el contribuyente posee una solicitud pendiente (estatus = 0)
		 * o aprobada (estatus = 1), del mismo tipo, por ser una solicitud de inscripcion
		 * de actividad economica no deberia existir ninguna pendiente o aprobada.
		 * @return Boolean Retorna true si ya posee una solicitud con las caracteristicas descriptas, caso
		 * contrario retornara false.
		 */
		public function yaPoseeSolicitudSimiliar()
		{
			$modelFind = null;
			$modelFind = InscripcionActividadEconomica::find()->where('id_contribuyente =:id_contribuyente',
			 															[':id_contribuyente' => $this->id_contribuyente])
															  ->andWhere(['IN', 'estatus', [0,1]])
															  ->count();
			return ($modelFind > 0) ? true : false;
		}



		/**
		 * Metodo que permite determinar si el contribuyente posee una solicitud pendiente (estatus = 0)
		 * del mismo tipo.
		 * @return boolean retorna true si ya posee una solicitud con las caracteristicas descriptas, caso
		 * contrario retornara false.
		 */
		public function poseeSolicitudPendiente()
		{
			$findModel = null;
			$findModel = InscripcionActividadEconomica::find()->where('id_contribuyente =:id_contribuyente',
									 									[':id_contribuyente' => $this->id_contribuyente])
															  ->andWhere(['IN', 'estatus', [0]])
															  ->count();
			return ($findModel > 0) ? true : false;
		}



		/**
		 * Metodo que permite buscar una solcitud de inscripcion de Actividad Economica
		 * procesada y activa. Que permita determinar que el contribuyente esta inscrito
		 * como contribuyente de Actividad Economica.
		 * @return boolean retorna true si encuntra al menos un registro, false en caso
		 * contrario.
		 */
		public function yaEstaInscritoActividadEconomica()
		{
			$modelFind = null;
			$modelFind = InscripcionActividadEconomica::find()->where('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->id_contribuyente])
															  ->andWhere(['IN', 'estatus', [1]])
															  ->count();
			return ($modelFind > 0) ? true : false;
		}


		 /**
	     * Metodo que retorna la descripcion del tipo de contribuyente, segun el identificador del mismo.
	     * "NATURAL".
	     * "JURIDICO".
	     * @param  Long $idContribuyente identificador dle contribuyente.
	     * @return String Retorna la descripcion del tipo de contribuyente.
	     */
	    public function getTipoNaturalezaDescripcionSegunID()
	    {
	    	$descripcion = null;
	    	return $descripcion = ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->id_contribuyente);
	    }




	    /**
	     * Metodo que realiza una busqueda del detalle de la solicitud (model)
	     * "inscripciones-actividad-economica".
	     * @param  Long $nroSolicitud identificador de la entidad "solicitudes-contribuyente".
	     * @return Active Record.
	     */
	    public function findInscripcion($nroSolicitud)
	    {
	    	$modelFind = InscripcionActividadEconomica::find()->where('nro_solicitud =:nro_solicitud', [':nro_solicitud' => $nroSolicitud])
	    													  ->andWhere('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->id_contribuyente])
	    													  ->joinWith('estatusInscripcion')
	    													  ->one();
	    	return isset($modelFind) ? $modelFind : null;
	    }


	}

?>