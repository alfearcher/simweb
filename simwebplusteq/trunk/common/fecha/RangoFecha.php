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
 *  @file RangoFecha.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 07/03/2016
 *
 *  @class RangoFecha
 *  @brief Clase Modelo que contiene la funcion RangoFecha, que sirve para crear selects con rangos de fechas desde la que desees hasta 
 *  la fecha actual.
 *
 *
 *  @property
 *
 *
 *  @method
 *  @RangoFecha
 *
 *  @inherits
 *
 */

	namespace common\fecha;

 	use Yii;
	use yii\base\Model;
	

	/**
	* 	Clase
	*/
	class RangoFecha
	{

		
		/**
		 *	Metodo que sirve para mostrar un rango de fechas hasta la fecha actual, enviando un parametro dinamico y conservando como fijo
		 *	el año actual
		 * 	@return Retorna un array con el rango de las fechas
		 */
		public function RangoFecha($fecha_desde)
		{
			

		
		$fecha_hasta = date('Y');

			
			foreach (range($fecha_desde , $fecha_hasta) as $fecha[]){


				


			}
			
			foreach ($fecha as $f){

				$array[] = ['id' => $f, 'campo' => $f];
			}

			return $array;

		}

		/**
		 * [RangoFechaOrdenanza description] metodo que calcula el rango de entre dos fechas enviadas como parametros
		 * @param [type] $fecha_desde [description] fecha inicial
		 * @param [type] $fecha_hasta [description] fecha final
		 */
		public function RangoFechaOrdenanza($fecha_desde, $fecha_hasta)
		{
			
			foreach (range($fecha_desde , $fecha_hasta) as $fecha[]){


			}
			
			foreach ($fecha as $f){

				$array[] = ['id' => $f, 'campo' => $f];
			}

			return $array;

		}





	}

?>