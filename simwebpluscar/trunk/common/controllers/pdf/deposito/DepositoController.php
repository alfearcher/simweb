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
 *	@file DepositoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class DepositoController
 *	@brief Clase que gestiona la generacion del pdf del recibo de pago
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


 	namespace common\controllers\pdf\deposito;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\recibo\recibo\ReciboSearch;
	use backend\models\recibo\deposito\Deposito;
	// use common\models\numerocontrol\NumeroControlSearch;
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;

	use mPDF;


	/***/
	class DepositoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_recibo;
		private $_id_contribuyente;
		private $_nro_control;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo identificador del recibo, numero que se genero al momneto de
		 * insertar el registro en la entidad "depositos", este numero permite el link con las
		 * otras entidasdes que estan relacionada con el recibo de pago y con el pago en si.
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $nroControl numero que se genero al momento de crear el recibo como
		 * control de procesos en el sistama.
		 */
		public function __construct($recibo, $idContribuyente, $nroControl)
		{
			$this->_recibo = $recibo;
			$this->_id_contribuyente = $idContribuyente;
			$this->_nro_control = $nroControl;
		}




		/***/
		public function actionGenerarReciboPdf()
		{
			$barcode = 152222;
            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'RECIBO DE PAGO MULTIPLE',

                                    ]);

            // Informacion del congtribuyente.
            $findModel = ContribuyenteBase::findOne($this->_id_contribuyente);
            $htmlContribuyente =  $this->renderPartial('@common/views/plantilla-pdf/layout/layout-contribuyente-pdf',[
                                                            'model' => $findModel,
                                                            'showDireccion' => true,
                                                            'showRepresentante' => false,
                                    ]);

            // Identificacion del pago.
            $deposito = Deposito::findOne($this->_recibo);
            $htmlIdentidadPago = $this->renderPartial('@common/views/plantilla-pdf/recibo/layout-identidad-pago-pdf', [
            												'model' => $deposito,
            												'cvb' => '123-4567-8970',
            					]);


            // Detalle del pago.
            $htmlDetallePago =



            // Nombre del archivo.
	        $nombrePDF = 'RC-' . $this->_id_contribuyente . '-' . $this->_recibo . '-' . $this->_nro_control;
	        $nombre = $nombrePDF;
	        $nombrePDF .= '.pdf';

	        $mpdf = new mPDF;

	        $mpdf->SetHeader($nombre);
	        $mpdf->WriteHTML($htmlEncabezado);
	        $mpdf->WriteHTML($htmlContribuyente);
	        $mpdf->WriteHTML($htmlIdentidadPago);
	        $mpdf->WriteHTML($htmlDetallePago);
	        /*$mpdf->SetHTMLFooter($htmlPiePagina);*/

	        $mpdf->Output($nombrePDF, 'I');
	        exit;
		}


	}
?>