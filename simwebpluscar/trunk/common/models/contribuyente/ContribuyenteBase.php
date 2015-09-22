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
 *	@file ContribuyenteBase.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 16-09-2015
 *
 *  @class Contribuyente modelo principal de la entidad "contribuyentes"
 *
 *
 *	@property
 *
 *	@method
 *
 *	@inherits
 *
 */
	namespace common\models\contribuyente;

	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use common\conexion\ConexionController;
	use yii\db\Exception;


	/**
	*
	*/
	class ContribuyenteBase extends ActiveRecord
	{

		public $id_contribuyente;
		public $ente;
		public $naturaleza;
		public $cedula;
		public $tipo;
		public $tipo_naturaleza;			// 0 => NATURAL, 1 => JURIDICO.
		public $id_rif;
		public $id_cp;
		public $apellidos;
		public $nombres;
		public $razon_social;
		public $representante;
		public $nit;
		public $fecha_nac;
		public $sexo;
		public $casa_edf_qta_dom;
		public $piso_nivel_no_dom;
		public $apto_dom;
		public $domicilio_fiscal;
		public $catastro;
		public $tlf_hab;
		public $tlf_hab_otro;
		public $tlf_ofic;
		public $tlf_ofic_otro;
		public $tlf_celular;
		public $fax;
		public $email;
		public $inactivo;					// 0 => ACTIVO, 1 => INACTIVO.
		public $cuenta;
		public $reg_mercantil;
		public $num_reg;
		public $tomo;
		public $folio;
		public $fecha;
		public $capital;
		public $horario;
		public $extension_horario;
		public $num_empleados;
		public $tipo_contribuyente;
		public $licencia;
		public $agente_retencion;
		public $id_sim;
		public $manzana_limite;
		public $lote_1;
		public $lote_2;
		public $nivel;
		public $lote_3;
		public $fecha_inclusion;
		public $fecha_inicio;
		public $foraneo;
		public $no_declara;
		public $econ_informal;
		public $grupo_contribuyente;
		public $fe_inic_agente_retencion;
		public $no_sujeto;
		public $ruc;
		public $naturaleza_rep;
		public $cedula_rep;


		public $conexion;
		public $conn;





		/**
		 * 	Metodo que retorna el nombre de la tabla que utiliza el modelo.
		 * 	@return Nombre de la tabla del modelo.
		 */
		public static function tableName()
		{
			return 'contribuyentes';
		}



		/**
		 *	Metodo que describe la condicion del registro.
		 * 	@param $inactivoLocal, integer que determina la condicion del registro.
		 * 	@return string indicando la condicion del resgitro, si returna false no
		 * 	indica que no se ha podido identificar condicion del registro.
		 */
		public static function  getActivoInctivoDescripcion($inactivoLocal)
		{
			if ( $inactivoLocal == 0 ) {
				return 'ACTIVO';
			} elseif ( $inactivoLocal == 1 ) {
				return 'INACTIVO';
			}
			return false;
		}




		/**
		 *	Metodo que describe a que tipo de naturaleza pertenece el contribuyente, segun el
		 * 	valor del campo tipo_naturaleza.
		 * 	@param $tipoNaturalezaLocal, integer que determina a que tipo de naturaleza pertenece un
		 * 	contribuyente.
		 * 	@return string indicando el tipo de naturaleza, si returna false es porque no se ha
		 * 	podido identificar el tipo de naturaleza.
		 */
		public static function getTipoNaturalezaDescripcion($tipoNaturalezaLocal)
		{
			if ( $tipoNaturalezaLocal == 0 ) {
				return 'NATURAL';
			} elseif ( $tipoNaturalezaLocal == 1 ) {
				return 'JURIDICO';
			}
			return false;
		}





		/**
		 *	Metodo que me arma el numero de identificacion del documento primario del contribuyente,
		 * 	cedula (contribuyentes naturalez), rif (contribuyente juridico).
		 * 	@param $tipoNaturalezaLocal, integer que indica el tipo de naturaleza del contribuyente,
		 * 	natural o juridico.
		 * 	@param $naturalezaLocal, string que identifica el primer caracter del documento de identificacion
		 * 	primario del contribuyente.
		 * 	@param $cedulaLocal, long que forma parte del documento de identificacion del contribuyente.
		 * 	@param $tipoLocal, integer que identifica el ultimo digito del rif de los contribuyentes juridico.
		 * 	@return returna un string que representa el numero de identificacion del contribuyente.
		 * 	Para los contribuyentes naturaleza retorna el formato A-99999999 y para los juridicos el formato
		 * 	sera A-999999999-9. Si retorna false es porque no se determino el tipo de naturaleza del contribuyente.
		 */
		public static function getCedulaRifDescripcion($tipoNaturalezaLocal, $naturalezaLocal = 0, $cedulaLocal = 0, $tipoLocal = 0)
		{
			if ( $tipoNaturalezaLocal == 0 ) {
				return $naturalezaLocal . '-' . $cedulaLocal;
			} elseif ( $tipoNaturalezaLocal == 1 ) {
				return $naturalezaLocal . '-' . $cedulaLocal . '-' . $tipoLocal;
			}
			return false;
		}






		/**
		 *	Metodo que retorna la descripcion del contribuyente, es decir, apellidos y nombres para los contribuyentes
		 * 	naturales y razon social para los contribuyente juridicos.
		 * 	Cuando el contribuyente es juridico no es necesario enviar los demás parametros.
		 * 	@param $tipoNaturalezaLocal, integer que indica el tipo de naturaleza del contribuyente,
		 * 	natural o juridico.
		 * 	@param $razonSocialLocal, string que contiene la descripcion de los contribuyentes de tipo juridicos.
		 * 	@param $apellidosLocal, string que indica el apellido del contribuyente de tipo natural.
		 * 	@param $nombresLocal, string que indica el nombre del contribuyente de tipo natural.
		 * 	@param $ordenApellidoNombre, integer que indica en que orden se va a concatenar los apellidos y nombres
		 * 	de los contribuyentes naturalez.
		 * 	@return descripcion del contribuyente, si retorna blanco es poque no se pudo identificar el tipo de
		 * 	naturaleza o porque los campos se mandaron en blanco
		 */
		public static function getContribuyenteDescripcion($tipoNaturalezaLocal, $razonSocialLocal = '',  $apellidosLocal = '', $nombresLocal = '', $ordenApellidoNombre = 0 )
		{
			$contribuyente = '';
			if ( $tipoNaturalezaLocal == 0 ) {
				if ( $ordenApellidoNombre == 0 ) {
					$contribuyente = $apellidosLocal . ' ' . $nombresLocal;
				} else {
					$contribuyente = $nombresLocal . ' ' . $apellidosLocal;
				}
			} elseif ( $tipoNaturalezaLocal == 1 ) {
				$contribuyente = $razonSocialLocal;
			}
			return $contribuyente;
		}






		/**
		 *
		 */
		private static function datosContribuyenteSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				try {
					$conexion = new ConexionController();
					$conn = $conexion->InitConectar('db');
					$conn->open();

					$tabla = self::tableName();
					$sql = "select * from {$tabla} where id_contribuyente = {$idContribuyente}";

					$dataReport = $conexion->buscarRegistro($conn, $sql);
					$conn->close();

					return $dataReport;

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 *
		 * 	@return Descripcion del contribuyente
		 */
		public static function getContribuyenteDescripcionSegunID($idContribuyente)
		{
			$dataResult = self::datosContribuyenteSegunID($idContribuyente);
			if ( $dataResult ) {
				$tipoNaturalezaLocal = $dataResult[0]['tipo_naturaleza'];
				$apellidosLocal = $dataResult[0]['apellidos'];
				$nombresLocal = $dataResult[0]['nombres'];
				$razonSocialLocal = $dataResult[0]['razon_social'];

				return self::getContribuyenteDescripcion($tipoNaturalezaLocal, $razonSocialLocal, $apellidosLocal, $nombresLocal);
			} else {
				return false;
			}
		}






		/**
		 *	Metodo que me permite obtener el numero de cedula, en caso de los caontribuyentes con tipo naturaleza = NATURALEZ
		 * 	o en su defecto el rif para aquellos contribuyentes cuyo tipo naturaleza es JURIDICO. Esto a traves del ID del Contribuyente
		 * 	como parametro de entrada.
		 * 	@param $idContribuyente, long que identifica al contribuyente.
		 * 	@return returna la cedula de identidad en formato A-99999999 o el rif en formato A-999999999-9.
		 */
		public static function getCedulaRifSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$dataResult = self::datosContribuyenteSegunID($idContribuyente);
				if ( $dataResult ) {
					$tipoNaturalezaLocal = $dataResult[0]['tipo_naturaleza'];
					$naturalezaLocal = $dataResult[0]['naturaleza'];
					$cedulaLocal = $dataResult[0]['cedula'];
					$tipoLocal = $dataResult[0]['tipo'];

					return self::getCedulaRifDescripcion($tipoNaturalezaLocal, $naturalezaLocal, $cedulaLocal, $tipoLocal);
				} else {
					return false;
				}
			} else {
				return false;
			}
		}






		/**
		 *
		 */
		public static function getTipoNaturalezaDescripcionSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$dataResult = self::datosContribuyenteSegunID($idContribuyente);
				if ( $dataResult ) {
					$tipoNaturalezaLocal = $dataResult[0]['tipo_naturaleza'];

					return self::getTipoNaturalezaDescripcion($tipoNaturalezaLocal);
				} else {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 *
		 */
		public function getTefefonosSegunID($idContribuyente)
		{

		}



		/**
		 *
		 */
		public function getTelefonos()
		{

		}

	}
 ?>