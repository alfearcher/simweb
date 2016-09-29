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
	use backend\models\solicitud\estatus\EstatusSolicitud;


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
		 * Metodo que permite determinar si existe al menos una solicitud pendiente por procesar
		 * de inscripcion de sucursal.
		 * @return [type] [description]
		 */
		public function existeSolicitudPendiente()
		{
			$findModel = InscripcionSucursal::find()->where('id_contribuyente =:id_contribuyente',
																 [':id_contribuyente' => $this->_id_contribuyente])
													->andWhere('estatus =:estatus', [':estatus' => 0])
													->count();
			return isset($findModel) ? $findModel : 0;
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
	    										    ->andWhere('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->_id_contribuyente])
	    										    ->joinWith('estatusInscripcion')
	    											->one();
	    	return isset($modelFind) ? $modelFind : null;
	    }





	    /**
	     * Metodo que busca el ultimo identificador de las sucursales asociadas a un RIF,
	     * una vez obtenido este valor se le sumara uno (1), para devolver el siguiente.
	     * Este valor complementa la clave concatenada de naturaleza-cedula-tipo-idrif.
	     * @param  string $naturaleza indica el primer valor del RIF o DNI del contribuyente juridico
	     * - V => Venezolano.
	     * - E => Extranjero.
	     * - J => Juridico.
	     * - G => Gubernamental.
	     * - P => Pasaporte.
	     * @param  long $cedula segundo valor del RIF o DNI del contribuyente juridico.
	     * @param  integer $tipo ultimo valor del RIF o DNI del contribuyente juridico.
	     * @return integer retorna un identificador que sera el asignado al contribuyente-sucursal
	     * este valor complementa el registro a nivel de base de datos.
	     */
	    public  function getIdentificadorSucursalNuevo($naturaleza, $cedula, $tipo)
	    {
	    	$idRif = 0;
	    	if ( self::getSedePrincipal() ) {
		    	$datosSedePrincipal = self::getDatosContribuyente();
		    	if ( isset($datosSedePrincipal) ) {
		    		if ( $datosSedePrincipal['naturaleza'] = $naturaleza && $datosSedePrincipal['cedula'] = $cedula && $datosSedePrincipal['tipo'] = $tipo ) {
		    			// Lo siguiente retorna una array
		    			// array(1) {
  						//		["id_rif"]=>
  						//			string(1) "valor"
  						//	}
		    			$idRif = ContribuyenteBase::getUltimoIdRifSucursalSegunRIF($naturaleza, $cedula, $tipo);
		    		}
		    	}
		   	}
		   	return isset($idRif) ? $idRif['id_rif'] + 1 : 0;
	    }



	    /**
	     * Metodo que permite verificar si un contribuyente posee todos los datos necesarios
	     * del registro mercantil, esto permitira crear la sucursal y asumir los mismos valores
	     * que aparece en el registro mercantil de la sede principal.
	     * @param  $datos, array que posee los datos de la sede que se pretende tomar como principal.
	     * @return return boolean, true si todos los datos estan bien, false no se puede crear la sucursal.
	     */
	    public static function datosRegistroMercantilValido($datos)
	    {
	    	if ( is_array($datos) ) {
	    		if ( !isset($datos['fecha']) ) { return false; }
	    		if ( strlen(trim($datos['reg_mercantil'])) < 5 ) { return false; }
	    		if ( $datos['num_reg'] == 0 ) { return false; }
	    		if ( strlen(trim($datos['tomo'])) == 0 ) { return false; }
	    		if ( strlen(trim($datos['folio'])) == 0 ) { return false; }
	    		if ( $datos['capital'] == 0 || $datos['capital'] < 0 ) { return false; }
	    	} else {
	    		return false;
	    	}
	    	return true;
	    }

	}


?>