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
 *  @file UnidadDepartamento.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-09-2015
 *
 *  @class UnidadDepartamento
 *  @brief Clase Modelo que maneja la politica de validaciones del formulario que se
 *  @brief utiliza la
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



    namespace backend\models\utilidad\unidaddepartamento;

    use Yii;
    use yii\db\ActiveRecord;
    use backend\models\utilidad\departamento\Departamento;
    use backend\models\funcionario\Funcionario;



/**
 *
 */
    class UnidadDepartamento extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'unidades_departamentos';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['id_unidad', 'id_departamento'], 'required'],
                [['id_unidad', 'id_departamento', 'inactivo'], 'integer'],
                [['descripion'], 'string', 'max' => 45]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id_unidad' => Yii::t('backend', 'Id Unidad'),
                'id_departamento' => Yii::t('backend', 'Id Departamento'),
                'descripion' => Yii::t('backend', 'Descripion'),
                'inactivo' => Yii::t('backend', 'Inactivo'),
            ];
        }



         /**
         * Relacion con la entidad "funcionarios"
         * @return [type] [description]
         */
        public function getFuncionario()
        {
            return $this->hasMany(Funcionario::className(), ['id_unidad' => 'id_unidad']);
        }



        /**
         * Relacion con la entidad "Departamento".
         */
        public function getDepartamento()
        {
            return $this->hasOne(Departamento::className(), ['id_departamento' => 'id_departamento']);
        }


    }

?>