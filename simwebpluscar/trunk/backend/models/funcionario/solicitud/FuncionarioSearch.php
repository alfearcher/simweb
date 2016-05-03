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
	use backend\models\configuracion\tiposolicitud\TipoSolicitudSearch;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\ImpuestoForm;
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
	    											 ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
	    											 ->andWhere('vigencia >:vigencia', [':vigencia' => date('Y-m-d')])
	    											 ->joinWith('funcionario')
	    											 ->orderBy([
	    											 		'apellidos' => SORT_ASC,
	    											 		'nombres' => SORT_ASC,
	    											 	]);

	    	return isset($modelFind) ? $modelFind : null;
	    }





	    /***/
	    public function findFuncionarioSolicitud()
	    {
	    	$modelFind = null;
	    	$modelFind = Funcionario::find()->distinct('id_funcionario')->where('vigencia >:vigencia', [':vigencia' => date('Y-m-d')])
	    									->joinWith('funcionarioSolicitud')
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
	    public function getDataProviderFuncionarioParaDesincorporar($tipoSolicitud = 0)
	    {
	    	if ( $tipoSolicitud > 0 ) {
	    		$query = $this->findSolicitudFuncionarios($tipoSolicitud);
	    	} else {
	    		$query = $this->findFuncionarioSolicitud();
	    	}
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
	    public function getInfoSolicitudImpuesto($tipoSolicitud)
	    {
	    	return TipoSolicitudSearch::getInfoImpuestoSegunSolicitud($tipoSolicitud);
	    }



	    /**
	     * Metodo que realiza la busqueda de los tipos de solicitudes relacionados
	     * a un funcionario. La relacion entre las entidades para obtener los resultados
	     * se realizan desde "funcionarios-solicitudes" hacia "config-tipos-solicitudes".
	     * @param  Long $idFuncionario identificador del funcionario.
	     * @return ActiveRecord.
	     */
	    public function findTipoSolicitudSegunFuncionario($idFuncionario)
	    {
	    	$modelFind = null;
	    	$modelFind = FuncionarioSolicitud::find()->where('id_funcionario =:id_funcionario', [':id_funcionario' => $idFuncionario])
	    	               							 ->joinWith('tipoSolicitud')
	    	               							 ->orderBy([
	    	               							 		'impuesto' => SORT_ASC,
	    	               							 		'descripcion' => SORT_ASC,
	    	               							 	]);
	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /**
	     * Metodo que permite obtener un dataprovider que puede ser utilizado en un
	     * gridview. Este gridview mostrara la lista de las solicitudes asociadas
	     * a un funcionario, el key del gridview sera el indice de la entidad
	     * "funcionarios-solicitudes".
	     * @param  Long $idFuncionario identificador del funcionario.
	     * @return ActiveDataProvider.
	     */
	    public function getDataProviderTipoSolicitudSegunFuncionario($idFuncionario)
	    {
	    	$query = $this->findTipoSolicitudSegunFuncionario($idFuncionario);
	    	$dataProvider = New ActiveDataProvider([
	    							'query' => $query,
	    	]);
	    	$query->andFilterWhere([
	    						FuncionarioSolicitud::tableName().'.inactivo' => 0,
	    						TipoSolicitud::tableName().'.inactivo' => 0,
	    						// ['like', 'apellidos', $this->searchGlobal],
	    						// ['like', 'nombres', $this->searchGlobal],

	    			]);
	    	return $dataProvider;
	    }



	    /**
	     * Metodo que entrega el apellido y nombre de un funcionario, segun el
	     * identificador del funcionario.
	     * @param  Long $id identificador del funcionario.
	     * @return Array Retorna un arreglo donde los indices (key) del arreglo
	     * corresponde a "apellidos" y "nombres". El valor de los elementos
	     * corresponde a los datos respectivos.
	     */
	    public function getFuncionarioSegunId($id)
	    {
	    	$model = New FuncionarioForm();
	    	return $model->getFuncionarioSegunId($id);
	    }




	    /**
	     * Metodo que permite obetene los identificadores de los tipos de solictudes
	     * asignadas a un funcionario. El paramatro de busqueda utilizado es el login
	     * (nombre de usuario) del funcionario.
	     * @param  String $userLocal usuario del funcionoario, establecido en la entidad
	     * principal "funcionarios".
	     * @return Array Retorna un arreglo de identificadores del tipo de solicitud
	     * asignada al funcionario. Esta relacion se determina en la entidad "funcionarios-solicitudes".
	     */
	    public function findIdTipoSolicitudSegunFuncionario($userLocal)
	    {
	    	$model = FuncionarioSolicitud::find()->select('tipo_solicitud')
	    	                                     ->distinct('tipo_solicitud')
	    	                                     ->where('inactivo =:inactivo', [':inactivo' => 0])
	    	                                     ->andWhere('login =:login', [':login' => $userLocal])
	    	                                     ->joinWith('funcionario')
	    	                                     ->orderBy([
	    	                                     		'tipo_solicitud' => SORT_ASC,
	    	                                     	])
	    	                                     ->asArray()
	    	                                     ->all();

	    	return count($model) > 0 ? $model : null;
	    }




	}
?>