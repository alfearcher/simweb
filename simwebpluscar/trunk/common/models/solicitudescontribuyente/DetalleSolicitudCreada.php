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
    //use common\models\solicitudescontribuyente\SolicitudesContribuyente;
    use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;



    /***/
    class DetalleSolicitudCreada extends SolicitudesContribuyenteForm
    {

        private $nro_solicitud;


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
        private function getDatosSolicitudCreada()
        {
            $solicitud = New SolicitudesContribuyenteForm();
            $datos = $solicitud->getParametroSolicitudContribuyente($this->nro_solicitud, ['nro_solicitud',
                                                                                           'tipo_solicitud',
                                                                                           'nivel_aprobacion']);
            if ( couunt($datos))
        }



    }

 ?>