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
 *  @file CalcularLapsoTiempo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-03-2017
 *
 *  @class CalcularLapsoTiempo
 *  @brief Clase Modelo que maneja la politica
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

	namespace common\models\utilidades;

 	use Yii;
	use yii\db\Exception;



	/***/
	class CalcularLapsoTiempo
	{

		private $_fechaDesde;
		private $_fechaHasta;
		private $_intervalo;


		/**
		 * Constructor de la clase.
		 * @param string $fechaInicio [description]
		 * @param string $fechaFinal  [description]
		 */
		public function __construct($fechaInicio, $fechaFinal)
		{
			$this->_fechaDesde = date('Y-m-d', strtotime($fechaInicio));
			$this->_fechaHasta = date('Y-m-d', strtotime($fechaFinal));
			$this->_intervalo = date_diff(date_create($this->_fechaDesde), date_create($this->_fechaHasta));
		}



		/**
		 * Metodo que retorna la informacion de la clase tipo DateTime
		 * @return DateTime.
		 */
		public function getIntenvalo()
		{
			return $this->_intervalo;
		}




		/**
		 * Metodo que determina la cantidad de dias que existe entre las dos fechas
		 * @return string retorna un entero con al informacion.
		 */
		public function getCantidadDias()
		{
			return $diasTotales = (int)$this->_intervalo->{'days'};
		}




		/**
		 * Metodo que determina la cantidad de meses que existen entre dos fechas.
		 * @return string retorna un entero con al informacion.
		 */
		public function getCantidadMeses()
		{
			$años = (int)$this->_intervalo->{'y'};
			$meses = (int)$this->_intervalo->{'m'};
			$dia = (int)$this->_intervalo->{'d'};

			$diasTotales = (int)$this->_intervalo->{'days'};

			if ( $años > 0 ) {
				return $meses + ( $años * 12 );
			}  else {
				return $meses;
			}
		}




		/**
		 * Metodo que determina la cantidad de años que existe entre dos fechas.
		 * @return string retorna un entero con al informacion.
		 */
		public function getCantidadAnos()
		{
			$años = (int)$this->_intervalo->{'y'};
			$diasTotales = (int)$this->_intervalo->{'days'};
			if ( $diasTotales == 365 ) {
				return 1;
			}
			return $años;
		}





		/***/
		public function getCantidadSemanas()
		{
			// $arregloDesde = explode('-', trim($this->_fechaDesde));
			// $arregloHasta = explode('-', trim($this->_fechaHasta));

			// $semanaUno = (int)date('W', mktime(0, 0, 0, $arregloDesde[1], $arregloDesde[2], $arregloDesde[0]));
			// $semanaDos = (int)date('W', strtotime($this->_fechaHasta));

			return 1;
		}



		/**
		 * Metodo que retorna trimetre 4 como valor a tomar en cuenta en los calculos.
		 * @return integer retorna entero
		 */
		public function getCantidadTrimestre()
		{
			return 4;
		}

	}

?>