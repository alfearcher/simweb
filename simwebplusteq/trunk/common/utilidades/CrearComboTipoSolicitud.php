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
 *  @file ArmarComboTipoSolicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-07-2017
 *
 *  @class ArmarComboTipoSolicitud
 *  @brief Clase Modelo
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

	namespace common\utilidades;

 	use Yii;
	use yii\data\ActiveDataProvider;
	use yii\data\ArrayDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;




	/***/
	class CrearComboTipoSolicitud
	{

	    /**
	     * Metodo que realiza la consultas del tipo de solicitud segun el impuesto.
	     * Y retorna los atributos de la entidad respectiva.
	     * @param integer $impuesto identificador del impuesto.
	     * @return array.
	     */
	    public function findTipoSolicitudByImpuesto($impuesto)
	    {
	    	return $model = TipoSolicitud::find()->alias('T')
	    										 ->where('impuesto =:impuesto',
	    										 					[':impuesto' => $impuesto])
	    										 ->orderBy([
	    										 	'impuesto' => SORT_ASC,
	    										 	'descripcion' => SORT_ASC,
	    										 ])
	    										 ->all();
	    }


	    /**
	     * Metodo que arma un combo-lista de tipos de solicitudes.
	     * @param integer $impuesto identificador del impuesto.
	     * @return array
	     */
	    public function getComboTipoSolicitud($impuesto)
	    {
	    	$listaTipoSolicitud = self::findTipoSolicitudByImpuesto($impuesto);
	    	if ( count($listaTipoSolicitud) > 0 ) {
	        	echo "<option value='0'>" . "Select..." . "</option>";
	            foreach ( $listaTipoSolicitud as $solicitud ) {
	                echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
	            }
	        } else {
	            echo "<option value='0'>" . "Select..." . "</option>";
	        }
	        return;
	    }
	}
?>