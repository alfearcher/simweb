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
 *  @file FuncionarioCalcomania.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/04/2016
 * 
 *  @class FuncionarioCalcomania
 *  @brief Clase que extiende de active records y apunta a la tabla funcionario_calcomania.
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

	namespace backend\models\funcionario\calcomania;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	//use backend\FuncionarioForm;

	/**
	* 	Clase base del modulo de funcioario.
	*/
	class FuncionarioCalcomania extends ActiveRecord 
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
			return 'funcionario_calcomania';
		}

	}

?>