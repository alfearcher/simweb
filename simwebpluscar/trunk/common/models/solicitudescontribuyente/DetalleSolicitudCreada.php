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
 *  @file DetalleSolicitudCreada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18/05/2016
 *
 *  @class DetalleSolicitudCreada
 *  @brief
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *  getDb
 *  tableName
 *
 *
 *
 *
 *  @inherits
 *
 */

    namespace common\models\solicitudescontribuyente;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;
    use yii\helpers\Url;
    //use common\models\solicitudescontribuyente\SolicitudesContribuyente;
    use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
    use backend\controllers\aaee\solicitud\SolicitudViewActividadEconomicaController;



    /***/
    class DetalleSolicitudCreada extends SolicitudesContribuyenteForm
    {

        public $nro_solicitud;


        /**
         * Constructor de la clase.
         * @param Long $nroSolicitud identificador de la solicitud creada por el
         * funcionario o contribuyente.
         */
        public function __construct($nroSolicitud)
        {
            $this->nro_solicitud = $nroSolicitud;
        }


        /***/
        public function getDatosSolicitudCreada()
        {
            $datos = $this->findSolicitudContribuyente($this->nro_solicitud);
            if ( isset($datos) ) {
               // Se arma un indice de busqueda utilizando los campos:
               // impuesto.
               // tipo solicitud.
               // nivel aprobacion.
               // Con la intencion de localizar la ruta para renderizar la vista de la solicitud
               // respectiva. Este directorio de rutas para las vistas de los tipos de solicitudes
               // estara en un .php
               return self::getViewPorImpuesto($datos);
            }
        }


        /***/
        public function getViewPorImpuesto($model)
        {
            if ( $model->impuesto == 1 ) {
                $view = New SolicitudViewActividadEconomicaController($model);
                return $view->actionInicioView();

            }

            return false;
        }


    }

 ?>