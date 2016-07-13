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
            $findModel = SolicitudesContribuyente::find()->where(SolicitudesContribuyente::tableName().'.id_contribuyente =:id_contribuyente',
                                                                        [':id_contribuyente' => $this->_id_contribuyente])
                                                         ->joinWith('tipoSolicitud')
                                                         ->joinWith('impuestos')
                                                         ->joinWith('nivelAprobacion')
                                                         ->orderBy([
                                                                'fecha_hora' => SORT_ASC,
                                                                'impuesto' => SORT_ASC,
                                                            ]);
            return isset($findModel) ? $findModel : null;
        }




        /**
         * Metodo que determina un arreglo de indices que representa los impuestos
         * presentes en las solicitudes realizadas por el contribueyente, el parametro
         * $inactivo es un arreglo de la forma:
         * [0,1,...,n], donde cada valor indica el estatus del registro de la solicitud
         * que se quieren consultar.
         * @param  array  $inactivo arreglo de valores que debe contener el atributo "inactivo".
         * @return array retorna un arreglo con los identificadores de los impuestos.
         */
        protected function getListaImpuestoSegunSolicitudes($inactivo = [])
        {
             $findModel = SolicitudesContribuyente::find()->where('id_contribuyente =:id_contribuyente',
                                                                        [':id_contribuyente' => $this->_id_contribuyente])
                                                         ->andWhere('inactivo  in :inactivo', [':inactivo' => $inactivo])
                                                         ->joinWith('impuestos')
                                                         ->orderBy([
                                                                'impuesto' => SORT_ASC,
                                                            ]);

            // $modelFind = SolicitudesContribuyente::find()->select('impuesto')
            //                                                  ->distinct()
            //                                                  ->where('id_contribuyente =:id_contribuyente',
            //                                                             [':id_contribuyente' => $this->_id_contribuyente]);


            return isset($findModel) ? $findModel : null;
        }




        /**
         * Metodo que realiza una consulta donde se obtendra una lista de valores
         * del atributo "impuesto", de las solicitudes pendientes que esten asocidas
         * al contribuyente.
         * @return Active Record retorna el modelo de la consulta.
         */
        private function findListaImpuestoSolicitudPendiente()
        {
            $findModel = SolicitudesContribuyente::find()->select('impuesto')
                                                         ->distinct()
                                                         ->where('id_contribuyente =:id_contribuyente',
                                                                        [':id_contribuyente' => $this->_id_contribuyente])
                                                         ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
                                                         ->orderBy([
                                                                'impuesto' => SORT_ASC,
                                                            ]);

            return isset($findModel) ? $findModel : null;
        }




        /**
         * Metodo que obtiene un arreglo de identificadores de registros de la entidad
         * "impuestos".
         * $model->asArray()->all() tiene la siguiente estructura:
         * array(2) {
         *       [0]=>
         *         array(1) {
         *           ["impuesto"]=>
         *           string(1) "2"
         *         }
         *         [1]=>
         *         array(1) {
         *           ["impuesto"]=>
         *           string(1) "3"
         *         }
         *   }
         * @return array retorna una arreglo de valores (no repetidos) del atributo "impuesto".
         * Estructura de lo retornado ($arregloImpuesto):
         * array(2) {
         *     [0]=>
         *     string(1) "2"
         *     [1]=>
         *     string(1) "3"
         *   }
         */
        public function getListaImpuestoSolicitudPendiente()
        {
            $impuestos = [];
            $arregloImpuesto = [];
            $model = self::findListaImpuestoSolicitudPendiente();
            if ( isset($model) ) {
                $impuestos = $model->asArray()->all();
                if ( count($impuestos) > 0 ) {
                    foreach ( $impuestos as $impuesto ) {
                        foreach ( $impuesto as $key => $value ) {
                            $arregloImpuesto[] = $value;
                        }
                    }
                }
            }
            return $arregloImpuesto;
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