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
 *  @file ListaFuncionarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-07-2015
 *
 *  @class ListsFuncionarioForm
 *  @brief Clase Modelo
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *
 *
 *  @inherits
 *
 */


	namespace backend\models\funcionario;

	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\funcionario\Funcionario;



	/**
	 * Clase que gestiona la lista de los funcionarios
	 */
	class ListaFuncionarioForm extends Funcionario
	{

	    //public $id_funcionario;
	    public $ci;
	    public $apellidos;
	    public $nombres;


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
	            [['ci'], 'integer'],
	            [['celular', 'apellidos', 'nombres' ], 'string'],

	        ];
	    }


	    /***/
	    public function search($params)
   		{

	        $query = Funcionario::find();

	        $dataProvider = new ActiveDataProvider([
	            'query' => $query,
	        ]);

	        $this->load($params);

	        if (!$this->validate()) {
	            // uncomment the following line if you do not want to return any records when validation fails
	            // $query->where('0=1');
	            return $dataProvider;
	        }


	        $query->andFilterWhere(['like', 'id_funcionario', $this->id_funcionario])
	              ->andFilterWhere(['like', 'ci', $this->ci])
	              ->andFilterWhere(['like', 'apellidos', $this->apellidos])
	              ->andFilterWhere(['like', 'nombres', $this->nombres]);


	        return $dataProvider;
    	}


	}
?>