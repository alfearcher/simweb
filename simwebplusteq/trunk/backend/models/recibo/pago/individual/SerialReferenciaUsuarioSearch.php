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
 *  @file SerialReferenciaUsuarioSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-04-2017
 *
 *  @class SerialReferenciaUsuarioSearch
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

	namespace backend\models\recibo\pago\individual;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
      use yii\data\ArrayDataProvider;
      use backend\models\recibo\pago\individual\SerialReferenciaUsuario;



	/**
	* Clase que permite
	*/
	class SerialReferenciaUsuarioSearch
	{

	     private $_recibo;
           private $_usuario;


      	/**
      	 * Metodo constructor de la clase.
      	 * @param integer $recibo numero del recibo de pago.
             * @param string $usuario nombre del usuario.
      	 */
      	public function __construct($recibo, $usuario)
      	{
                  $this->_recibo = $recibo;
                  $this->_usuario = $usuario;
      	}



            /**
             * Metodo que permite definir el modelo principal de consuta.
             * @return SerialReferenciaUsuario.
             */
            private function findSerialReferenciaUsuarioModel()
            {
                  return $findModel = SerialReferenciaUsuario::find()->where('recibo =:recibo',
                                                                                    [':recibo' => $this->_recibo])
                                                                     ->andWhere('usuario =:usuario',
                                                                                    [':usuario' => $this->_usuario]);
            }



            /**
             * Metodo que permite realizar una consulta por el id del registro.
             * @param integer $idSerial identificador del registro.
             * @return Array con los datos consultados.
             */
            public function findSerialById($idSerial)
            {
                  $findModel = self::findSerialReferenciaUsuarioModel();
                  return $registers = $findModel->andWhere('id_serial =:id_serial',
                                                                  [':id_serial' => $idSerial])
                                                ->all();
            }




            /**
             * Metodo que permite encontrar los seriales registrados para un recibo y usuario
             * especifico.
             * @return SerialReferenciaUsuario.
             */
            public function findSeriales()
            {
                  $findModel = self::findSerialReferenciaUsuarioModel();
                  return $registers = $findModel->all();
            }




            /**
             * Metodo que genera el proveedor de datos para mostrar los seriales guardados
             * por el usuario para un recibo especifico.
             * @return ActiveDataProvider.
             */
            public function getDataProvider()
            {
                  $query = self::findSerialReferenciaUsuarioModel();
                  $provider = New ActiveDataProvider([
                                    'key' => 'id_serial',
                                    'query' => $query,
                  ]);
                  $query->all();
                  return $provider;
            }



            /**
             * Metodo que permite ejecutar la supresion de un registro, en los seriales
             * agregados por los usuarios.
             * @param integer $idSerial identificador de la entidad.
             * @param connection $connLocal
             * @param ConexionController $conexionLocal instancia de la clase.
             * @return boolean.
             */
            public function suprimirSerialById($idSerial, $connLocal, $conexionLocal)
            {
                  $result = false;
                  $tabla = SerialReferenciaUsuario::tableName();
                  $arregloCondicion = [
                        'id_serial' => $idSerial,
                  ];

                  return $result = $conexionLocal->eliminarRegistro($connLocal, $tabla, $arregloCondicion);
            }


	}

?>