<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
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
 *	@file MensajeController.php
 *
 *	@author Jose Rafael Perez Teran
 *	@email jperez320@gmail.com - jperez820@hotmail.com
 *
 *	@date 25-12-2015
 *
 *  @class MensajeController
 *	@brief Clase MensajeController, controller de mensale.
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


 	namespace common\mensaje;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;


	/**
	 *
	 */
	class MensajeController extends Controller
	{



		/**
		 * Metodo que permite renderizar un mensaje
		 * @param  string  $cuerpoMensaje cuerpo del mensaje.
		 * @param  boolean $tipoModal  determina si el mensaje aparecera en formato modal.
		 * @return retorna una vista renderizada.
		 */
    	private function actionShowMensaje($cuerpoMensaje = [])
    	{
    		return $this->render('@common/views/mensaje/mensaje-modal', ['cuerpoMensaje' => $cuerpoMensaje]);
    	}



    	/**
    	 * Metodo que busca la lista de codigo-mensaje que se manejaran en el sistema.
    	 * @return retorna un arragle con el par de valores codigo-mensaje. Codigo es
    	 * una valor enetro de 3 digito.
    	 */
    	private function actionGetListaCodigoMensaje()
    	{
    		return require 'codigo-mensaje.php';
    	}



    	/**
         * Metodo que busca el mensaje segun su codigo
         * @param  integer $codigo codigo de 3 digitos del mensaje, este es un key
         * del arreglo de los mensajes codificados
         * @return retorna un string con la descripcion del mensaje.
         */
    	private function actionGetMensajeDescripcion($codigo)
    	{
    		$listaMensaje = self::actionGetListaCodigoMensaje();
    		return $listaMensaje[$codigo];
    	}



        /***/
        public function actionMensaje($codigo = 0, $render = true)
        {
            if ( $codigo > 0 ) {
                $mensaje = self::actionGetMensajeDescripcion($codigo);
                if ( $render == false ) {
                    return $mensaje[0];
                } else {
                    return self::actionShowMensaje($mensaje);
                }
            }
            return false;
        }

	}
?>