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
 *  @file SlInmueblesUrbanosSearch.php
 *
 *  @author Alvaro Jose Fernandez Archer
 *
 *  @date 25-05-2016
 *
 *  @class SlInmueblesUrbanosSearch
 *  @brief Clase Modelo que maneja la politica de busqueda
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

	namespace backend\models\inmueble;

 	use Yii;
	//use yii\base\Model;
	//use yii\db\ActiveRecord;
	use common\models\contribuyente\ContribuyenteBase;
	
	/**
	* 	Clase
	*/
	class SlInmueblesRegistrosSearch extends SlInmueblesRegistrosForm
	{

		

	    /**
	     * Metodo que realiza una busqueda del detalle de la solicitud (model)
	     * "inscripciones-actividad-economica".
	     * @param  Long $nroSolicitud identificador de la entidad "solicitudes-contribuyente".
	     * @return Active Record.
	     */
	    public function findInmueblesRegistros($nroSolicitud)
	    {
	    	$modelFind = SlInmueblesRegistrosForm::find()->where('nro_solicitud =:nro_solicitud', [':nro_solicitud' => $nroSolicitud])
	    													  //->andWhere('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->id_contribuyente])
	    													  ->one();

	    	return isset($modelFind) ? $modelFind : null; 
	    }

	    
	  
  
	}

?>