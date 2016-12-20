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
 *  @file Tasa.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-10-2015
 *
 *  @class Tasa
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
    namespace backend\models\utilidad\tiponaturalezaza;

    use Yii;


    /***/
    class TipoNaturaleza extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tipo_naturaleza';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['siglas_tnaturaleza', 'nb_naturaleza'], 'required'],
                [['siglas_tnaturaleza'], 'string', 'max' => 2],
                [['nb_naturaleza'], 'string', 'max' => 45],
                [['siglas_tnaturaleza'], 'unique'],
                [['nb_naturaleza'], 'unique']
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id_tipo_naturaleza' => Yii::t('backend', 'Clave Primaria de la tabla'),
                'siglas_tnaturaleza' => Yii::t('backend', 'Abreviacion del tipo de naturaleza, ej: J, V, E, D, G ... etc.'),
                'nb_naturaleza' => Yii::t('backend', 'Nombre del tipo de naturaleza, ej: Venezolano, Extranjero, Juridico, Gubernamental...etc.'),
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCondicionTipoNaturalezas()
        {
            return $this->hasMany(CondicionTipoNaturaleza::className(), ['fk_tipo_naturaleza' => 'id_tipo_naturaleza']);
        }
    }
