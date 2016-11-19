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
 *  @file AnularReciboForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-11-2016
 *
 *  @class AnularReciboForm
 *  @brief Clase Modelo del formulario para la solicitud de anulacion de los recibos de pagos
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

	namespace backend\models\recibo\recibo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;




	/**
	* Clase
	*/
	class AnularReciboForm extends Model
	{
		public $recibo;
		public $id_contribuyente;
		public $estatus;




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
			return 'sl_anulaciones_recibos';
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
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'recibo', 'estatus'],
	        	  'integer', 'message' => Yii::t('backend', 'Valor no valido')],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }



	     /**
	      * Metodo que permite localizar los recibos que se encuentran pendientes.
	      * @return data provider.
	      */
		public function searchListaDeposito()
		{
			$query = Deposito::find();

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->where('id_contribuyente =:id_contribuyente',
									[':id_contribuyente' => $this->id_contribuyente])
				  ->andWhere('R.estatus =:estatus',
				  					[':estatus' => $this->estatus]);

			$query->alias('R')
				  ->joinWith('condicion', true, 'INNER JOIN');

			return $dataProvider;

		}



		/***/
		public function searchDepositoPlanilla($recibo)
		{
			$query = DepositoPlanilla::find();

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->where('id_contribuyente =:id_contribuyente',
									[':id_contribuyente' => $this->id_contribuyente]);
			if ( $recibo > 0 ) {
				$query->andWhere('DP.recibo =:recibo',[':recibo' => $recibo]);
			}

			$query->alias('DP')
				  ->joinWith('deposito R', true, 'INNER JOIN')
				  ->joinWith('condicion C', true, 'INNER JOIN');

			return $dataProvider;

		}



	}
?>