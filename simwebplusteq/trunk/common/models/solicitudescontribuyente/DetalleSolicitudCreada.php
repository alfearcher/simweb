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
    use backend\controllers\vehiculo\solicitud\SolicitudViewVehiculoController;
    use backend\controllers\inmueble\solicitud\SolicitudViewInmuebleController;
    use backend\controllers\propaganda\solicitud\SolicitudViewPropagandaController;



    /**
     * Clase que permite direccionar la busqueda de la informacion detalle de una solicitud
     * por tipo de impuesto. Utiliza como parametro de entrada el valor del numero de la
     * solicitud creada, para luego buscar la informacion restante con este parametro.
     */
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



        /**
         * Metodo que busca los datos de la solicitud de la entidad "solicitudes-contribuyente"
         * para luego enviarlos como parametros a un metodo que se encargara de renderizar las
         * vistas por tipo de solicitud.
         * @return View Retorna una vista segun el tipo de solicitud o false si no encuentra la
         * vista.
         */
        public function getDatosSolicitudCreada()
        {
            // find sobre SoliicitudesContribuyente.
            $datos = $this->findSolicitudContribuyente($this->nro_solicitud);
            if ( isset($datos) ) {
               return self::getViewPorImpuesto($datos);
            }
            return false;
        }


        /**
         * Metodo que permite renderizar las vistas de las solicitudes por impuesto.
         * @param  Active Record $model modelo de la solicitud maestro, que previamente
         * fue localizada utilizando un find.
         * @return View Retorna una vista con la informacion de los detalles particulares
         * de la solicitud, sino encuentra los detalles retornara false.
         */
        public function getViewPorImpuesto($model)
        {
            if ( $model->impuesto == 1 ) {
                $view = New SolicitudViewActividadEconomicaController($model);
                return $view->actionInicioView();

            } elseif ( $model->impuesto == 2 ) {
                $view = New SolicitudViewInmuebleController($model);
                return $view->actionInicioView();

            } elseif ( $model->impuesto == 3 ) {
                 $view = New SolicitudViewVehiculoController($model);
                return $view->actionInicioView();

            } elseif ( $model->impuesto == 4 ) {
                $view = New SolicitudViewPropagandaController($model);
                return $view->actionInicioView();

            } elseif ( $model->impuesto == 6 ) {

            } elseif ( $model->impuesto == 7 ) {

            } elseif ( $model->impuesto == 10 ) {

            } elseif ( $model->impuesto == 11 ) {

            } elseif ( $model->impuesto == 12 ) {

            }

            return false;
        }


    }

 ?>