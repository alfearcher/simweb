<?php

namespace backend\models\vehiculo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\vehiculo\VehiculosForm;
use backend\models\vehiculo\TiposVehiculos;
use backend\models\vehiculo\ClasesVehiculos;
use backend\models\vehiculo\UsosVehiculos;
use common\models\vehiculo\desincorporaciones\CausasDesincorporaciones;

/**
 * VehiculoSearch represents the model behind the search form about `backend\models\VehiculosForm`.
 */
class VehiculoSearch extends VehiculosForm
{
    /**
     * [getDescripcionTipoVehiculo description]
     * @param  [type] $tipo [description]
     * @return [type]       [description]
     */
    public function getDescripcionTipoVehiculo($tipo)
    {
        $model = TiposVehiculos::findOne($tipo);
        return $model->descripcion;

    }

    /**
     * [getDescripcionUsoVehiculo description]
     * @param  [type] $uso [description]
     * @return [type]      [description]
     */
    public function getDescripcionUsoVehiculo($uso)
    {
        $model = UsosVehiculos::findOne($uso);
        return $model->descripcion;

    }

    /**
     * [getDescripcionClaseVehiculo description]
     * @param  [type] $clase [description]
     * @return [type]        [description]
     */
    public function getDescripcionClaseVehiculo($clase)
    {
        $model = ClasesVehiculos::findOne($clase);
        return $model->descripcion;

    }


   /**
    * [getDescripcionClaseVehiculo description]
    * @param  [type] $clase [description]
    * @return [type]        [description]
    */
    public function getDescripcionCausaDesincorporacion($causa)
    {
        $model = CausasDesincorporaciones::findOne($causa);
        return $model->descripcion;

    }


   
}
