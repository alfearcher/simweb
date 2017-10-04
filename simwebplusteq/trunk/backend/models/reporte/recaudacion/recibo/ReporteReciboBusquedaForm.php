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
 *  @file ReporteReciboBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-10-2017
 *
 *  @class ReporteReciboBusquedaForm
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

	namespace backend\models\reporte\recaudacion\recibo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\fecha\RangoFechaValido;
	use backend\models\recibo\deposito\Deposito;


	/**
	* Clase que gestiona la logica para la consulta que permitira la generacion
	* del reporte de recibos.
	*/
	class ReporteReciboBusquedaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $recibo;
		public $estatus;
		public $usuario;				// Quien pago el recibo.
		public $usuario_creador;		// Quien creo el recibo.

		private $rangoValido;

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
	        	  'recibo'],
	        	  'required',],
	        	[['recibo', 'estatus',],
	        	  'integer',
	        	  'message' => Yii::t('backend', 'valor no valido')],
	        	[['usuario', 'usuario_creador',],
	        	  'string'],
	        	[['usuario', 'usuario_creador',],
	        	  'safe'],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'date',
	        	  'format' => 'php:d-m-Y',
	        	  'message' => Yii::t('backend', 'fecha no valida')],
	        	['fecha_hasta',
    			 'compare',
    			 'compareAttribute' => 'fecha_desde',
    			 'operator' => '>=',
    			 'message' => Yii::t('backend', 'El rango de fecha no es valido')],
    			 ['fecha_desde' , 'validarRango'],
	        ];
	    }


	     /**
	     * Metodo que permite validar el rango de fecha.
	     * @return none
	     */
	    public function validarRango()
	    {
	    	$this->rangoValido = false;
	    	$validarRango = New RangoFechaValido($this->fecha_desde, $this->fecha_hasta);
	    	if ( !$validarRango->rangoValido() ) {
	    		$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    	} else {
	    		$this->rangoValido = true;
	    	}
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
	        	'usuario' => Yii::t('backend', 'Quíen Pago'),
	        	'usuario_creador' => Yii::t('backend', 'Quíen Creo'),
	        ];
	    }




	    /**
	     * Metodo para generar el data provider del reporte.
	     * @return ActiveDatProvider
	     */
		public function searchDeposito()
		{
			$query = Deposito::find();

			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => 100,
				],
			]);

			if ( $this->recibo > 0 ) {
				$query->where('recibo =:recibo',[':recibo' => $this->recibo]);

			} elseif ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' ) {
				$this->fecha_desde = self::formatFecha($this->fecha_desde);
				$this->fecha_hasta = self::formatFecha($this->fecha_hasta);

				$query->where(['BETWEEN', 'fecha', $this->fecha_desde, $this->fecha_hasta]);

				if ( strlen($this->estatus) > 0 ) {
					$query->andwhere('D.estatus =:estatus',
											[':estatus' => $this->estatus]);
				}

				if ( strlen($this->usuario) > 0 ) {
					$query->andwhere('usuario =:usuario',
											[':usuario' => $this->usuario]);
				}

				if ( strlen($this->usuario_creador) > 0 ) {
					$query->andwhere('usuario_creador =:usuario_creador',
											[':usuario_creador' => $this->usuario_creador]);
				}
			}
			$query->alias('D')
				  ->joinWith('condicion E', true, 'INNER JOIN');

			return $dataProvider;

		}




		/***/
		public function formatFecha($fecha)
		{
			return date('Y-m-d', strtotime($fecha));
		}


	}
?>