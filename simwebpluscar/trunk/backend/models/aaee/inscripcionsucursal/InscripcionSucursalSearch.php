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
 *  @file InscripcionSucursalSeaech.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-07-2016
 *
 *  @class InscripcionSucursalSearch
 *  @brief Clase modelo
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

	namespace backend\models\aaee\inscripcionsucursal;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\utilidad\documento\DocumentoRequisito;
	use yii\data\ActiveDataProvider;


	/**
	* 	Clase
	*/
	class InscripcionSucursalSearch extends InscripcionSucursal
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
		 * Metodo que permite determinar el tipo de naturaleza del contribuyente.
		 * Para el momento en que se realizo esta documentacion solo se manejaban
		 * los tipos de naturaleza:
		 * - Natural.
		 * - Juridico.
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return string retorna una cadena donde indica que tipo de naturaleza
		 * es el contribuyente.
		 */
		public function getDescripcionTipoNaturaleza()
		{
			return ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->_id_contribuyente);
		}



		/**
		 * Metodo que permite determinar si un contribuyente es una sede principal.
		 * Esto solo aplica para los contribuyentes juridicos.
		 * @return boolean true si es verdadero, false en caso contrario.
		 */
		public function getSedePrincipal()
		{
			return ContribuyenteBase::getEsUnaSedePrincipal($this->_id_contribuyente);
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
			return (isset($datos)) ? $datos[0] :null;
		}



		/**
		 * Metodo que crea un ArrayDataProvider que sirva para mostrar un grid view
		 * de documentos y/o requisitos. El metodo recibe
		 * @param  array $arrayDocumento arreglo de indices, que indica los item
		 * seleccionados y a los cuales se les debe buscar para generar el array data
		 * provider
		 * @return array data provider retorna un array data provider
		 */
		public function getDataProviderDocumentoSeleccionado($arrayDocumento)
		{
			$query = DocumentoRequisito::find();

			$dataProvider = New ActiveDataProvider([
					'query' => $query,
			]);
			$query->where(['in', 'id_documento', $arrayDocumento]);

			return isset($dataProvider) ? $dataProvider : null;
		}




	    /**
	     * Metodo que realiza una consulta de la solicitud especifica (model)
	     * "inscripcion de sucursal".
	     * @param  long $nroSolicitud identificador de la entidad "solicitudes-contribuyente".
	     * @return Active Record.
	     */
	    public function findInscripcion($nroSolicitud)
	    {
	    	$modelFind = InscripcionSucursal::find()->where('nro_solicitud =:nro_solicitud', [':nro_solicitud' => $nroSolicitud])
	    										    ->andWhere('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->id_contribuyente])
	    											->one();
	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /***/
	    public function getDescripcionEstatus($estatus)
	    {
	    	$result = '';
	    	if ( $estatus == 0 ) {
	    		$result = 'PENDIENTE';
	    	} elseif ( $estatus == 1 ) {
	    		$result = 'APROBADA';
	    	} elseif ( $estatus == 2 ) {
	    		$result = 'NEGADA';
	    	} elseif ( $estatus == 9 ) {
	    		$result = 'ANULADA';
	    	}

	    	return $result;
	    }

	}


?>