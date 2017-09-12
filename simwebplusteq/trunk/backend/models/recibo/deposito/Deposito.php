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
 *  @file Deposito.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-09-2016
 *
 *  @class Deposito
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

	namespace backend\models\recibo\deposito;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\models\calculo\cvb\ModuloValidador;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use backend\models\recibo\estatus\EstatusDeposito;
	use common\models\contribuyente\ContribuyenteBase;



	/**
	* 	Clase
	*/
	class Deposito extends ActiveRecord
	{


		/**
		 *	Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
		 * 	Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
		 * 	@return Nombre de la base de datos
		 */
		public static function getDb()
		{
			return Yii::$app->db;
		}


		/**
		 * 	Metodo que retorna el nombre de la tabla que utiliza el modelo.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'depositos';
		}


		/***/
		public function getCodigoControl($valorConvertir)
		{
			return ModuloValidador::getDigitoControl($valorConvertir);
		}


		/***/
		public function getDepositoPlanilla()
		{
			return $this->hasMany(DepositoPlanilla::className(), ['recibo' => 'recibo']);
		}



		/**
		 * Relacion con la entidad "estatus".
		 * @return active record
		 */
		public function getCondicion()
		{
			return $this->hasOne(EstatusDeposito::className(), ['estatus' => 'estatus']);
		}



		/**
		 * Relacion con la entidad "contribuyentes"
		 * @return
		 */
		public function getContribuyente()
		{
			return $this->hasOne(ContribuyenteBase::className(), ['id_contribuyente' => 'id_contribuyente']);
		}



		/***/
		public function getDescripcionContribuyente($idContribuyente)
		{
			return $descripcion = ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
		}


		/**
		 * Metodo que genera un numero de proceso a partir de la fecha y hora especifica.
		 * Si recibe una fecha hora tomara esta, sino se determinara una fecha hora.
		 * @param string $fechaHora Fecha y hora
		 * @return string retorna un numero de proceso.
		 */
		public function getNumeroProceso($fechaHora = '')
		{
			$numeroProceso = '0';
			if ( trim($fechaHora) == '' ) {
				$fechaHora = date('Y-m-d h:i:s');
			}
			$dia = trim(date('d', strtotime($fechaHora)));
			$mes = trim(date('m', strtotime($fechaHora)));
			$año = trim(date('y', strtotime($fechaHora)));
			$hora = trim(date('h', strtotime($fechaHora)));
			$minuto = trim(date('i', strtotime($fechaHora)));
			$segundo = trim(date('s', strtotime($fechaHora)));

			$numeroProceso = $año . $mes . $dia . $hora . $minuto . $segundo;
			return $numeroProceso;
		}

	}

?>