<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIGECON
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
 *	@file Session.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 27-01-2016
 *
 *  @class Session
 *	@brief
 *
 *	Esto es un detalle
 *
 *
 *
 *	@property
 *
 *
 *	@method
 *
 *	@inherits
 *
 */


	namespace common\models\session;


	use yii\db\Connection;
	use Yii;


	/**
	 *
	 */
	class Session
	{


		/**
		 * Metodo que permite eliminar una o varias variable(s) de session.
		 * @param  array  $varSessions, Nombre de la(s) session(s) que se quiere eliminar.
		 * @return [type]             [description]
		 */
		public function actionDeleteSession($varSessions = [])
		{
			if ( count($varSessions) > 0 ) {
				foreach ( $varSessions as $varSession ) {
					if ( self::actionExisteSession($varSession) ) {
						unset($_SESSION[$varSession]);
					}
				}
			}
		}



		/**
		 * Metodo que determina si una ssession existe.
		 * @param  string $varSession, Nombre de la variable de session.
		 * @return boolean true si existe false sino existe.
		 */
		public function actionExisteSession($varSession = '')
		{
			if ( trim($varSession) != '' ) {
				if ( isset($_SESSION[$varSession]) ) {
					return true;
				}
			}
			return false;
		}




		/**
		 * Metodo que determina si la variable Yii esta seteada con los datos de un usuario.
		 * @return boolean true si existe false sino existe.
		 */
		public function actionExisteUser()
		{
			if ( isset(Yii::$app->user->identity) ) {
				return true;
			}
			return false;
		}




		/**
		 * Metodo que determina que nombre de usuario esta seteado en ese momento,
		 * la informacion sera obtenida de la variable Yii.
		 * @return string Nombre de usuario que esta seteado en la variable Yii.
		 * Sino existe entonces retorna blanco
		 */
		public function actionQuienEstaActivo()
		{
			$user = '';
			if ( self::actionExisteUser() ) {
				$user = Yii::$app->user->identity->username;
			}
			return $user;
		}
	}
?>