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
 *  @file ListadoSolicitudDeclaracion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-09-2016
 *
 *  @class ListadoSolicitudDeclaracion
 *  @brief Clase modelo de anexo de ramo
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

	namespace backend\models\aaee\listado;

 	use Yii;
	use yii\web\NotFoundHttpException;
	use backend\models\solicitud\estatus\EstatusSolicitud;
	use backend\models\aaee\declaracion\tipodeclaracion\TipoDeclaracion;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\DeclaracionBase;
	use yii\db\ActiveRecord;
	use yii\base\Model;
	use common\models\planilla\PagoDetalle;
	use yii\helpers\ArrayHelper;
	use yii\db\Query;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use common\models\contribuyente\ContribuyenteBase;

	/**
	* Clase que gestiona la emision de un listado de las solicitudes de las declaraciones.
	*/
	class ListadoSolicitudDeclaracion extends Model
	{

		public $fecha_desde;
		public $fecha_hasta;
		public $tipo_solicitud;
		public $nro_solicitud;
		public $impuesto;
		public $fecha_hora_creacion;
		public $estatus_solicitud;
		public $ordenar_declaracion;


		const SCENARIO_NRO_SOLICITUD = 'numero_solicitud';
		const SCENARIO_TIPO = 'tipo';
		const SCENARIO_DEFAULT = 'default';


		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
	        	self::SCENARIO_NRO_SOLICITUD => [
	        					'nro_solicitud',

	        		],
        		self::SCENARIO_TIPO => [
        						'impuesto',
	        					'tipo_solicitud',
	        					'fecha_desde',
	        					'fecha_hasta',
	        					'estatus_solicitud',
	        					'ordenar_declaracion',
        		],
        		self::SCENARIO_DEFAULT => [
        						Model::scenarios(),
        		],
	        ];
    	}




    	/***/
    	public function search($params)
    	{
    		return self::getDataProvider($params);

    	}



    	/***/
    	public function getDataProvider($params)
    	{
			$query = New Query();
			$rows = [];
    		if ( $this->ordenar_declaracion == 0 ) {

	    		$rows = $query->select('S.nro_solicitud,S.fecha_hora_creacion,
	    			                    S.id_contribuyente,D.ano_impositivo,
	    			                    T.descripcion,E.descripcion as condicion,
	    			                    D.tipo_declaracion,
	    			                    sum(D.monto_new) as suma')
	    				      ->from(SolicitudesContribuyente::tableName() . ' S')
	    				      ->join('INNER JOIN', DeclaracionBase::tableName() . ' D', 'S.nro_solicitud=D.nro_solicitud')
	    				      ->join('INNER JOIN', EstatusSolicitud::tableName() . ' E', 'S.estatus=E.estatus_solicitud')
	    				      ->join('INNER JOIN', TipoSolicitud::tableName() . ' T', 'S.tipo_solicitud=T.id_tipo_solicitud')
	    				      ->where(['BETWEEN', 'date(fecha_hora_creacion)',
	    				      		 						date('Y-m-d',strtotime($this->fecha_desde)),
			    	      					 				date('Y-m-d',strtotime($this->fecha_hasta))])
	    				      ->andWhere('S.tipo_solicitud =:tipo_solicitud',
	    				      								[':tipo_solicitud' => $this->tipo_solicitud])
	    				      ->andWhere('S.impuesto =:impuesto',
	    				      				[':impuesto' => $this->impuesto])
	    				      ->andWhere('S.estatus =:estatus',
	    				      				[':estatus' => $this->estatus_solicitud])
	    				      ->groupBy(
	    				      		'S.nro_solicitud'
	    				      	);
	    				      // ->all();

    		} elseif ( $this->ordenar_declaracion == 1 ) {

    			$rows = $query->select('S.nro_solicitud,S.fecha_hora_creacion,
	    			                    S.id_contribuyente,D.ano_impositivo,
	    			                    T.descripcion,E.descripcion as condicion,
	    			                    D.tipo_declaracion,
	    			                    sum(D.monto_new) as suma')
	    				      ->from(SolicitudesContribuyente::tableName() . ' S')
	    				      ->join('INNER JOIN', DeclaracionBase::tableName() . ' D', 'S.nro_solicitud=D.nro_solicitud')
	    				      ->join('INNER JOIN', EstatusSolicitud::tableName() . ' E', 'S.estatus=E.estatus_solicitud')
	    				      ->join('INNER JOIN', TipoSolicitud::tableName() . ' T', 'S.tipo_solicitud=T.id_tipo_solicitud')
	    				      ->where(['BETWEEN', 'date(fecha_hora_creacion)',
	    				      		 						date('Y-m-d',strtotime($this->fecha_desde)),
			    	      					 				date('Y-m-d',strtotime($this->fecha_hasta))])
	    				      ->andWhere('S.tipo_solicitud =:tipo_solicitud',
	    				      								[':tipo_solicitud' => $this->tipo_solicitud])
	    				      ->andWhere('S.impuesto =:impuesto',
	    				      				[':impuesto' => $this->impuesto])
	    				      ->andWhere('S.estatus =:estatus',
	    				      				[':estatus' => $this->estatus_solicitud])
	    				      ->groupBy(
	    				      		'S.nro_solicitud'
	    				      	)
	    				      ->orderBy([
	    				      		'suma'=> SORT_DESC,
	    				      	]);
	    				     // ->all();

    		}


    		$dataProvider = New ActiveDataProvider([
							'query' => $query,
							'pagination' => [
        						'pageSize' => 30,
    						],
					]);

    		return $dataProvider;

    	}









		/***/
		public function search1($params)
		{
			$query = self::findSolicitudDeclaracionModel();

			$dataProvider = New ActiveDataProvider([
							'query' => $query,
							'pagination' => [
        						'pageSize' => 30,
    						],
    						'sort' => [
    							'sum(D.monto_new)' => SORT_ASC,
    						],
					]);
	  //       if (!$this->validate()) {
	  //           // uncomment the following line if you do not want to any records when validation fails
	  //           // $query->where('0=1');
	  //           return $dataProvider;
	  //       }


	    	if ( $this->tipo_solicitud > 0 ) {
		   		$query->andFilterWhere(['=', 'tipo_solicitud1', $this->tipo_solicitud]);
		   	}
	    	if ( $this->fecha_desde !== null && $this->fecha_hasta !== null ) {

		    	$query->andFilterWhere(['BETWEEN','date(fecha_hora_creacion)',
		    	      					 date('Y-m-d',strtotime($this->fecha_desde)),
		    	      					 date('Y-m-d',strtotime($this->fecha_hasta))]);
		   	}
		   	if ( $this->impuesto > 0 ) {
		   		$query->andFilterWhere(['=', 'impuesto', $this->impuesto]);
		   	}
		   	if ( $this->nro_solicitud > 0 ) {
		   		$query->andFilterWhere(['=', 'nro_solicitud', $this->nro_solicitud]);
		   	}

		   	if ( $this->estatus_solicitud !== null ) {
		   		$query->andFilterWhere(['=', 'estatus_solicitud', $this->estatus_solicitud]);
		   	}

		   	if ( $this->nro_solicitud !== null ) {
		   		$query->andFilterWhere(['=', 'nro_solicitud', $this->nro_solicitud]);
		   	}

	        return $dataProvider;


		}



		public function getTableName()
		{
			return 'sl_declaraciones';
		}



		public function rules()
	    {
	        return [
	        	[['tipo_solicitud','fecha_desde',
	        	  'fecha_hasta', 'estatus_solicitud',],
	        	  'required', 'on' => 'tipo',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	 [['nro_solicitud'],
	        	  'required', 'on' => 'numero_solicitud',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	        	[['tipo_solicitud', 'nro_solicitud',
	        	  'impuesto', 'ordenar_declaracion',
	        	   'estatus_solicitud',],
	        	   'integer'],
	        ];
	    }




		/***/
		public function getMontoLiquidacion($tipoLiquidacion, $añoImpositivo, $idContribuyente)
		{
			$suma = 0;
			$resultados = PagoDetalle::find()->where('id_contribuyente =:id_contribuyente',
											 		[':id_contribuyente' => $idContribuyente])
											 ->andWhere('impuesto =:impuesto',[':impuesto' => 1])
											 ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
											 ->andWhere('referencia =:referencia',
													[':referencia' => $tipoLiquidacion])
											 ->andWhere('ano_impositivo =:ano_impositivo',
													[':ano_impositivo' => $añoImpositivo])
											 ->andWhere('pago !=:pago',[':pago' => 9])
											 ->joinWith('pagos P', true, 'INNER JOIN')
											 ->asArray()
											 ->all();
			if ( count($resultados) > 0 ) {
				foreach ( $resultados as $resultado ) {
					$suma = $suma + ( $resultado['monto'] + $resultado['recargo'] + $resultado['interes'] ) - ( $resultado['descuento'] + $resultado['monto_reconocimiento'] );
				}
			}

			return $suma;
		}



		/**
		 * Metodo que permite obtener una lista para los lista-combo
		 * @return [type] [description]
		 */
		public function getListaEstatus()
		{
			$model = EstatusSolicitud::find()->all();
			return ArrayHelper::map($model,'estatus_solicitud', 'descripcion');
		}


		/***/
		public function getContribuyente($idContribuyente)
		{
			return ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
		}



	}


?>