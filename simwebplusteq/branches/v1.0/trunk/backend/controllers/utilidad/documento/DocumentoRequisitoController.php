<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file DocumentoRequisitoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 29-09-2015
 *
 *  @class DocumentoRequisitoController
 *	@brief Clase DocumentoRequisitoController,
 *
 *
 *
 *	@property
 *
 *
 *	@method
 *
 *
 *	@inherits
 *
 */


 	namespace backend\controllers\utilidad\documento;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\web\NotFoundHttpException;
	use backend\models\utilidad\documento\DocumentoRequisito;
	use backend\models\utilidad\documento\DocumentoRequisitoForm;



	//session_start();		// Iniciando session

	/**
	 *
	 */
	class DocumentoRequisitoController extends Controller
	{



		/**
		 * Metodo que permite retornar una lista con los documentos y requisitos solicitados por impuesto.
		 * @param $impuesto, integer que identifica al impuesto.
		 * @return retorna una lista de registros de la entidad documentos-requisitos.
		 */
		public function actionDocumentoRequisitoSegunImpuesto($impuesto)
		{
			if ( $impuesto > 0 ) {
				return DocumentoRequisito::documentoRequisitoSegunImpuesto($impuesto);
			}
		}





		/**
		 * Metodo que devuelve un dataProvider desde el modelo
		 * @return [type] [description]
		 */
		public function actionGetDataProviderSegunImpuesto($impuesto = 0)
		{
			return DocumentoRequisitoForm::getDataProviderDocumentosRequisitosSegunImpuesto($impuesto);
		}



		/***/
		public function actionRenderizarGridViewDocumentoRequisito($impuesto = 0)
		{
			$dataProvider = $this->actionGetDataProviderSegunImpuesto($impuesto);
			return $this->render('/utilidad/documento-requisito/documento-requisito-gridview', [
																			'dataProvider' => $dataProvider
																]);
		}

	}
?>