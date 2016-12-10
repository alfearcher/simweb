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
 *	@file ControlLapso.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 18-08-2016
 *
 *  @class ControlLapso
 *	@brief Las constantes aqui definidas permitiran controlaran los los rangos de liquidacion
 *
 *	Se utilizara la variable Yii::$app->solicitud->evento para
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

 	/**
 	 * Clase que permite entregar los valores de los años-lapsos permitidos
 	 * segun la condicion del contribuyente. Aqui condicion se refiere a si
 	 * el mismo esta notificado de su deuda o no. En caso de que este notificado
 	 * se debe considerar un lapso de tiempo diferente a si el mismo no lo esta
 	 * notificado de su deuda. Se utilizara el siguiemte esquema para tener acceso
 	 * a estos constantes:
 	 * Yii::$app->lapso->año-limite-tal-cosa(). Este esuqema permitira tener acceso
 	 * a los valoes de las constantes especifica.
 	 *
 	 */
 	class ControlLapso
 	{
 		/**
 		 * Contribuyente que han sido notificado.
 		 */
	 	const LAPSO_LIMITE_NOTIFICADO = 6;

	 	/**
 		 * Contribuyente que no han sido notificado de su deuda.
 		 */
	 	const LAPSO_LIMITE_SIN_NOTIFICAR = 4;



	 	/**
	 	 * [anoLimiteNotificado description]
	 	 * @return [type] [description]
	 	 */
	 	public function anoLimiteNotificado()
	 	{
	 		return date('Y') - self::LAPSO_LIMITE_NOTIFICADO;
	 	}


	 	/***/
	 	public function anoLimiteSinNotificar()
	 	{
	 		return date('Y') - self::LAPSO_LIMITE_SIN_NOTIFICAR;
	 	}

	 }
?>