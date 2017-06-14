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
 *  @file RangoFechaValido.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-06-2017
 *
 *  @class RangoFechaValido
 *  @brief Clase Modelo
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

	namespace backend\models\utilidad\fecha;

 	use Yii;



	/**
	* Clase que permite validar si un rango de fecha es valida. La clase retorna un
	* boolean.
	*/
	class RangoFechaValido
	{

		private $_fecha_desde;
		private $_fecha_hasta;



		/**
		 * Metodo constructor de la clase.
		 * @param string $fechaDesde fecha inicio del rango.
		 * @param string $fechaHasta fecha final del rango.
		 */
		public function __construct($fechaDesde, $fechaHasta)
		{
			$this->_fecha_desde = $fechaDesde;
			$this->_fecha_hasta = $fechaHasta;
		}



		/**
	     * Metodo que permite la validacion del rango de las fechas.
	     * @return none
	     */
	    public function rangoValido()
	    {
	    	if ( trim($this->_fecha_desde) !== '' && trim($this->_fecha_hasta) !== '' ) {

	    		$fDesde = date('Y-m-d', strtotime($this->_fecha_desde));
	    		$fHasta = date('Y-m-d', strtotime($this->_fecha_hasta));

	    		if ( (int)date('Y', strtotime($fDesde)) == (int)date('Y', strtotime($fHasta)) ) {
	    			if ( (int)date('m', strtotime($fDesde)) == (int)date('m', strtotime($fHasta)) ) {
	    				if ( (int)date('d', strtotime($fDesde)) > (int)date('d', strtotime($fHasta)) ) {

	    					//$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    					return false;
	    				}

	    			} elseif ( (int)date('m', strtotime($fDesde)) > (int)date('m', strtotime($fHasta)) ) {

	    				//$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    				return false;

	    			}

	    		} elseif ( (int)date('Y', strtotime($fDesde)) < (int)date('Y', strtotime($fHasta)) ) {

	    			//$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    			return false;
	    		}
	    	} else {
	    		return false;
	    	}
	    	return true;
	    }

	}

?>