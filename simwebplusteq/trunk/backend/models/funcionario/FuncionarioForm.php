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
 *  @file FuncionarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-07-2015
 *
 *  @class FuncionarioForm
 *  @brief Clase Modelo del formulario de creacion de funcionarios, mantiene las reglas de validacion
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
//use yii\db\ActiveRecord;
use backend\models\funcionario\Funcionario;
use common\conexion\ConexionController;

/**
 * This is the model class for table "funcionarios".
 *
 * @property string $id_funcionario
 * @property string $entes_ente
 * @property string $ci
 * @property string $apellidos
 * @property string $nombres
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $status_funcionario         //  1 => inactivo
 * @property string $en_uso
 * @property string $login
 * @property string $clave11
 * @property string $niveles_nivel
 * @property string $cargo
 * @property string $vigencia
 * @property string $id_departamento
 * @property string $id_unidad
 * @property string $email
 * @property string $celular
 * @property string $naturaleza
 */

	/**
	 *	Clase principal del formulario _form vista de funcionario.
	 */
	class FuncionarioForm extends Funcionario
	{

	    public $id_funcionario;
	    public $entes_ente;
	    public $ci;
	    public $apellidos;
	    public $nombres;
	    public $fecha_inicio;
	    public $fecha_fin;
	    public $status_funcionario;         //  1 => inactivo
	    public $en_uso;
	    public $login;
	    public $clave11;
	    public $niveles_nivel;
	    public $cargo;
	    public $vigencia;
	    public $id_departamento;
	    public $id_unidad;
	    public $email;
	    public $celular;
	    public $naturaleza;
	    public $fecha_inclusion;


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
	            [['naturaleza','ci','email','id_departamento',
	              'id_unidad','cargo', 'apellidos', 'nombres',
	              'niveles_nivel', 'fecha_inicio', 'vigencia'],
	              'required', 'message' => '{attribute} is required'],
	            [['ci', 'id_departamento', 'id_unidad'], 'integer'],
	            [['login', 'clave11', 'cargo'], 'string'],
	            ['email', 'email'],
	            ['email', 'filter','filter'=>'strtolower'],
	            ['ci', 'unique'],
	            [['celular'], 'string'],
	            [['login', 'clave11'], 'default', 'value' => null],
	            [['status_funcionario', 'niveles_nivel', 'en_uso'], 'default', 'value' => 0],
	            ['entes_ente', 'default', 'value' => Yii::$app->ente->getEnte()],
	            [['fecha_fin', 'vigencia'], 'default', 'value' => date('Y-m-d', strtotime('0000-00-00'))],
	            ['fecha_inclusion', 'default', 'value' => date('Y-m-d')],

	        ];
	    }




	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	            'id_funcionario' => Yii::t('backend', 'Id Funcionario'),
	            'entes_ente' => Yii::t('backend', 'Entes Ente'),
	            'ci' => Yii::t('backend', 'Identity Card Number'),
	            'apellidos' => Yii::t('backend', 'Last Name'),
	            'nombres' => Yii::t('backend', 'Name'),
	            'fecha_inicio' => Yii::t('backend', 'Date of Admission'),
	            'fecha_fin' => Yii::t('backend', 'End Start'),
	            'status_funcionario' => Yii::t('backend', 'Status Funcionario'),
	            'en_uso' => Yii::t('backend', 'En Uso'),
	            'login' => Yii::t('backend', 'Login'),
	            'clave11' => Yii::t('backend', 'Clave11'),
	            'niveles_nivel' => Yii::t('backend', 'User Level'),
	            'cargo' => Yii::t('backend', 'Post Office'),
	            'vigencia' => Yii::t('backend', 'Vality Date'),
	            'id_departamento' => Yii::t('backend', 'Departament'),
	            'id_unidad' => Yii::t('backend', 'Work Unit'),
	            'email' => Yii::t('backend', 'Email'),
	            'celular' => Yii::t('backend', 'Mobile Phone'),
	            'naturaleza' => Yii::t('backend', 'Nature'),
	        ];
	    }


	    /***/
	    public function findFuncionario($id)
	    {
	    	$model = Funcionario::findOne($id);
	    	return isset($model) ? $model : null;
	    }



	    /***/
	    public function getFuncionarioSegunId($id)
	    {
	    	$funcionario = null;
	    	$model = $this->findFuncionario($id);
	    	if ( $model != null ) {
	    		$funcionario['apellidos'] = $model->apellidos;
	    		$funcionario['nombres'] = $model->nombres;
	    	}
	    	return $funcionario;
	    }



	    public function search($params)
   		{


	        $query = Funcionario::find()->where(['id_funcionario' => $_SESSION['idFuncionario']]);
	        //$query = InmueblesUrbanosForm::find();

	        $dataProvider = new ActiveDataProvider([
	            'query' => $query,
	        ]);

	        $this->load($params);

	        if (!$this->validate()) {
	            // uncomment the following line if you do not want to return any records when validation fails
	            // $query->where('0=1');
	            return $dataProvider;
	        }

	        $query->andFilterWhere([

	        		'id_funcionario' => $this->id_funcionario,
		            'entes_ente' => $this->entes_ente,
		            'ci' => $this->ci,
		            'apellidos' => $this->apellidos,
		            'nombres' => $this->nombres,
		            'fecha_inicio' =>$this->fecha_inicio,
		            'fecha_fin' => $this->fecha_fin,
		            'status_funcionario' => $this->status_funcionario,
		            'en_uso' => $this->en_uso,
		            'login' => $this->login,
		            'clave11' => $this->clave11,
		            'niveles_nivel' => $this->niveles_nivel,
		            'cargo' => $this->cargo,
		            'vigencia' => $this->vigencia,
		            'id_departamento' => $this->id_departamento,
		            'id_unidad' => $this->id_unidad,
		            'email' => $this->email,
		            'celular' => $this->celular,
		            'naturaleza' => $this->naturaleza,


	        ]);

	        $query->andFilterWhere(['like', 'id_funcionario', $this->id_funcionario])
	            ->andFilterWhere(['like', 'ci', $this->ci])
	            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
	            ->andFilterWhere(['like', 'nombres', $this->nombres])
	            ->andFilterWhere(['like', 'status_funcionario', $this->status_funcionario]);


	        return $dataProvider;
    	}
	}
?>