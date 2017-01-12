<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file Ente.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 13-05-2015
 *
 *  @class Ente
 *	@brief Clase que posee los datos basicos de la institucion o alcaldia, para
 *  @brief customizar la aplicacion.
 *
 *
 *
 *	@property
 *  protected $noPais;
 *  protected $pais;
 *  protected $noEnte;
 *  protected $alcaldia;
 *	protected $alias;
 *	protected $eslogan;
 *	protected $rif;
 *	protected $direccion;
 *	protected $telefonos;
 *	protected $email
 *
 *  protected $noEstado;
 *  protected $estado;
 *
 *  protected $noMunicipio;
 *	protected $municipio;
 *
 * 	protected $id
 *
 *	@method
 *
 *	@inherits
 *
 */
	namespace common\classes;

	use Yii;
	/**
	*
	*/
	class MesDias
	{
		protected $meses = [
					1 => 'enero',
					2 => 'febrero',
					3 => 'marzo',
					4 => 'abril',
					5 => 'mayo',
					6 => 'junio',
					7 => 'julio',
					8 => 'agosto',
					9 => 'septiembre',
					10 => 'octubre',
					11 => 'noviembre',
					12 => 'diciembre',
		];


		protected $dias = [
					0 => 'domingo',
					1 => 'lunes',
					2 => 'martes',
					3 => 'miercoles',
					4 => 'jueves',
					5 => 'vierenes',
					6 => 'sabado',

		];



		/**
		 * Metodo que retorna la descripcion del mes a partir del numero
		 * que le corresponde en el año.
		 * @param  integer $mes identificador del mes en el año.
		 * @return string retorna la descripcion del mes.
		 */
		public function getMes($mes)
		{
			return $this->meses[$mes];
		}



		/**
		 * Metodo que retorna un arreglo con los identificadores de los meses
		 * y la descripcion de los mismos.
		 * @return array retorna un arreglo de meses.
		 * [ i => descripcion ]
		 */
		public function getMeses()
		{
			return $this->meses;
		}


		/***/
		public function getDia($dia)
		{
			return $this->dias[$dia];
		}


		/***/
		public function getDias()
		{
			return $this->dias;
		}

	}




 ?>