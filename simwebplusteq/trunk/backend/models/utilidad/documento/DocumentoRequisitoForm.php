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
 *  @file DocumentoRequisitoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-098-2015
 *
 *  @class DocumentoRequisistoForm
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

	namespace backend\models\utilidad\documento;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;

	/**
	* 	Clase base del formulario
	*/
	class DocumentoRequisitoForm extends  DocumentoRequisito
	{
		public $id_documento;
		public $impuesto;
		public $descripcion;
		public $inactivo;



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [

	        ];
	    }




	    /**
	     * Metodo que devuelde un dataProvider
	     * @param $impuesto, integer que identifica al impuesto.
	     * @return retorna un dataProvider
	     */
	    public function getDataProviderDocumentosRequisitosSegunImpuesto($impuesto = 0)
	    {
	    	if ( $impuesto == 0 ) {
		    	$query = DocumentoRequisito::find();
	    		$dataProvider = new ActiveDataProvider([
	        		'query' => $query,
	    		]);
	    	} else {
	    		$query = DocumentoRequisito::find()->where(['impuesto' => $impuesto, 'inactivo' => 0])->orderBy('descripcion');
	    		$dataProvider = new ActiveDataProvider([
	        		'query' => $query,
	    		]);
	    	}

	    	return $dataProvider;
	    }


	}
?>