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
 *  @file HistoricoDeclaracionSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com
 *
 *  @date 26-01-2016
 *
 *  @class HistoricoDeclaracionSearch
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

	namespace backend\models\aaee\historico\declaracion;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\historico\declaracion\HistoricoDeclaracion;
	use common\models\numerocontrol\NumeroControlSearch;

	/**
	* 	Clase
	*/
	class HistoricoDeclaracionSearch extends HistoricoDeclaracion
	{
		private $_id_contribuyente;



		/**
		 * Metodo constuctor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}



		/**
		 * Motodo que crea un modelo de consulta.
		 * @return active record de la clase HistoricoDeclaracion
		 */
		private function findHistoricoDeclaracionModel()
		{
			$findModel = HistoricoDeclaracion::find()->where('id_contribuyente =:id_contribuyente',
																[':id_contribuyente' => $this->_id_contribuyente]);

			return $findModel;
		}



		/***/
		public function findHistoricoDeclaracionSegunSolicitud($nroSolicitud)
		{
			$findModel = self::findHistoricoDeclaracionModel();
			$model = $findModel->andWhere('nro_solicitud =:nro_solicitud',
										[':nro_solicitud' => $nroSolicitud])
							   ->joinWith('tipoDeclaracion', true, 'INNER JOIN')
							   ->all();

			return ( count($model) > 0 ) ? $model : [];
		}



		/***/
		public function findHistoricoDeclaracion($idHistorico)
		{
			$findModel = self::findHistoricoDeclaracionModel();
			$model = $findModel->andWhere('id_historico =:id_historico',
										[':id_historico' => $idHistorico])
							   ->joinWith('tipoDeclaracion', true, 'INNER JOIN')
							   ->one();

			return ( count($model) > 0 ) ? $model : [];
		}




		/***/
		public function findHistoricoDeclaracionSegunNroControl($nroControl)
		{
			$findModel = self::findHistoricoDeclaracionModel();
			$model = $findModel->andWhere('nro_control =:nro_control',
										[':nro_control' => $nroControl])
							   ->joinWith('tipoDeclaracion', false, 'INNER JOIN')
							   ->all();

			return ( count($model) > 0 ) ? $model : [];
		}



		 /**
		  * Metodo que realiza el salvado del registro en la entidad respectiva.
		  * Al guardar debe gemerar el identificador del registro y colocarlo en
		  * un arreglo. El arreglo tiene la estructura:
		  * [
		  * 	'r' => "null" o "new",
		  * 	'id' => 0 o el identificador generado
		  * ]
		  * Si 'id' = 0, significa que no se genero e; identificador lo que indica
		  * que no se pudo guardar el registro.
		  * @param  array $arregloDatos arreglo de los datos que se guardaran en la
		  * entidad. Tiene la siguiente estructura:
		  * [
		  * 	'campo o atributo' => valor a guardar
		  * ]
		  * @param  conexioncontroller $conexion instancia de conexion.
		  * @param  [type] $conn         [description]
		  * @return array retorna un arreglo con dos elementos segun la estructura
		  * [
		  * 	'r' => "null" o "new",
		  * 	'id' => 0 o el identificador generado
		  * ]
		  */
	    public function guardar($arregloDatos, $conexion, $conn)
	    {
	    	$result = [
	    		'r' => null,
	    		'id' => 0,
	    	];
	    	$prefijo = [
	    		1 => 'DE',
	    		2 => 'DD',
	    	];
	    	$resultado = false;
	    	$id = 0;
	    	$model = New HistoricoDeclaracion();
	    	$tabla = $model->tableName();

	    	// Se inicializa un contador de oportunidades para generar numero control
	    	$i = 0;
	    	$maximo = 10;
	    	$nroControl = 0;

	    	// Se genera el numero de control
	    	$control = New NumeroControlSearch();
	    	while ( $i <= $maximo ) {
	    		$nroControl = $control->generarNumeroControl();
	    		if ( $nroControl > 0 ) {
	    			break;
	    		}
	    		$i++;
	    	}


	    	if ( $nroControl > 0 ) {
		    	foreach ( $model->attributes as $key => $value ) {
		    		if ( isset($arregloDatos[$key]) ) {
		    			$model[$key] = $arregloDatos[$key];
		    		} else {
		    			$model[$key] = 0;
		    		}
		    	}


		    	$model['id_historico'] = null;
		    	$model['nro_control'] = $nroControl;
		    	$campos = [
		    		'id_impuesto' => $model['id_impuesto'],
		    		'ano_impositivo' => $model['ano_impositivo'],
		    		'periodo' => $model['periodo'],
		    		'id_contribuyente' => $model['id_contribuyente'],
		    		'nro_control' => $nroControl,
		    	];

		    	// Se obtiene los atributos que formaran el serial de control, con sus respectiva
		    	// configuracion de formacion.
		    	$configSerial = self::getArregloConfigSerial($campos);

		    	// Se arma el serial de cada atributo.
		    	$seriales = self::generarSerialControl($configSerial);

		    	// Se arma todo el serial control.
		    	$model['serial_control'] = $prefijo[$model['tipo_declaracion']] . '-' . self::getSerialControl($seriales);

		    	$resultado = $conexion->guardarRegistro($conn, $tabla, $model->attributes);
				if ( $resultado ) {
					$id = $conn->getLastInsertID();
					$result = [
						'r' => 'new',
						'id' => $id,
					];
				}
			}

	    	return $result;
	    }



	    /**
	     * Metodo que permite generar un serial de control, tomando un arreglo multidimensional
	     * con la estructura:
	     * [descripcion del campo] => {
	     * 			['valor'] => valor numerico,
	     *    		['prefix'] => entero, que indica la cantidad de digitos a tomar a la izquieda.
	     * }
	     * @param array $configCampos arreglo multidimensiona;
	     * @return array retorna un arreglo donde el indice del arreglo es la descripcion
	     * del campo, y el valor del elemento es el codigo serial creado para ese campo.
	     */
	    public function generarSerialControl($configCampos)
	    {
	    	$agregado = '000000';
	    	$lenAgregado = strlen($agregado);

	    	// Arreglo de campos con los ceros agregados a la izquierda.
	    	$seriales = [];
	    	foreach ( $configCampos as $key => $value ) {
	    		$preSerial = $agregado . trim($value['valor']);
	    		$seriales[$key] = substr($preSerial, -($value['prefix']));
	    	}

	    	return $seriales;

	    }




	    /***/
	    protected function getSerialControl($seriales)
	    {
	    	 return $seriales['id_impuesto'] . '-' . $seriales['ano_impositivo'] . '-' .
	    	 		$seriales['periodo'] . '-' . $seriales['id_contribuyente'] . '-' .
	    	 		$seriales['nro_control'];

	    	 // return $seriales['id_contribuyente'] . '-' .
	  				// $seriales['nro_control'];
	    }


	    /***/
	    protected function getArregloConfigSerial($campos)
	    {
	    	return $seriales = [
			    		'id_impuesto' => [
			    				'valor' => $campos['id_impuesto'],
			    				'prefix' => 6,
			    		],
			    		'ano_impositivo' => [
			    				'valor' => $campos['ano_impositivo'],
			    				'prefix' => 2,
			    		],
			    		'periodo' => [
			    				'valor' => $campos['periodo'],
			    				'prefix' => 2,
			    		],
			    		'id_contribuyente' => [
			    				'valor' => $campos['id_contribuyente'],
			    				'prefix' => 4,
			    		],
			    		'nro_control' => [
			    				'valor' => $campos['nro_control'],
			    				'prefix' => 6,
			    		],
			    	];
	    }


	}

?>