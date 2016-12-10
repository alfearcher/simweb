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
    use frontend\models\solicitud\SolicitudCreadaSearch;


    /***/
    class SolicitudSearchForm extends SolicitudCreadaSearch
    {
        public $id_contribuyente;
        public $impuesto;
        public $tipo_solicitud;
        public $fecha_desde;
        public $fecha_hasta;

        const SCENARIO_SEARCH = 'search';
        const SCENARIO_SEARCH_ALL = 'search_all';


        /***/
        public function __construct($idContribuyente)
        {
            $this->id_contribuyente = $idContribuyente;
            parent::__construct($idContribuyente);
        }




        /**
        * @inheritdoc
        */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH => [
                            'id_contribuyente',
                            'impuesto',
                            'tipo_solicitud',
                            'fecha_desde',
                            'fecha_hasta',

                ],
                self::SCENARIO_SEARCH_ALL => [
                    'id_contribuyente',
                ]
            ];
        }



    /**
         *  Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
         */
        public function rules()
        {
            return [
                [['id_contribuyente', 'impuesto'],
                  'required', 'on' => 'search',
                  'message' => Yii::t('backend', '{attribute} is required')],
                [['id_contribuyente'],
                  'required', 'on' => 'search_all',
                  'message' => Yii::t('backend', '{attribute} is required')],
                [['id_contribuyente', 'impuesto', 'tipo_solicitud'],
                  'integer','on' => 'search',
                   'message' => Yii::t('backend', '{attribute} wild be integer')],
                [['fecha_hasta'], 'required',
                  'when' => function($model) {
                                if ( $model->fecha_desde != null ) {
                                    return true;
                                }
                            }
                , 'message' => Yii::t('backend', '{attribute} is required')],
                [['fecha_desde'], 'required',
                  'when' => function($model) {
                                if ( $model->fecha_hasta != null ) {
                                    return true;
                                }
                            }
                , 'message' => Yii::t('backend', '{attribute} is required')],
                [['fecha_hasta'], 'compare',
                  'compareAttribute' => 'fecha_desde', 'operator' => '>='],
                [['fecha_desde', 'fecha_hasta'], 'default', 'value' => null],
                ['id_contribuyente', 'default', 'value' => isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null],
            ];
        }



        /**
        *   Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
        *   @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
        */
        public function attributeLabels()
        {
            return [
                'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
                'nro_solicitud' => Yii::t('backend', 'Number Request'),
                'impuesto' => Yii::t('backend', 'Tax'),
                'tipo_solicitud' => Yii::t('backend', 'Type of request'),
                'fecha_desde' => Yii::t('backend', 'Start Date'),
                'fecha_hasta' => Yii::t('backend', 'End Date'),

            ];
        }



    }

 ?>