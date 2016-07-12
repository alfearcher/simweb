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
 *  @file SolicitudCreadaSearch.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 12/07/2016
 *
 *  @class SolicitudCreadaSearch
 *  @brief Modelo que instancia
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

    namespace frontend\models\solicitud;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;
    use common\models\solicitudescontribuyente\SolicitudesContribuyente;
    use yii\data\ActiveDataProvider;


    /***/
    class SolicitudCreadaSearch extends ActiveRecord
    {
        private $_id_contribuyente;
        private $_impuesto;
        private $_tipo_solicitud;
        private $_fecha_desde;
        private $_fecha_hasta;


        /***/
        public function __construct($idContribuyente, $impuesto = 0)
        {
            $this->_id_contribuyente = $idContribuyente;
            self::setImpuesto($impuesto);
        }


        /***/
        public function setImpuesto($impuesto)
        {
            $this->_impuesto = $impuesto;
        }

        /***/
        public function getImpuesto()
        {
            return $this->_impuesto;
        }


        /***/
        public function setTipoSolicitud($tipoSolicitud)
        {
            $this->_tipo_solicitud = $tipoSolicitud;
        }



        /**
         * Metodo que permite buscar una solicitud creada y que este pendiente para su procesamiento.
         * La busqueda la realza a a traves del parametro
         * @return Active Record
         */
        public function findSolicitudCreada()
        {
            $findModel = SolicitudesContribuyente::find()->where(SolicitudesContribuyente::.'.id_contribuyente =:id_contribuyente',
                                                                        [':id_contribuyente' => $this->_id_contribuyente])
                                                         ->joinWith('tipoSolicitud')
                                                         ->jointWith('impuestos')
                                                         ->joinWith('nivelAprobacion')
                                                         ->orderBy([
                                                                'fecha_hora' => SORT_ASC,
                                                                'impuesto' => SORT_ASC,
                                                            ])
            return isset($findModel) ? $findModel : null;
        }



        /**
         * Metodo para obtener un dataProvider que permita generar listados, el parametro
         * $inactivo es un arreglo del tipo:
         * [0,1,..,n].
         * @param  array  $inactivo arreglo de valores que debe contener el atributo "inactivo".
         * @return dataProvider.
         */
        public function getDataProviderSolicitudPendiente($inactivo = [])
        {
            $query = self::findSolicitudCreada();

            $dataProvider = New ActiveDataProvider([
                'query' => $query,
            ]);
            if ( count($inactivo) > 0 ) {
                $query->andFilterWhere(['in', 'inactivo', $inactivo]);

                if ( $this->_impuesto > 0 ) {
                    $query->andFilterWhere(['=', 'impuesto', $this->_impuesto]);
                }

                if ( $this->_tipo_solicitud > 0 ) {
                    $query->andFilterWhere(['=', 'tipo_solicitud', $this->_tipo_solicitud]);
                }
            } else {
                // Si el campo no cumple las condiciones esto deberia regresar null.
                $query->where('0=1');

            }

            return $dataProvider;
        }


    }

 ?>