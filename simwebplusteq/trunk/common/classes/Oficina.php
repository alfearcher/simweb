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
 *	@file Oficina.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 13-05-2015
 *
 *  @class Oficina
 *	@brief Clase que posee los datos basicos de la Dirección u Oficina encargada de la
 * 	@brief recaudacion de los impuestos, permitiendo asi la customización la aplicacion.
 *
 *
 *	@property
 *  protected $nombre;
 *	protected $nombreCargo;
 *
 *  protected $id;
 *	protected $direccionLocalidad;
 *	protected $telefonos;
 *	protected $email;
 *
 *	@method
 *
 *	@inherits
 *
 */

 	namespace common\classes;
 	/**
 	*
 	*/
 	class Oficina
 	{

 		protected $nombre = 'Direccion de Hacienda Municipal';
 		protected $nombreCargo = 'Director(a) de Hacienda Municipal';
 		protected $director = 'Kelly Pérez';

 		protected $nombreCatastro = 'Direccion de Catastro Municipal';
 		protected $directorCatastro = 'Nombre del director de catastro';
 		protected $directorCatastroCargo = 'Director(a) de Catastro Municipal';
 		//	Inddentificador de la oficina
 		protected $id = 1;
 		protected $direccionLocalidad;
 		protected $telefonos = [];
 		protected $email = [];




 		public function __construct()
 		{
 		}


 		public function	getDirector()
 		{
 			return $this->director;
 		}


 		public function getNombre()
 		{
 			return $this->nombre;
 		}

 		public function getNombreCargo()
 		{
 			return $this->nombreCargo;
 		}

 		public function getNombreCatastro()
 		{
 			return $this->nombreCatastro;
 		}

 		public function	getDirectorCatastro()
 		{
 			return $this->directorCatastro;
 		}
 		public function getDirectorCatastroCargo()
 		{
 			return $this->directorCatastroCargo;
 		}

 		public function getDireccionLocalidad()
 		{
 			return $this->direccionLocalidad;
 		}

 		public function getTelefonos()
 		{
 			return $this->telefonos;
 		}

 		public function getEmail()
 		{
 			return $this->email;
 		}

 		public function getId()
 		{
 			return $this->id;
 		}

 	}


 ?>