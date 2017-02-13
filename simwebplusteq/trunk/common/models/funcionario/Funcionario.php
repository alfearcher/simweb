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

	namespace common\models\funcionario;

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



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



    	/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario _form
    	 */
	    public function rules()
	    {
	        return [
	            [['naturaleza','ci','email','id_departamento',
	              'id_unidad','cargo', 'apellidos', 'nombres',
	              'niveles_nivel', 'fecha_inicio', 'vigencia',
	              'id_funcionario'],
	              'required', 'message' => '{attribute} is required'],
	            [['ci', 'id_departamento', 'id_unidad', 'id_funcionario'], 'integer'],
	            [['login', 'clave11', 'cargo'], 'string'],
	            ['email', 'email'],
	            ['email', 'filter','filter'=>'strtolower'],
	            // ['ci', 'unique', 'when' => function($model) {
	            // 								if ( !$model->isNewRecord ) {
	            // 									if ( $model->id_funcionario !== $this->id_funcionario ) {
	            // 										return true;
	            // 									}
	            // 								}
	            // }],
	            [['celular'], 'string'],
	            [['login', 'clave11'], 'default', 'value' => null],
	            [['status_funcionario', 'niveles_nivel', 'en_uso'], 'default', 'value' => 0],
	            ['entes_ente', 'default', 'value' => Yii::$app->ente->getEnte()],
	            [['fecha_fin', 'vigencia'], 'default', 'value' => date('Y-m-d', strtotime('0000-00-00'))],
	            ['fecha_inclusion', 'default', 'value' => date('Y-m-d')],

	        ];
	    }



	}

?>