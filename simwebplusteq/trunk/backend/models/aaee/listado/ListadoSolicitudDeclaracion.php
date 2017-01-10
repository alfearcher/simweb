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
	use backend\models\aaee\rubro\Rubro;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\DeclaracionBase;
	use yii\db\ActiveRecord;
	use yii\base\Model;
	use common\models\planilla\PagoDetalle;


	/**
	* Clase que gestiona la emision de un listado de las solicitudes de las declaraciones.
	*/
	class ListadoSolicitudDeclaracion extends Model
	{

		public $fecha_desde;
		public $fecha_hasta;
		public $tipo_solicitud;
		public $fecha_hora_creacion;




		/***/
		public function search($params)
		{
			$query = self::findSolicitudDeclaracionModel();

			$dataProvider = New ActiveDataProvider([
							'query' => $query,
							'pagination' => [
        						'pageSize' => 30,
    						],
					]);
			$this->load($params);

	        if (!$this->validate()) {
	            // uncomment the following line if you do not want to any records when validation fails
	            // $query->where('0=1');
	            return $dataProvider;
	        }

	        // $query->andFilterWhere([
	        //     'fecha_hora_creacion' => '',
	        // ]);


	        return $dataProvider;


		}



		public function getTableName()
		{
			return 'sl_declaraciones';
		}



		public function rules()
	    {
	        return [
	        	[['tipo_solicitud','fecha_desde', 'fecha_hasta'], 'required'],
	        	[['tipo_solicitud'], 'integer'],
	        ];
	    }




		/***/
		public function findSolicitudDeclaracionModel()
		{
			return $findModel = SolicitudesContribuyente::find()->alias('S')
													   ->where('D.estatus !=:estatus',[':estatus' => 9])
													   ->andWhere('S.inactivo =:inactivo',[':inactivo' => 0])
													   ->joinWith('declaracion D', true, 'INNER JOIN');
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


	}


?>