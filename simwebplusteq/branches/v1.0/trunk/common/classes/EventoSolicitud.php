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
 *	@file EventoSolicitud.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 15-10-2015
 *
 *  @class EventoSolicitud
 *	@brief Las constantes aqui definidas permitiran controlaran los eventos que
 *	esten relacionados a las solicitudes, dichos eventos se refieren a:
 *	- CREAR
 *	- APROBAR
 *	- NEGAR
 *	las solicitudes, se utilizara la variable Yii::$app->solicitud->evento para
 *	compararlo con lo que se defina a nivel de base de datos.
 *
 *
 *	@property
 *
 *
 *	@method
 *
 *
 *	@inherits
 *
 */


 	namespace common\classes;

 	use Yii;
 	use common\models\session\Session;

 	/**
 	 *
 	 */
 	class EventoSolicitud
 	{
	 	const CREAR_SOLICITUD = 'CREAR';
	 	const APROBAR_SOLICITUD = 'APROBAR';
	 	const NEGAR_SOLICITUD = 'NEGAR';

	 	public $_id;


	 	public function setId($id)
	 	{
	 		if ( isset($_SESSION['_id']) ) {
	 			Session::actionDeleteSession(['_id']);
	 			$_SESSION['_id'] = $id;
	 		} else {
	 			 $_SESSION['_id'] = $id;
	 		}
	 	}


	 	public function getId()
	 	{
	 		return $_SESSION['_id'];
	 	}


	 	/***/
	 	public function crear()
	 	{
	 		return self::CREAR_SOLICITUD;
	 	}


	 	/***/
	 	public function aprobar()
	 	{
	 		return self::APROBAR_SOLICITUD;
	 	}


	 	/***/
	 	public function negar()
	 	{
	 		return self::NEGAR_SOLICITUD;
	 	}


	 	/***/
	 	public function eventos()
	 	{
	 		$crear = $this->crear();
	 		$aprobar = $this->aprobar();
	 		$negar = $this->negar();

	 		$eventos = [$crear, $aprobar, $negar];

	 		return $eventos;
	 	}

	 }
?>