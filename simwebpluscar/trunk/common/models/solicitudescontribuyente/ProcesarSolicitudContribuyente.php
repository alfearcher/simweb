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
 *  @file ProcesarSolicitudContribuyente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07/06/2016
 *
 *  @class ProcesarSolicitudContribuyente
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
 *
 *
 *
 *
 *  @inherits
 *
 */

    namespace common\models\solicitudescontribuyente;

    use Yii;
    //use yii\base\Model;
    //use yii\db\ActiveRecord;
    use yii\helpers\Url;
    use common\models\solicitudescontribuyente\SolicitudesContribuyente;
    use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;



    /**
     * Clase que procesa la solicitud, es decir, actualiza los valores de la solicitud
     * creada una vez que la misma es procesada. El procesamiento de la solicitud
     * consiste en una aprobacion o negacion de la misma.
     */
    class ProcesarSolicitudContribuyente extends SolicitudesContribuyenteForm
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
            // find sobre SoliicitudesContribuyente.
            // Metodo clase padre.
            $datos = $this->findSolicitudContribuyente($this->nro_solicitud);
            return isset($datos) ? $datos : null;
        }




    }

 ?>