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
	use backend\models\recibo\recibo\AnularRecibo;




	/**
	* Clase
	*/
	class AnularReciboForm extends AnularRecibo
	{
		public $recibo;
		public $id_contribuyente;
		public $estatus;
		// public $nro_solicitud;



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
	        	[['usuario'], 'default', 'value' => Yii::$app->identidad->getUsuario()],

	        	// [['nro_solicitud'], 'integer'],
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



		/**
		 * Metodo que genera el data provider para las planillas que estan contenidas
		 * en el recibo.
		 * @return Active Data Provider
		 */
		public function searchDepositoPlanilla()
		{
			$query = DepositoPlanilla::find();

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->where('id_contribuyente =:id_contribuyente',
									[':id_contribuyente' => $this->id_contribuyente]);
			if ( $this->recibo > 0 ) {
				$query->andWhere('DP.recibo =:recibo',[':recibo' => $this->recibo]);
			}

			$query->alias('DP')
				  ->joinWith('deposito R', true, 'INNER JOIN')
				  ->joinWith('condicion C', true, 'INNER JOIN');

			return $dataProvider;

		}



		/**
		 * Metodo que realiza la consulta del recibo y devielve una instancio de la
		 * clase Deposito.
		 * @return Deposito retorna una instancia de la clase Deposito
		 */
		public function findDeposito()
		{
			$deposito = New Deposito();

			return $deposito->find()->where('recibo =:recibo',[':recibo' => $this->recibo])
							        ->joinWith('condicion C', true)
							        ->one();
		}



		/***/
		public function findListaDeposito($listaRecibo)
		{
			if ( is_array($listaRecibo) ) {
				$deposito = New Deposito();

				return $deposito->find()
							    ->where(['IN', 'recibo', $listaRecibo])
								->joinWith('condicion C', true)
								->all();
			}
		}




		/**
	     * Metodo que permite localizar los recibos anulados
	     * @param array $listaRecibo arreglo de recibos que se desea consultar.
	     * @return data provider.
	     */
		public function searchListaDepositoAnulado($listaRecibo)
		{
			$query = Deposito::find();

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->where(['IN', 'recibo', $listaRecibo])
				  ->andWhere('R.estatus =:estatus',
				  					[':estatus' => 9]);

			$query->alias('R')
				  ->joinWith('condicion', true, 'INNER JOIN');

			return $dataProvider;

		}






		/***/
		public function searchSolicitud($listaRecibo)
		{
			$query = self::findSolicitudAnulacionRecibo($listaRecibo);

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->alias('A')
				  ->joinWith('estatusSolicitud', true);

			return $dataProvider;
		}



		/***/
		public function findSolicitudAnulacionRecibo($listaRecibo)
		{
			return $findModel = AnularRecibo::find()->where(['IN', 'recibo', $listaRecibo]);
		}


	}
?>