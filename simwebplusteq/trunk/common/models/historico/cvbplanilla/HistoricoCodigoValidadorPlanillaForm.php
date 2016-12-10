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
 *  @file HistoricoCodigoValidadorPlanillaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-11-2016 HistoricoCodigoValidadorPlanillaForm
 *
 *  @class HistoricoCodigoValidadorPlanillaForm
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

	namespace common\models\historico\cvbplanilla;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\historico\cvbplanilla\HistoricoCodigoValidadorPlanilla;
	use common\conexion\ConexionController;
	use common\models\historico\cvbplanilla\GenerarValidadorPlanilla;


	/**
	* Clase que permite guardar el historico del codigo validador de la planilla
	* cada vez que se genere el pdf de la planilla de pago, se guardara el cvb
	* que se genere y el mismo se guardara en un historico con otros datos
	* que sirvieron para generar dicho cvb.
	*/
	class HistoricoCodigoValidadorPlanillaForm extends HistoricoCodigoValidadorPlanilla
	{

		/**
		 * modelo del tipo clase "Deposito"
		 * @var clase Deposito
		 */
		private $_planilla;

		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_validador;

		private $_observacion = '';




		/**
		 * Constructor de la clase
		 * @param integer $planilla
		 */
		public function __construct($planilla, $observacion = '')
		{
			$this->_planilla = $planilla;
			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');

  			$this->_observacion = $observacion;

		}




		/**
		 * Metodo que guarda el historico
		 * @param string $observacion observaciones que se crea conveniente guardar.
		 * @return boolean retorna un true si guarda o un false en caso contrario.
		 */
		public function guardarHistorico($arregloDatos)
		{
			$result = false;
			$this->_conn->open();
			$observacion = '';

			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$tabla = $this->tableName();

			$this->attributes = $arregloDatos;

			// Aqui se define quien esta generando el historico.
			$arregloDatos['usuario'] = Yii::$app->identidad->getUsuario();
			$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
			$arregloDatos['estatus'] = 0;
			if ( trim($this->_observacion) !== '' ) {
				$observacion = trim($this->_observacion);
			}
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