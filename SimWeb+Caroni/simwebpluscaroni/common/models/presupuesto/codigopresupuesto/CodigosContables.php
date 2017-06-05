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
 *  @file NivelesContables.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 22/09/2016
 *
 *  @class NivelesContables
 *  @brief Modelo que instancia la conexion a la base de datos para buscar datos de la tabla niveles_contables.
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

    namespace common\models\presupuesto\codigopresupuesto;

    use Yii;
    use yii\base\Model;
    use common\models\Users;
    use yii\db\ActiveRecord;
    use backend\models\configuracion\tiposolicitud\TipoSolicitud;
    use backend\models\impuesto\Impuesto;
    use backend\models\funcionario\solicitud\FuncionarioSolicitud;
    use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;
    use backend\models\configuracion\nivelaprobacion\NivelAprobacion;
    use backend\models\utilidad\causanegacionsolicitud\CausaNegacionSolicitud;
    use backend\models\solicitud\estatus\EstatusSolicitud;
    use backend\models\propaganda\Propaganda;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\TiposPropaganda;
    use common\models\presupuesto\nivelespresupuesto\NivelesContables;
    use backend\models\tasa\Tasa;

    class CodigosContables extends ActiveRecord
    {
        public $cod;

        public static function getDb()
        {
          return Yii::$app->db;
        }


        /**
         *  Metodo que retorna el nombre de la tabla que utiliza el modelo.
         *  @return Nombre de la tabla del modelo.
         */
        public static function tableName()
        {
          return 'codigos_contables';
        }

        public function getNivelPresupuesto()
        {
            return $this->hasOne(NivelesContables::className(), ['nivel_contable' => 'nivel_contable']);
        }

        public function getDescripcionCodigoContable($codigo)
        {
            //die(var_dump($codigo));
            $model = CodigosContables::find()
                                    ->where(['id_codigo' => $codigo])
                                    ->one();
           return $model->descripcion;
        }


        /**
         * Relacion eon la entidad "varios"
         * @return Tasa
         */
        public function getTasa()
        {
            return $this->hasOne(Tasa::className(), ['id_codigo' => 'id_codigo']);
        }






 }


 ?>