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
	class Ente
	{
		protected $noPais = 1;
		protected $pais = 'Republica Bolivariana de Venezuela';

		protected $noEnte = 16;
		protected $alcaldia = 'Alcaldia del Municipio Bolivariano de Guaicaipuro';

		protected $alias = ' LOS TEQUES';
		protected $eslogan = 'En Guaicaipuro Construimos Futuro';
		protected $rif = 'G-20004620-1';
		protected $direccionLocalidad = 'Los Teques';
		protected $telefonos = ['0212-321.50.68'];
		protected $email = ['hacienda.guaicaipuro@gmail.com'];
		protected $portalWeb = 'http://www.alcaldiadeguaicaipuro.gob.ve/';
		protected $noEstado = 0;
		protected $estado = 'Estado Miranda';

		protected $noMunicipio = 0;
		protected $municipio = 'Guaicaipuro';

		protected $cuentaRecaudadora = [
						'1234-1234-99-1234567890'
					];

		// identificador dentro del sistema, generado por el mismo.
		protected $id = 0;




		public function __construct()
		{
		}


		public function getCuentaRecaudadoraPrincipal($nro)
		{
			return $this->cuentaRecaudadora[$nro];
		}


		public function getPortalWeb()
		{
			return $this->portalWeb;
		}


		public function getNoPais()
		{
			return $this->noPais;
		}

		public function getPais()
		{
			return $this->pais;
		}

		public function getEnte()
		{
			return  $this->noEnte;
		}

		public function getAlcaldia()
		{
			return $this->alcaldia;
		}

		public function getAlias()
		{
			return $this->alias;
		}

		public function getEslogan()
		{
			return $this->eslogan;
		}

		public function getRif()
		{
			return $this->rif;
		}

		public function getDireccion()
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

		public function getnoEstado()
		{
			return $this->noEstado;
		}

		public function getEstado()
		{
			return $this->estado;
		}

		public function getnoMunicipio()
		{
			return $this->noMunicipio;
		}

		public function getMunicipio()
		{
			return $this->municipio;
		}

		public function getId()
		{
			return $this->id;
		}

	}




 ?>
