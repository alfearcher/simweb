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
 *  @file FuncionarioSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-04-2016
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


	namespace backend\models\funcionario\solicitud;

	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\funcionario\FuncionarioForm;
	use backend\models\funcionario\Funcionario;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use yii\data\ActiveDataProvider;


	/**
	 *	Clase principal del formulario.
	 */
	class FuncionarioSearch extends Model
	{

	    public $id_departamento;
	    public $id_unidad;
	    public $searchGlobal;
	    public $listado;
	    public $errListaFuncionario;
	    public $errListaSolicitud;
	    public $impuesto;
	    public $tipo_solicitud;

	    const SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD = 'search_departamento';
		const SCENARIO_SEARCH_GLOBAL = 'search_global';
		const SCENARIO_DEFAULT = 'default';
		const SCENARIO_SEARCH_IMPUESTO_SOLICITUD = 'search_impuesto_solicitud';

    	/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD => [
								'id_departamento',
								'id_unidad'
        		],
        		self::SCENARIO_SEARCH_GLOBAL => [
        						'searchGlobal',
        		],
        		self::SCENARIO_SEARCH_IMPUESTO_SOLICITUD => [
        						'impuesto',
        						'tipo_solicitud'
        		],
        		self::SCENARIO_DEFAULT => [
        						'',
        		],
        	];
    	}



    	/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
	            [['id_departamento', 'id_unidad'],
	              'required', 'on' => 'search_departamento', 'message' => Yii::t('backend', '{attribute} is require')],
	            [['searchGlobal'],
	              'required', 'on' => 'search_global', 'message' => Yii::t('backend', '{attribute} is require')],
	            ['listado', 'safe'],
	            [['id_departamento', 'id_unidad', 'impuesto', 'tipo_solicitud'],
	              'integer'],
	            [['errListaFuncionario', 'errListaSolicitud'], 'string'],
	            [['impuesto', 'tipo_solicitud'],
	              'required', 'on' => 'search_impuesto_solicitud', 'message' => Yii::t('backend', '{attribute} is require')],
	        ];
	    }



	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	        	'id_departamento' => Yii::t('backend', 'Departamento'),
	            'id_unidad' => Yii::t('backend', 'Unidad'),
	            'searchGlobal' => Yii::t('backend', 'Search'),
	        ];
	    }



	     /***/
	    public function findFuncionarioPorDepartamentoUnidad($idDepartamento, $idUnidad)
	    {
	    	$modelFind = Funcionario::find()->andWhere(Funcionario::tableName().'.id_departamento =:id_departamento', [':id_departamento' => $idDepartamento])
	    	     							->andWhere(Funcionario::tableName().'.id_unidad =:id_unidad', [':id_unidad' => $idUnidad])
	    	     							->andWhere('vigencia >:vigencia', [':vigencia' => date('Y-m-d')])
	    	     							->joinWith('departamento')
	    	     							->joinWith('unidad')
	    	     							->orderBy([
	    	     									'apellidos' => SORT_ASC,
	    	     									'nombres' => SORT_ASC,
	    	     								]);
	    	return isset($modelFind) ? $modelFind : null;
	    }




	    /***/
	    public function findFuncionarioVigente()
	    {
	    	$modelFind = Funcionario::find()->where('vigencia >:vigencia', [':vigencia' => date('Y-m-d')])
	    									->joinWith('departamento')
	    	     							->joinWith('unidad')
	    	     							->orderBy([
	    	     									'apellidos' => SORT_ASC,
	    	     									'nombres' => SORT_ASC,
	    	     								]);
	    	//$modelFind = Funcionario::find();
	    	return isset($modelFind) ? $modelFind : null;
	    }





	    /***/
	    public function getDataProviderFuncionarioPorDepartamento($idDepartamento, $idUnidad)
	    {
	    	$query = $this->findFuncionarioPorDepartamentoUnidad($idDepartamento, $idUnidad);

	    	//$query = Funcionario::find();

	    	$dataProvider = New ActiveDataProvider([
	    						'query' => $query,
	    	]);

	    	if ( $this->searchGlobal != '' ) {
	    		$query->andFilterWhere([
	    						'or',
	    						['like', 'ci', $this->searchGlobal],
	    						['like', 'apellidos', $this->searchGlobal],
	    						['like', 'nombres', $this->searchGlobal],

	    			]);
	    	}

	    	return $dataProvider;
	    }




	    /***/
	    public function getDataProviderFuncionarioVigente()
	    {
	    	$query = $this->findFuncionarioVigente();

	    	$dataProvider = New ActiveDataProvider([
	    						'query' => $query,
	    	]);

	    	if ( $this->searchGlobal != '' ) {
	    		$query->andFilterWhere([
	    						'or',
	    						['like', 'ci', $this->searchGlobal],
	    						['like', 'apellidos', $this->searchGlobal],
	    						['like', 'nombres', $this->searchGlobal],

	    			]);
	    	}

	    	return $dataProvider;
	    }




	    /**
	     * Metodo que realiza una busqueda de los funcionarios relacionados a una
	     * solicitud a traves de la entidad "funcionarios-solicitudes". Utiliza como
	     * parametro de busqueda el tipo de solicitud.
	     * @param  Integer $tipoSolicitud identificador de la solicitud
	     * @return Active Record, Retorna un modelo con la informacion de la entidad "funcionarios-solicitudes"
	     * y la informacion del funcionarios estara un un array con el indice denominado "funcionario".
	     */
	    public function findSolicitudFuncionarios($tipoSolicitud)
	    {
	    	$modelFind = null;
	    	$modelFind = FuncionarioSolicitud::find()->where('tipo_solicitud =:tipo_solicitud', [':tipo_solicitud' => $tipoSolicitud])
	    											 ->andWhere(Funcionario::tableName().'.inactivo =:inactivo', [':inactivo' => 0])
	    											 ->joinWith('funcionario')
	    											 ->orderBy([
	    											 		'apellidos' => SORT_ASC,
	    											 		'nombres' => SORT_ASC,
	    											 	]);
	    	return isset($modelFind) ? $modelFind : null;
	    }





	    /**
	     * Metod que permite generar un dataprovider para la renderizacion de los resultados
	     * a traves de un gridview. El dataprovider contendra los datos de las entidades
	     * "funcionarios-solicitudes" y "funcionarios".
	     * @param  Integer $tipoSolicitud identificador del tipo de solicitud.
	     * @return ActiveDataProvider.
	     */
	    public function getDataProviderSolicitudFuncionario($tipoSolicitud)
	    {
	    	$query = $this->findSolicitudFuncionarios($tipoSolicitud);

	    	$dataProvider = New ActiveDataProvider([
	    						'query' => $query,
	    	]);

	    	if ( $this->searchGlobal != '' ) {
	    		$query->andFilterWhere([
	    						'or',
	    						['like', 'ci', $this->searchGlobal],
	    						['like', 'apellidos', $this->searchGlobal],
	    						['like', 'nombres', $this->searchGlobal],

	    			]);
	    	}

	    	return $dataProvider;	
	    }

	}
?>