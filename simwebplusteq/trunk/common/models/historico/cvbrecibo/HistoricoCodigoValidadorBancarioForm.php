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
 *  @file HistoricoCodigoValidadorBancarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 HistoricoCodigoValidadorBancarioForm
 *
 *  @class HistoricoCodigoValidadorBancarioForm
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

	namespace common\models\historico\cvbrecibo;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\historico\cvbrecibo\HistoricoCodigoValidadorBancario;
	use common\conexion\ConexionController;
	use common\models\historico\cvbrecibo\GenerarValidadorRecibo;


	/**
	* Clase que permite guardar el historico del codigo validador bancario
	* cada vez que se genere el pdf del recibo de pago, se guardara el cvb
	* que se genere y el mismo se guardara en un historico con otros datos
	* que sirvieron para generar dicho cvb.
	*/
	class HistoricoCodigoValidadorBancarioForm extends HistoricoCodigoValidadorBancario
	{

		/**
		 * modelo del tipo clase "Deposito"
		 * @var clase Deposito
		 */
		private $_model;

		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_validador;




		/**
		 * Constructor de la clase
		 * @param Deposito $model modelo de la clase Deposito.
		 */
		public function __construct($model)
		{
			$this->_model = $model;
			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');

  			// Instancia del generador de codigo validador bancario.
  			$this->_validador = New GenerarValidadorRecibo($this->_model);
		}




		/**
		 * Metodo que guarda el historico
		 * @param string $observacion observaciones que se crea conveniente guardar.
		 * @return boolean retorna un true si guarda o un false en caso contrario.
		 */
		public function guardarHistorico($observacion = '')
		{
			$result = false;
			$this->_conn->open();

			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$tabla = $this->tableName();

			$arregloDatos = $this->attributes;

			// Se recorre los atributo de la entidad del historico
			foreach ( $this->attributes as $key => $value ) {
				if ( isset($this->_model->attributes[$key]) ) {
					$arregloDatos[$key] = $this->_model->attributes[$key];
				}
			}

			// Aqui se define quien esta generando el historico.
			$arregloDatos['cvb'] = $this->_validador->getCodigoValidadorRecibo();
			$arregloDatos['usuario'] = Yii::$app->identidad->getUsuario();
			$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
			$arregloDatos['inactivo'] = 0;
			$arregloDatos['observacion'] = $observacion;

			if ( $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos) ) {
				$this->_transaccion->commit();
				$result = true;
			} else {
				$this->_transaccion->rollBack();
			}

			$this->_conn->close();
			return $result;
		}



	}

?>