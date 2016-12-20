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
 *  @file Funcionario.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-07-2015
 *
 *  @class Funcionario
 *  @brief Clase Modelo de la entidad funcionarios.
 *
 *
 *  @property
 *
 *
 *  @method
 *  getDb
 * 	tableName
 *
 *  @inherits
 *
 */

	namespace backend\models\funcionario;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\funcionario\calcomania\FuncionarioCalcomania;
	use backend\models\utilidad\departamento\Departamento;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamento;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;

	/**
	* 	Clase base del modulo de funcioario.
	*/
	class Funcionario extends ActiveRecord
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
			return 'funcionarios';
		}




		/**
		 * Relacion con la entidad "funcionario-calcomania".
		 * @return ActiveRecord.
		 */
		public function getCalcomania()
		{
			return $this->hasMany(FuncionarioCalcomania::className(),['id_funcionario' => 'id_funcionario']);
		}


		/**
		 * Relacion con la entidad "departamentos"
		 * @return [type] [description]
		 */
		public function getDepartamento()
		{
			return $this->hasOne(Departamento::className(), ['id_departamento' => 'id_departamento']);
		}



		/**
		 * Relacion con la entidad "unidades-departamentos".
		 * @return [type] [description]
		 */
		public function getUnidad()
		{
			return $this->hasOne(UnidadDepartamento::className(), ['id_unidad' => 'id_unidad']);
		}


		/**
		 * Relacion con la entidad "funcionario-calcomania"
		 * @return [type] [description]
		 */
		public function getFuncionarioCalcomania()
		{
			return $this->hasOne(FuncionarioCalcomania::className(),['id_funcionario' => 'id_funcionario']);
		}



		/**
		 * Relacion con la entidad "funcionarios-solicitudes"
		 * @return Active Record
		 */
		public function getFuncionarioSolicitud()
		{
			return $this->hasMany(FuncionarioSolicitud::className(), ['id_funcionario' => 'id_funcionario']);
		}

	}

?>