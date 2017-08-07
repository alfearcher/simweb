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
	// use common\models\numerocontrol\NumeroControlSearch;
	use common\conexion\ConexionController;
	use common\models\rafaga\GenerarRafagaRecibo;
    use backend\models\historico\impresion\HistoricoImpresionSearch;
    use backend\models\historico\impresion\HistoricoImpresion;

	use mPDF;



	/**
	 * Clase controller que gestiona la emision del comprobante de recibo de pago en pdf
	 */
	class ReciboRafagaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_recibo;
		private $_id_contribuyente;
		private $_nro_control;



		/**
		 * Metodo constructor de la clase
		 * @param integer $recibo identificador del recibo.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
		}



		/**
		 * Metodo que renderiza la rafaga del recibo de pago en pdf
		 * @return view retorna un pdf de la rafaga del recibo de pago.
		 */
		public function actionGenerarRafagaReciboPdf()
		{

			$generarRafaga = New GenerarRafagaRecibo($this->_recibo);
            $rafaga = $generarRafaga->getRafaga();
            if ( count($rafaga) > 0 ) {

            	$result = self::guardarHistoricoImpresion($rafaga);

				$nombre = '';
	            // Nombre del archivo.
		        $nombrePDF = Yii::t('backend', 'rafaga');
		        if ( trim($nombrePDF) !== '' ) {
		        	$nombre = $nombrePDF . '.pdf';
		        }

		        $mpdf = new mPDF;

		        //$mpdf->SetHeader($nombrePDF);
		        $mpdf->WriteHTML('');
		       	//funciona
		       	// $mpdf->Rect(18, 230, 100, 30, D);

		        // eje x, y, w=width, h=height, r=radius, estilo de la linea
		        // 											D = dibuja linea
		        // 											F
		        // 											DF
		       	$mpdf->RoundedRect(16, 230, 120, 30, 3, D);
		       	$mpdf->SetFont('Arial', 'B', 8);
		       	$mpdf->Text(60,258,"Validacion terminal caja");

		       	$mpdf->SetFont('Arial', 'N', 8);
		       	// Texto de la rafaga.
		       	$mpdf->Text(18, 234, $rafaga['alcaldia']);
		       	$mpdf->Text(18, 238, $rafaga['id_contribuyente'] . ' ' . $rafaga['contribuyente']);
		       	$mpdf->Text(18, 242, 'R'. $rafaga['recibo'] . ' - total: ' . Yii::$app->formatter->asDecimal($rafaga['monto'], 2) . ' -  fecha pago: ' . date('d-m-Y', strtotime($rafaga['fecha'])));

		       	// Formas de pagos del Recibo.
		       	foreach ( $rafaga['forma_pago'] as $formaPago ) {
		       		$forma .= $formaPago['descripcion'] . ' : ' . $formaPago['aporte'] . '  -  ';
		       	}

		       	$mpdf->Text(18, 246, $forma);
		       	$mpdf->Text(18, 254, 'impreso por: ' . $rafaga['usuario'] . ' ' . $rafaga['fecha_hora']);

		       	$mpdf->Output($nombre, 'I');
		       	exit;

            } else {
            	if ( count($generarRafaga->getErrores()) > 0 ) {
            		$mensajes = $generarRafaga->getErrores();
            		$htmlMensaje = $this->renderPartial('/recibo/pago/individual/warnings',[
                                                            'mensajes' => $mensajes,
                                            ]);
            		return $this->render('/recibo/pago/error/error',[
                                        'htmlMensaje' => $htmlMensaje,
                	]);
         //    		return $this->render('@common/views/error/warnings', [
									// 					'mensajes' => $mensajes,
									// ]);
            		//return $rafaga->getErrores();
            	}
            }
		}



		/**
		 * Metodo que renderiza una vista que indica que ocurrio un error en la
		 * ejecucion del proceso.
		 * @param  integer $cod codigo que permite obtener la descripcion del
		 * codigo de la operacion.
		 * @return view.
		 */
		public function actionErrorOperacion($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}




		 /**
         * Metodo que inicia el proceso de guardar el historico de impresion de la
         * rafaga.
         * @param array $labelRafaga arreglo con la informacion basica de la rafaga.
         * @return boolean.
         */
        public function guardarHistoricoImpresion($labelRafaga)
        {
            $result = false;

            $impresionModel = New HistoricoImpresion();
            $impresionModel->documento = 'RAFAGA RECIBO';
            $impresionModel->nro_documento = $labelRafaga['recibo'];
            $impresionModel->usuario = Yii::$app->identidad->getUsuario();
            $impresionModel->fecha_hora = date('Y-m-d H:i:s');
            $impresionModel->fuente_json = json_encode($labelRafaga);
            $impresionModel->observacion = '';
            $impresionModel->nro_control = 0;
            $impresionModel->ip_maquina = isset(Yii::$app->request->userIP) ? Yii::$app->request->userIP : '';
            $impresionModel->host_name = isset(Yii::$app->request->userHost) ? Yii::$app->request->userHost : '';

            $historicoSearch = New HistoricoImpresionSearch();
            $result = $historicoSearch->guardar($impresionModel);
            return $result;
        }

	}
?>