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
 *  @file AjustePreReferenciaBancariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-10-2017
 *
 *  @class AjustePreReferenciaBancariaForm
 *  @brief Clase Modelo del formulario que muestra el listado y las opciones de ajustes
 *  de las pre-referencias.
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

	namespace backend\models\ajuste\pago\prereferencia;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositodetalle\DepositoDetalle;
	use yii\helpers\ArrayHelper;
	use  backend\models\recibo\prereferencia\PreReferenciaPlanilla;
	use backend\models\utilidad\tipo\ajusteprereferencia\TipoAjustePreReferenciaBancaria;


	/**
	* Clase
	*/
	class AjustePreReferenciaBancariaForm extends Model
	{
		public $tipo_ajuste;
		public $nota_explicativa;

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}


    	/**
		 * Metodo que indica las reglas de validacion del formulario de consulta.
		 * @return array
		 */
	    public function rules()
	    {
	        return [
	        	[['tipo_ajuste', 'nota_explicativa'],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        ];
	    }


	    /***/
	    public function attributeLabels()
	    {
	        return [
	        	'tipo_ajuste' => Yii::t('backend', 'Tipo de Ajuste'),
	        ];
	    }


		/**
		 * Metodo que genera una lista de los registros para utilizarlos en un
		 * combo-lista. Entidad referente a los tipos de ajsutes sobre las pre-
		 * referencias bancarias.
		 * @return array
		 */
		public function listaTipoAjustePreReferencia()
		{
			return ArrayHelper::map(TipoAjustePreReferenciaBancaria::find()->asArray()->all(), 'tipo_ajuste', 'descripcion');
		}

	}
?>