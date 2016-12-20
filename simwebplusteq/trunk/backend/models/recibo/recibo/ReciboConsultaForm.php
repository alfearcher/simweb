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
 *  @file ReciboForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @class ReciboForm
 *  @brief Clase Modelo del formulario para
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
	* 	Clase
	*/
	class ReciboConsultaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $recibo;
		public $estatus;
		public $id_contribuyente;



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
	        	[['fecha_desde', 'fecha_hasta',
	        	  'estatus', 'recibo'], 'required',],
	        	[['recibo', 'estatus', 'id_contribuyente'],
	        	  'integer',
	        	  'message' => Yii::t('backend', 'valor no valido')],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'date',
	        	  'format' => 'php:d-m-Y',
	        	  'message' => Yii::t('backend', 'fecha no valida')],
	        	['fecha_hasta',
    			 'compare',
    			 'compareAttribute' => 'fecha_desde',
    			 'operator' => '>=',
    			 'message' => Yii::t('backend', 'El rango de fecha no es valido')],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'fecha_desde' => Yii::t('backend', 'Desde'),
	        	'fecha_hasta' => Yii::t('backend', 'Hasta'),
	        	'estatus' => Yii::t('backend', 'Condicion'),
	        	'recibo' => Yii::t('backend', 'Nro. Recbo'),
	        ];
	    }




	    /***/
		public function searchDeposito($params = '')
		{
			$query = Deposito::find();

			$dataProvider = New ActiveDataProvider([
									'query' => $query,
				]);

			$query->where('id_contribuyente =:id_contribuyente',
									[':id_contribuyente' => $this->id_contribuyente]);
			if ( $this->recibo > 0 ) {
				$query->andWhere('recibo =:recibo',[':recibo' => $this->recibo]);

			} elseif ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' ) {
				$this->fecha_desde = self::formatFecha($this->fecha_desde);
				$this->fecha_hasta = self::formatFecha($this->fecha_hasta);

				$query->andWhere(['BETWEEN', 'fecha', $this->fecha_desde, $this->fecha_hasta])
				      ->andWhere('R.estatus =:estatus',[':estatus' => $this->estatus]);
			}
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






		/***/
		public function formatFecha($fecha)
		{
			return date('Y-m-d', strtotime($fecha));
		}


	}
?>