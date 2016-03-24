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
 *  @file LiquidacionActividadEconomica.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class LiquidacionActividadEconomica
 *  @brief Clase Modelo que maneja la politica
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

	namespace common\models\calculo\liquidacion\aaee;

 	use Yii;
	use yii\db\ActiveRecord;
	use backend\models\aaee\rubro\Rubro;

	/**
	* 	Clase que gestiona el calculo anual del impuesto por actividad economica,
	*
	*/
	class LiquidacionActividadEconomica
	{

		public $_calculoAnual;
		public $_calculoPorRubro;
		public $_idContribuyente;
		public $_anoImpositivo;
		public $_mesImpositivo;


		public function __construct($idContribuyente, $anoImpositivo)


	}

?>