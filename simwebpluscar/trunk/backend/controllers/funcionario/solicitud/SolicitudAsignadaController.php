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
 *  @file SolicitudAsignada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-05-2016
 *
 *  @class SolicitudAsignadaController
 *  @brief Clase
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


	namespace backend\controllers\funcionario\solicitud;

 	session_start();		// Iniciando session
 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use backend\models\funcionario\solicitud\SolicitudAsignadaSearch;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;

	/**
	 *	Clase principal del formulario.
	 */
	class SolicitudAsignadaController extends Controller
	{

	   	public $layout = 'layout-main';				//	Layout principal del formulario.

		public $conn;
		public $conexion;
		public $transaccion;



		/***/
		public function actionIndex()
		{
			$model = New SolicitudAsignadaSearch();
			$lista = $model->getTipoSolicitudAsignada('jperez');

			$model->getDataProviderSolicitudContribuyente($lista);

// 			$lista = $model->findIdTipoSolicitudSegunFuncionario('jperez');
// 			$listaGeneral = $lista->asArray()->all();
//  			$listaSolicitud = $listaGeneral[0]['funcionarioSolicitud'];
// die(var_dump($listaSolicitud));
//  			$tipo = $listaSolicitud['tipo_solicitud'];
// die(var_dump($tipo));
		}

	}
?>