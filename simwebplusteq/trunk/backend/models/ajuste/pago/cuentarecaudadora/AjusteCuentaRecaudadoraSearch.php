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
 *  @file AjusteCuentaRecaudadoraSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-05-2017
 *
 *  @class AjusteCuentaRecaudadoraSearch
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

	namespace backend\models\ajuste\pago\cuentarecaudadora;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositodetalle\DepositoDetalle;


	/**
	* Clase
	*/
	class AjusteCuentaRecaudadoraSearch extends Model
	{




		/**
		 * Metodo que permite generar el modelo de consulta principal sobre las entidades
		 * "depositos" y "depositos-detalle".
		 * @return DepositoDetalle
		 */
		public function findDepositoDetalleModel()
		{
			$findModel = DepositoDetalle::find()->alias('B')
												->joinWith('deposito A', true, 'INNER JOIN')
												->where('A.estatus =:estatus',
															[':estatus' => 1]);

			return $findModel;
		}



		/***/
		public function searchDepositoDetalle($params)
		{
			$query = self::findDepositoDetalleModel();
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);
			if ( $this->recibo > 0 ) {

			}
			$query->andFilterWhere([
		        'id'=>$this->id,
		    ]);

		}


		/**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return array
	     */
	    private function getListaFuncionarioAutorizado()
	    {
	    	return [
	    		'adminteq1',
	    		'kperez',
	    		'pfranco',
	    	];
	    }


	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario)
	    {
	    	$listaUsuarioAutorizado = self::getListaFuncionarioAutorizado();
	    	if ( count($listaUsuarioAutorizado) > 0 ) {
	    		foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    			if ( $value == $usuario ) {
	    				return true;
	    			}
	    		}
	    	}
	    	return false;
	    }

	}
?>