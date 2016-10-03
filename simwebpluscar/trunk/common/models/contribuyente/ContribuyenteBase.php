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






		/**
		 *	Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
		 * 	Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
		 * 	@return Nombre de la base de datos
		 */
		public static function getDb()
		{
			return Yii::$app->db;
		}




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
		 * [datosContribuyenteSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
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
		 * [getTipoNaturalezaDescripcionSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
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




		/***/
		public function getTelefonosSegunID($idContribuyente)
		{
			return self::getTelefonos($idContribuyente);
		}



		/***/
		private static function getTelefonos($idContribuyente)
		{
			$telefono = null;
			$dataResult = self::datosContribuyenteSegunID($idContribuyente);
			if ( $dataResult ) {
				if ( $dataResult[0]['tipo_naturaleza'] == 0 ) {
					$telefono['tlf_hab'] = $dataResult[0]['tlf_hab'];
					$telefono['tlf_hab_otro'] = $dataResult[0]['tlf_hab_otro'];
					$telefono['tlf_celular'] = $dataResult[0]['tlf_celular'];

				} elseif ( $dataResult[0]['tipo_naturaleza'] == 1 ) {
					$telefono['tlf_ofic'] = $dataResult[0]['tlf_ofic'];
					$telefono['tlf_ofic_otro'] = $dataResult[0]['tlf_ofic_otro'];
					$telefono['tlf_celular'] = $dataResult[0]['tlf_celular'];
				}
			}
			return $telefono;
		}



		/***/
		public static function getDomicilioSegunID($idContribuyente)
		{
			return self::getDomicilio($idContribuyente);
		}



		/***/
		private static function getDomicilio($idContribuyente)
		{
			$domicilio = null;
			$dataResult = self::datosContribuyenteSegunID($idContribuyente);
			if ( $dataResult ) {
				$domicilio = $dataResult[0]['domicilio_fiscal'];
			}
			return $domicilio;
		}





		/***/
		public static function getFechaInicio($idContribuyente)
		{
			$datos = self::getDatosContribuyenteSegunID($idContribuyente);
			if ( $datos !== false ) {
				return isset($datos[0]['fecha_inicio']) ? $datos[0]['fecha_inicio'] : null;
			}
			return null;
		}




		/**
		 * [getDatosContribuyenteSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
		 */
		public static function getDatosContribuyenteSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				return self::datosContribuyenteSegunID($idContribuyente);
			} else {
				return false;
			}
		}



		/**
		 * Metodo que permite obtener el email del contribuyente
		 * @param  Long $idContribuyente identificador del contribuyente
		 * @return String Retorna el email del contribuyente, sino encuentra nada
		 * retorna vacio.
		 */
		public static function getEmail($idContribuyente)
		{
			$email = '';
			if ( $idContribuyente > 0 ) {
				$datos = self::getDatosContribuyenteSegunID($idContribuyente);
				$email = isset($datos[0]['email']) ? $datos[0]['email'] : '';
			}
			return $email;
		}





		/**
		 * Metodo que permite obtener el ultimo indicador de la sucursal. Cero (0) indica sede principal.
		 * @param  $naturalezaLocal string que indica la primera letra del RIF del contribuyente.
		 * @param  $cedulaLocal integer que indica los numeros en el centro del RIF.
		 * @param  $tipoLocal integer que indica el ultimo digito del RIF del contribuyente juridico.
		 * @return returna un integer que representa el indicador de sucursal (id_rif), este numero se incrementa
		 * cada vez que se incluye una sucursal, el valor de este numero en la sede principal debe ser cero (0).
		 */
		private static function getIdRifUltimaSucursal($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				try {
					$conexion = new ConexionController();
					$conn = $conexion->InitConectar('db');
					$conn->open();
					$tabla = self::tableName();

					$command = $conn->createCommand('SELECT id_rif FROM contribuyentes WHERE naturaleza=:naturaleza
													AND cedula=:cedula AND tipo=:tipo AND tipo_naturaleza=:tipo_naturaleza
													ORDER BY id_rif DESC LIMIT 1');
					$command->bindValues([':naturaleza' => $naturalezaLocal,':cedula' => $cedulaLocal, ':tipo' => $tipoLocal, ':tipo_naturaleza' => 1]);
					$post = $command->queryOne();
					$conn->close();

					return $post;

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}






		/**
		 * Metodo que permite obtener el ultimo indicador de la sucursal. Cero (0) indica sede principal.
		 * @param  $naturalezaLocal string que indica la primera letra del RIF del contribuyente.
		 * @param  $cedulaLocal integer que indica los numeros en el centro del RIF.
		 * @param  $tipoLocal integer que indica el ultimo digito del RIF del contribuyente juridico.
		 * @return returna un integer que representa el indicador de sucursal (id_rif), este numero se incrementa
		 * cada vez que se incluye una sucursal, el valor de este numero en la sede principal debe ser cero (0).
		 */
		public static function getUltimoIdRifSucursalSegunRIF($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				if ( strlen($naturalezaLocal) == 1 ) {
					return self::getIdRifUltimaSucursal($naturalezaLocal, $cedulaLocal, $tipoLocal);
				}
			}
			return false;
		}





		/**
		 * Metodo para obtener la cantidad de contribuyentes asociados a un RIF.
		 * @param  $naturalezaLocal string que indica la primera letra del RIF del contribuyente.
		 * @param  $cedulaLocal integer que indica los numeros en el centro del RIF.
		 * @param  $tipoLocal integer que indica el ultimo digito del RIF del contribuyente juridico.
		 * @return retorna cantidad de contribuyentes asociados a un RIF o false sino consigue ninguno.
		 */
		public static function getCantidadSucursalesSegunRIF($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0, $inactivoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				if ( strlen($naturalezaLocal) == 1 ) {
					return self::getCantidadSucursales($naturalezaLocal, $cedulaLocal, $tipoLocal, $inactivoLocal);
				}
			}
			return false;
		}





		/**
		 * Metodo que retorna la cantidad de sucursales que estan asociada aun RIF, se buscan los registros por defectos
		 * activos ($inactivoLocal = 0), si se requiere los inactivos se debe enviar $inactivoLocal = 1.
		 * @param  $naturalezaLocal string que indica la primera letra del RIF del contribuyente.
		 * @param  $cedulaLocal integer que indica los numeros en el centro del RIF.
		 * @param  $tipoLocal integer que indica el ultimo digito del RIF del contribuyente juridico.
		 * @return retorna integer que indica la cantidad de contribuyentes asociados a un RIF. Si no consigue nada retorna false.
		 */
		private static function getCantidadSucursales($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0, $inactivoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				try {
					$conexion = new ConexionController();
					$conn = $conexion->InitConectar('db');
					$conn->open();
					$tabla = self::tableName();

					$command = $conn->createCommand('SELECT COUNT(*) as r FROM contribuyentes WHERE naturaleza=:naturaleza
													AND cedula=:cedula AND tipo=:tipo AND tipo_naturaleza=:tipo_naturaleza AND inactivo=:inactivo');
					$command->bindValues([':naturaleza' => $naturalezaLocal,':cedula' => $cedulaLocal, ':tipo' => $tipoLocal, ':tipo_naturaleza' => 1, ':inactivo' => $inactivoLocal]);
					$post = $command->queryOne();
					$conn->close();

					return $post;

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}





		/**
		 * [getListaSucursalesSegunRIF description]
		 * @param  string  $naturalezaLocal [description]
		 * @param  integer $cedulaLocal     [description]
		 * @param  integer $tipoLocal       [description]
		 * @param  integer $inactivoLocal   [description]
		 * @return [type]                   [description]
		 */
		public static function getListaSucursalesSegunRIF($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0, $inactivoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				if ( strlen($naturalezaLocal) == 1 ) {
					return self::getListaSucursales($naturalezaLocal, $cedulaLocal, $tipoLocal, $inactivoLocal);
				}
			}
			return false;
		}







		/**
		 * [getListaSucursales description]
		 * @param  string  $naturalezaLocal [description]
		 * @param  integer $cedulaLocal     [description]
		 * @param  integer $tipoLocal       [description]
		 * @param  integer $inactivoLocal   [description]
		 * @return [type]                   [description]
		 */
		private static function getListaSucursales($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0, $inactivoLocal = 0)
		{
			if ( trim($naturalezaLocal) !== '' && $cedulaLocal > 0 ) {
				try {
					$conexion = new ConexionController();
					$conn = $conexion->InitConectar('db');
					$conn->open();
					$tabla = self::tableName();

					$command = $conn->createCommand('SELECT * FROM contribuyentes WHERE naturaleza=:naturaleza
													AND cedula=:cedula AND tipo=:tipo AND tipo_naturaleza=:tipo_naturaleza AND inactivo=:inactivo');
					$command->bindValues([':naturaleza' => $naturalezaLocal,':cedula' => $cedulaLocal, ':tipo' => $tipoLocal, ':tipo_naturaleza' => 1, ':inactivo' => $inactivoLocal]);
					$post = $command->queryAll();
					$conn->close();

					return $post;

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 * [getCapitalSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
		 */
		public static function getCapitalSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$dataResult = self::datosContribuyenteSegunID($idContribuyente);
				if ( $dataResult ) {

					return $dataResult[0]['capital'];

				} else {
					return false;
				}
			} else {
				return false;
			}
		}





		/**
		 * [getCedulaRifTipoNaturalezaSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
		 */
		public static function getCedulaRifTipoNaturalezaSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$dataResult = self::datosContribuyenteSegunID($idContribuyente);
				if ( $dataResult ) {
					$cedulaRif['tipo_naturaleza'] = $dataResult[0]['tipo_naturaleza'];
					$cedulaRif['naturaleza'] = $dataResult[0]['naturaleza'];
					$cedulaRif['cedula'] = $dataResult[0]['cedula'];
					$cedulaRif['tipo'] = $dataResult[0]['tipo'];

					return $cedulaRif;

				} else {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 * [getLicenciaSegunID description]
		 * @param  [type] $idContribuyente [description]
		 * @return [type]                  [description]
		 */
		public static function getLicenciaSegunID($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$dataResult = self::datosContribuyenteSegunID($idContribuyente);
				if ( $dataResult ) {
					$cedulaRif['id_sim'] = $dataResult[0]['id_sim'];

					return $cedulaRif;

				} else {
					return false;
				}
			} else {
				return false;
			}
		}




		/**
		 * Metodo que permite determinar si un contribuyente es una sede principal.
		 * Esto aplica para los contribuyentes juridicos.
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true si es una sede principal, de lo contrario
		 * retorna false.
		 */
		private static function getEsSedePrincipalSegunID($idContribuyente)
		{
			$datos = self::datosContribuyenteSegunID($idContribuyente);
			if ( isset($datos) ) {
				if ( self::getTipoNaturalezaDescripcion($datos[0]['tipo_naturaleza']) == 'JURIDICO' ) {
					if ( $datos[0]['id_rif'] == 0 ) {
						return true;
					}
				}
			}

			return false;
		}




		/**
		 * Metodo que permite determinar si un contribuyente es una sede principal.
		 * Esto aplica para los contribuyentes juridicos.
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true si es una sede principal, de lo contrario
		 * retorna false.
		 */
		public static function getEsUnaSedePrincipal($idContribuyente)
		{
			return self::getEsSedePrincipalSegunID($idContribuyente);
		}



		/**
		 * Metodo que permite buscar los datos del representante legal, esto aplica
		 * para los contribuyente juridicos.
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return array|boolean retorna un arreglo con la informacion de la cedula
		 * y el nombre del representante legal, sino retorna false.
		 */
		private static function datosRepresentante($idContribuyente)
		{
			if ( $idContribuyente > 0 ) {
				$datos = self::datosContribuyenteSegunID($idContribuyente);
				$datoRepresentante = [];
				if ( isset($datos) ) {
					return $datoRepresentante = [
								'naturaleza_rep' => $datos[0]['naturaleza_rep'],
								'cedula_rep' => $datos[0]['cedula_rep'],
								'representante' => $datos[0]['representante'],
							];
				}
			}
			return false;
		}



		/**
		 * Metodo que redirecciona la busqueda de los datos del representante
		 * legal. Aplica solo para contribuyentes juridicos,
		 * @param  long $idContribuyente identificador del contribuyente.
		 * @return array|boolean retorna un arreglo con la informacion de la cedula
		 * y el nombre del representante legal, sino retorna false.
		 */
		public static function getDatosRepresentanteSegunID($idContribuyente)
		{
			return self::datosRepresentante($idContribuyente);
		}



		/**
		 * Metodo que determina a traves de un RIF, quien es ña sede principal.
		 * @param  $naturalezaLocal string que indica la primera letra del RIF del contribuyente.
		 * @param  $cedulaLocal integer que indica los numeros en el centro del RIF.
		 * @param  $tipoLocal integer que indica el ultimo digito del RIF del contribuyente juridico.
		 * @param  integer $inactivoLocal que indica condicion del registro.
		 * @return array retorna un arreglo de atributos de la sede principal. Todos los atributos
		 * de la entidad respectiva.
		 */
		public static function getCualEsLaSedePrincipalSegunRIF($naturalezaLocal = '', $cedulaLocal = 0, $tipoLocal = 0, $inactivoLocal = 0)
		{
			$sede = null;
			$datos = self::getListaSucursalesSegunRIF($naturalezaLocal, $cedulaLocal, $tipoLocal, $inactivoLocal);

			if ( $datos !== false && $datos !== null ) {
				foreach ( $datos as $dato ) {
					if ( $dato['id_rif'] == 0 ) {
						$sede = $dato;
						break;
					}
				}
			}
			return $sede;
		}




	}
 ?>