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
 *  @file PlanillaConsultaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @class PlanillaConsultaForm
 *  @brief Clase Modelo que permite realizar las consultas de las planillas liquidadas pendientes de un
 * contribuyente y de sus objetos relacionados.
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

	namespace backend\models\planilla\consulta;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\Collapse;
	use backend\models\inmueble\InmueblesConsulta;
	use backend\models\vehiculo\VehiculosForm;
	use backend\models\propaganda\Propaganda;
	use common\models\deuda\DeudaSearch;
	use yii\helpers\ArrayHelper;


	/**
	* 	Clase
	*/
	class PlanillaConsultaForm extends Model
	{

		public $id_contribuyente;
		public $impuesto;
		public $id_impuesto;



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
	        	[['id_contribuyente', 'impuesto',
	        	  'id_impuesto',],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['id_contribuyente'],
	        	  'required', 'message' => '{attribute} is required'],
	        	[['usuario'], 'default', 'value' => Yii::$app->identidad->getUsuario()],
	        	[['fecha_hora'], 'default', 'value' => date('Y-m-d H:i:s')],
	        	[['id_contribuyente'], 'default', 'value' => $_SESSION['idContribuyente']],

	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'impuesto' => Yii::t('backend', 'Impuestos'),
	        	'id_impuesto' => Yii::t('backend', 'Id. Objeto'),
	        ];
	    }




	    /**
	     * Metodo que genera un GridView segun el provider enviada
	     * @param  ArrayDataProvider $provider array data provider.
	     * @return GridView retorna una GridView con la informacion indicada.
	     */
	    public function generarGridPlanilla($provider)
	    {
	    	return GridView::widget([
	         			'id' => 'grid-lista-planilla',
	               		'dataProvider' => $provider,
	               		'headerRowOptions' => ['class' => 'danger'],
	               		'tableOptions' => [
            				'class' => 'table table-hover',
      					],
	               		'summary' => '',
	               		'columns' => [
	               		 	['class' => 'yii\grid\SerialColumn'],
	               		 	[
	               		 		'contentOptions' => [
		                              'style' => 'font-size: 100%;',
		                        ],
	                            'label' => 'Planilla',
	                            'format' => 'raw',
	                            'value' => function($data) {
	                            	return Html::a($data['planilla'], '#', [
																'id' => 'link-view-planilla',
													            'class' => 'btn btn-success',
													            'data-toggle' => 'modal',
													            'data-target' => '#modal',
													            'data-url' => Url::to(['view-planilla', 'p' => $data['planilla']]),
													            'data-planilla' => $data['planilla'],
													            'data-pjax' => '0',
													        ]);

	                            },
	                        ],
	                        [
	                        	'contentOptions' => [
		                              'style' => 'font-size: 90%;',
		                        ],
	                        	'label' => 'Observacion',
	                        	'value' => function($data) {
	                        		return $data['observacion'];
	                        	}
	                        ],

	                        [
	               		 		'contentOptions' => [
		                              'style' => 'font-size: 100%;',
		                        ],
	                            'label' => 'Imprimir',
	                            'format' => 'raw',
	                            'value' => function($data) {
	                            		return Html::submitButton($data['planilla'],
                            													[
																					'id' => 'print-view-planilla',
												            						'class' => 'btn btn-primary',
												            						'target' => '_blank',
												            						'data' => [
												            							'method' => 'post',
												            							'params' => [
												            								'p' => $data['planilla'],
												            							],
												            						],
												            					]);
	                            },
	                        ],

	               		],
	               ]);
	    }



	    /***/
	    public function generarProviderDeudaPlanillaPorImpuesto($impuesto, $idImpuesto, $tipoPeriodo)
	    {
	    	$deudaSearch = New DeudaSearch($this->id_contribuyente);
	    	return $deudaSearch->getProviderDeudaPlanillaPorImpuesto($impuesto, $idImpuesto, $tipoPeriodo);
	    }



	    /***/
	    public function generarCollapsePlanilla($impuesto, $tipoPeriodo = '>=')
	    {
	    	$deudaSearch = New DeudaSearch($this->id_contribuyente);
	    	$planillas = $deudaSearch->getPlanillaConDeudaPorImpuesto($impuesto, $tipoPeriodo);


	    }



	    /***/
	    public function generarCollapsePlanillaPeriodoCero($impuesto)
	    {
	    	$deudaSearch = New DeudaSearch($this->id_contribuyente);
	    	$provider = $deudaSearch->getProviderDeudaPlanillaPorImpuesto($impuesto, 0, $tipoPeriodo = '=');

	    	if ( $provider !== null ) {
	    		return self::generarGridPlanilla($provider);
	    	}
	    	return null;
	    }





	    /***/
	    public function generarCollapsePlanillaPeriodoMayorCero($impuesto)
	    {
	    	$deudaSearch = New DeudaSearch($this->id_contribuyente);
	    	$provider = $deudaSearch->getProviderDeudaPlanillaPorImpuesto($impuesto, 0, $tipoPeriodo = '>');

	    	if ( $provider !== null ) {
	    		return self::generarGridPlanilla($provider);
	    	}
	    	return null;
	    }





	    /**
	     * Metodo que genera un widget collapse poe impuesto
	     * @return [type] [description]
	     */
	    public function generarCollapseDeuda()
	    {
	    	$collapse = null;
	    	$deudaSearch = New DeudaSearch($this->id_contribuyente);
	    	$listaImpuesto = $deudaSearch->getImpuestoConDeuda();

	    	if ( count($listaImpuesto) > 0 ) {
	    		foreach ( $listaImpuesto as $impuesto => $value ) {
	    			$item[] = [
	    				'label' => $value,
	    				//'content' => (string)$value,
	    				'content' => [
	    					(string)self::generarCollapseObjeto($impuesto),
	    					(string)self::generarCollapsePlanillaPeriodoCero($impuesto),
	    				]
	    			];
	    		}
    			$collapse = Collapse::widget([
    								'items' => $item
    						]);

	    	}

	    	return $collapse;

	    }






	    /**
	     * Metodo que genera un widget collapse por cada objeto
	     * @param  [type] $impuesto [description]
	     * @return [type]           [description]
	     */
	    public function generarCollapseObjeto($impuesto)
	    {

	    	if ( $impuesto == 1 ) {
	    		return self::generarCollapsePlanillaPeriodoMayorCero(1);

	    	} elseif ( ( $impuesto == 2 || $impuesto == 12 ) ) {
	    		// Se buscan los datos del inmueble.
	    		$models = InmueblesConsulta::find()->where('id_contribuyente =:id_contribuyente',
	    													[':id_contribuyente' => $this->id_contribuyente])
	    				  						   ->andWhere('inactivo =:inactivo',[':inactivo' => 0])
	    										   ->asArray()
	    										   ->all();

	    		$content = [];
	    		if ( count($models) > 0 ) {

	    			$content = ArrayHelper::map($models, 'id_impuesto', 'direccion');
	    			foreach ( $content as $key => $value ) {
	    				$provider = self::generarProviderDeudaPlanillaPorImpuesto($impuesto, $key, '>');
  						if ( $provider !== null ) {
	    					$contentGrid = self::generarGridPlanilla($provider);
	    				} else {
	    					$contentGrid = '';
	    				}

	    				$item[] = [
	    					'label' => $value,
	    					'content' => (string)$contentGrid,

	    				];
	    			}

	    			return Collapse::widget([
	    						'items' => $item
	    				]);

	    		}
	    	} elseif ( $impuesto == 3 ) {
	    		// Se buscan los datos del inmueble.
	    		$models = VehiculosForm::find()->where('id_contribuyente =:id_contribuyente',
	    													[':id_contribuyente' => $this->id_contribuyente])
	    				  						   ->andWhere('status_vehiculo =:status_vehiculo',
	    				  						   			[':status_vehiculo' => 0])
	    										   ->asArray()
	    										   ->all();

	    		$content = [];
	    		if ( count($models) > 0 ) {

	    			$content = ArrayHelper::map($models, 'id_vehiculo', 'placa');
	    			foreach ( $content as $key => $value ) {
	    				$provider = self::generarProviderDeudaPlanillaPorImpuesto($impuesto, $key, '>');
  						if ( $provider !== null ) {
	    					$contentGrid = self::generarGridPlanilla($provider);
	    				} else {
	    					$contentGrid = '';
	    				}

	    				$item[] = [
	    					'label' => $value,
	    					'content' => (string)$contentGrid,

	    				];
	    			}

	    			return Collapse::widget([
	    						'items' => $item
	    				]);

	    		}


	    	} elseif ( $impuesto == 4 ) {

	    	}
	    }



	    /***/
	    public function crearContentCollapse($label, $content)
	    {

	    	return Collapse::widget([
	    						'items' => [
	    							[
	    								'label' => $label,
	    								'content' => (string)$content,
	    							],
	    						]
	    				]);
	    }



	}

?>