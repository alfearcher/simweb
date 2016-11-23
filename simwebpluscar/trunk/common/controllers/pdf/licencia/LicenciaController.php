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


 	namespace common\controllers\pdf\licencia;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\aaee\historico\licencia\HistoricoLicenciaSearch;

	use mPDF;


	/**
	 * Clase controller que gestiona la emision del comprobante de recibo de pago en pdf
	 */
	class LicenciaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_id_historico;
		private $_id_contribuyente;
		private $_nro_control;




		/**
		 * Metodo constructor de la clase.
		 * @param integer $idHistorico identificador del historico de la licencia,
		 * numero que se genero al momento de insertar el registro en la entidad
		 * "historico_licencias_sw".
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $nroControl numero que se genero al momento de crear el recibo como
		 * control de procesos en el sistama.
		 */
		public function __construct($idHistorico, $idContribuyente, $nroControl)
		{
			$this->_id_historico = $idHistorico;
			$this->_id_contribuyente = $idContribuyente;
			$this->_nro_control = $nroControl;
		}




		/**
		 * Metodo que renderiza un recibo de pago en pdf
		 * @return view retorna un pdf del recibo de pago.
		 */
		public function actionGenerarLicenciaPdf()
		{
			$cvb = '';
            // Informacion del encabezado.
            $htmlEncabezado = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-encabezado-pdf', [
                                                            'caption' => 'LICENCIA SOBRE ACTIVIDADES ECONOMICAS DE INDUSTRIA, COMERCIO Y SERVICION',

                                    ]);


            // Informacion del Historico
            $historico = New HistoricoLicenciaSearch($this->_id_contribuyente);
            $historico->setHistorico($this->_id_historico);
            $model = $historico->findHistoricoLicencia();

            if ( count($model) > 0 ) {
	            $datosContribuyente = json_decode($model['fuente_json'], true);
	            $datosRubro = json_decode($model['rubro_json'], true);

die(var_dump($datosContribuyente));
	            // Informacion del contribuyente.
	            $htmlContribuyente = $this->renderPartial('@common/views/plantilla-pdf/licencia/layout-contribuyente-pdf', [
	                                                            'model' => $model,
	                                                            'datosContribuyente' => $datosContribuyente,
	                                                            'showDireccion' => true,
	                                                            'showRepresentante' => true,
	                                    ]);


	            // Informacion de la licencia
	            $htmlLicencia = $this->renderPartial('@common/views/plantilla-pdf/licencia/layout-identificacion-licencia-pdf', [
	            												'model' => $model,
	            												'datosContribuyente' => $datosContribuyente,
	            						]);


	            // Informacion de los rubros aprobados
	            $htmlRubro =  $this->renderPartial('@common/views/plantilla-pdf/licencia/layout-rubro-autorizado-pdf', [
	            												'model' => $model,
	            												'datosRubro' => $datosRubro,
	            						]);


	            // QR
	            $barcode = $model['serial_control'];
	            $htmlQR = $this->renderPartial('@common/views/plantilla-pdf/layout/layout-qr-pdf',[
	            											'barcode' => $barcode,
	            					]);



	            // Nombre del archivo.
		        $nombrePDF = $model['serial_control'];
		        if ( trim($nombrePDF) !== '' ) {
		        	$nombre = $nombrePDF . '.pdf';
		        }

		        $mpdf = new mPDF;

		        $mpdf->SetHeader($nombrePDF);
		        $mpdf->WriteHTML($htmlEncabezado);
		        $mpdf->WriteHTML($htmlContribuyente);
		        $mpdf->WriteHTML($htmlLicencia);
		        $mpdf->WriteHTML($htmlRubro);

		       //funciona
		       // $mpdf->Rect(18, 230, 100, 30, D);

		        // eje x, y, w=width, h=height, r=radius, estilo de la linea
		        // 											D = dibuja linea
		        // 											F
		        // 											DF
		       // $mpdf->RoundedRect(18, 230, 120, 30, 3, D);
		       // $mpdf->SetFont('Arial', 'B', 8);
		       // $mpdf->Text(60,258,"Validacion terminal caja");

		       // Se coloca el QR
		       $mpdf->WriteFixedPosHTML($htmlQR, 100, 220, 120, 30);


		       $mpdf->Output($nombre, 'I');
		       exit;
		    }
		}



	}
?>