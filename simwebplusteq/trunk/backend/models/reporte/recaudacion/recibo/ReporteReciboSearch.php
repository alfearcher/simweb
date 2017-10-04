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
 *  @file ReporteReciboSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-10-2017
 *
 *  @class ReporteReciboSearch
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

	namespace backend\models\reporte\recaudacion\recibo;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\reporte\recaudacion\recibo\ReporteReciboBusquedaForm;
	use backend\models\funcionario\Funcionario;
	use backend\models\recibo\deposito\Deposito;


	/***/
	class ReporteReciboSearch extends ReporteReciboBusquedaForm
	{




		/**
		 * Metodo setter de la fecha desde.
		 * @param string $fechaDesde fecha en formato YYYY-mm-dd
		 */
		public function setFechaDesde($fechaDesde)
		{
			$this->fecha_desde = date('Y-m-d', $fechaDesde);
		}


		/**
		 * Metodo setter de la fecha hasta.
		 * @param string $fechaHasta fecha en formato YYYY-mm-dd
		 */
		public function setFechaHasta($fechaHasta)
		{
			$this->fecha_hasta = date('Y-m-d', $fechaHasta);
		}


		/**
		 * Metodo setter del usuario.
		 * @param string $usuario nombre de usuario
		 */
		public function setUsuario($usuario)
		{
			$this->usuario = $usuario;
		}


		/**
		 * Metodo setter del usuario-creador.
		 * @param string $usuario nombre de usuario que creo el recibo.
		 */
		public function setUsuarioCreador($usuario)
		{
			$this->usuario_creador = $usuario;
		}


		/**
		 * Metodo setter del estatus del recibo.
		 * @param integer $estatus condicion del recibo.
		 */
		public function setEstatus($estatus)
		{
			$this->estatus = $estatus;
		}



		/**
		 * Metodo que retorna el modelo de consulta
		 * @return Funcionario.
		 */
		public function findUsuarioModel()
		{
			return Funcionario::find();
		}


		/**
		 * Metodo que ejecuta el find sobre la entidad Funcionario.
		 * @return array, registros del funcionarios.
		 */
		private function findUsuarioCreadorRecibo()
		{
			$findModel = self::findDepositoModel();
			return $model = $findModel->select([
											'usuario_creador',
											'CONCAT(apellidos, " ", nombres) as nombre',
										])
							   		  ->distinct('usuario_creador')
							   		  ->joinWith('usuarioCreoRecibo F', false, 'INNER JOIN')
							   		  ->where('usuario_creador !=:usuario_creador',
							   	 								[':usuario_creador' => ''])
							   		  ->orderBy([
							   		  		'nombre' => SORT_ASC,
							   		  ])
							   		  ->asArray()
							   		  ->all();
		}




		/**
		 * Metodo que ejecuta la consulta sobre los usuario que aparezcan
		 * como pagando un recibo de pago.
		 * @return array, registros del funcionarios.
		 */
		private function findUsuarioPagoRecibo()
		{
			$findModel = self::findDepositoModel();
			return $model = $findModel->select([
											'usuario',
											'CONCAT(apellidos, " ", nombres) as nombre',
										])
							   		  ->distinct('usuario')
							   		  ->joinWith('usuarioPagoRecibo F', false, 'INNER JOIN')
							   		  ->where('usuario !=:usuario', [':usuario' => ''])
							   		  ->orderBy([
							   		  		'nombre' => SORT_ASC,
							   		  ])
							   		  ->asArray()
							   		  ->all();
		}




		/**
		 * Metodo que crea una lista de usuarios.
		 * @return array
		 */
		public function getListaUsuarioCreadorFuncionario()
		{
			return ArrayHelper::map(self::findUsuarioCreadorRecibo(), 'usuario_creador', 'nombre');
		}


		/**
		 * Metodo que crea una lista de usuario que aparecen como usuarios que han
		 * pagado un recibo en algun momento.
		 * @return array
		 */
		public function getListaUsuarioPago()
		{
			return ArrayHelper::map(self::findUsuarioPagoRecibo(), 'usuario', 'nombre');
		}


		/**
		 * Metodo que retorna el modelo basico de consulta de la entidad "depostos"
		 * @return Deposito.
		 */
		private function findDepositoModel()
		{
			return Deposito::find()->alias('D');
		}


	}
?>