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
    use frontend\models\solicitud\SolicitudSearchForm;
    use yii\data\ActiveDataProvider;
    use backend\models\configuracion\tiposolicitud\TipoSolicitud;


    /***/
    class SolicitudCreadaSearch extends Model
    {
        private $_id_contribuyente;
        public $impuesto;
        public $tipo_solicitud;
        public $fecha_desde;
        public $fecha_hasta;


        /***/
        public function __construct($idContribuyente)
        {
            $this->_id_contribuyente = $idContribuyente;
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
                                                         ->joinWith('estatusSolicitud')
                                                         ->orderBy([
                                                                'fecha_hora_creacion' => SORT_ASC,
                                                                'impuesto' => SORT_ASC,
                                                            ]);
            return isset($findModel) ? $findModel : null;
        }



        /***/
        private function findListaTipoSolicitudPendiente($arrayImpuesto)
        {
           if ( count($arrayImpuesto) > 0 ) {

                $findModel = SolicitudesContribuyente::find()->select('tipo_solicitud')
                                                             ->distinct()
                                                             ->where('id_contribuyente =:id_contribuyente',
                                                                            [':id_contribuyente' => $this->_id_contribuyente])
                                                             ->andWhere('estatus =:estatus', [':estatus' => 0])
                                                             ->andWhere(['in', 'impuesto', $arrayImpuesto])
                                                             ->orderBy([
                                                                    'impuesto' => SORT_ASC,
                                                                    'tipo_solicitud' => SORT_ASC,
                                                                ]);
            }

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
                                                         ->andWhere('estatus =:estatus', [':estatus' => 0])
                                                         ->orderBy([
                                                                'impuesto' => SORT_ASC,
                                                            ]);

            return isset($findModel) ? $findModel : null;
        }


        /**
         * Metodo que obtiene un arreglo de identificadores de registros de la entidad
         * "impuestos". Lo que se obtiene es una arreglo de valores del atributo "impuesto"
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




        /***/
        public function getListaTipoSolicitudPendiente($arrayImpuesto)
        {
            $arregloTipoSolicitud = [];
            $model = self::findListaTipoSolicitudPendiente($arrayImpuesto);
            if ( isset($model) ) {
                $tipos = $model->asArray()->all();
                if ( count($tipos) ) {
                    foreach ( $tipos as $tipo ) {
                        foreach ( $tipo as $key => $value ) {
                            $arregloTipoSolicitud[] = $value;
                        }
                    }
                }
            }
            return $arregloTipoSolicitud;
        }






        /**
         * Metodo para obtener un dataProvider que permita generar listados, el parametro
         * $inactivo es un arreglo del tipo:
         * [0,1,..,n].
         * @param  array  $inactivo arreglo de valores que debe contener el atributo "inactivo".
         * @return dataProvider.
         */
        public function getDataProviderSolicitudPendiente()
        {
            $query = self::findSolicitudCreada();

            $dataProvider = New ActiveDataProvider([
                'query' => $query,
            ]);


            if ( $this->_id_contribuyente > 0 ) {
                $query->andFilterWhere(['=', SolicitudesContribuyente::tableName().'.estatus', 0]);

                if ( $this->impuesto > 0 ) {
                    $query->andFilterWhere(['=', SolicitudesContribuyente::tableName().'.impuesto', $this->impuesto]);
                }

                if ( $this->tipo_solicitud > 0 ) {
                    $query->andFilterWhere(['=', 'tipo_solicitud', $this->tipo_solicitud]);
                }

                if ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' ) {
                    $query->andFilterWhere(['BETWEEN','date(fecha_hora_creacion)',
                                             date('Y-m-d',strtotime($this->fecha_desde)),
                                             date('Y-m-d',strtotime($this->fecha_hasta))]);
                }

            } else {
                // Si el campo no cumple las condiciones esto deberia regresar null.
                $query->where('0=1');

            }

            return $dataProvider;
        }



        /***/
        public function getViewListaTipoSolicitudSegunImpuesto($i)
        {
            // Lista de identificadorees de tipo de solicitud, asociadas al contribuyente
            // segun el o los impuestos.
            // $listaSolicitud = self::getListaTipoSolicitudPendiente([$i]);

            // $countSolicitud = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
            //                                        ->andWhere(['in', 'id_tipo_solicitud', $listaSolicitud])
            //                                        ->andwhere('inactivo =:inactivo', [':inactivo' => 0])
            //                                        ->count();

            // $solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])
            //                                     ->andWhere(['in', 'id_tipo_solicitud', $listaSolicitud])
            //                                     ->all();

            // if ( $countSolicitud > 0 ) {
            //     echo "<option value='0'>" . "Select..." . "</option>";
            //      foreach ( $solicitudes as $solicitud ) {
            //          echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
            //      }
            // } else {
            //      echo "<option> - </option>";
            // }

            // return $this;
        }



    }

 ?>