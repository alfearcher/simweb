<?php

namespace backend\models\configuracion\convenios;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\configuracion\convenios\ConfigConvenios;

/**
 * ConfigConveniosSearch represents the model behind the search form about `backend\models\configuracion\convenios\ConfigConvenios`.
 */
class ConfigConveniosSearch extends ConfigConvenios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_config_convenio', 'impuesto', 'tipo_monto', 'ano_ut', 'solo_deuda_morosa', 'tipo_periodo', 'nro_max_cuotas', 'lapso_tiempo', 'id_tiempo', 'vcto_dif_ano', 'aplicar_interes', 'id_impuesto', 'inactivo'], 'integer'],
            [['monto_minimo', 'monto_inicial', 'porcentaje_inicial', 'interes'], 'number'],
            [['usuario', 'fecha_hora'], 'safe'],
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ConfigConvenios::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_config_convenio' => $this->id_config_convenio,
            'impuesto' => $this->impuesto,
            'monto_minimo' => $this->monto_minimo,
            'tipo_monto' => $this->tipo_monto,
            'ano_ut' => $this->ano_ut,
            'solo_deuda_morosa' => $this->solo_deuda_morosa,
            'tipo_periodo' => $this->tipo_periodo,
            'monto_inicial' => $this->monto_inicial,
            'porcentaje_inicial' => $this->porcentaje_inicial,
            'nro_max_cuotas' => $this->nro_max_cuotas,
            'lapso_tiempo' => $this->lapso_tiempo,
            'id_tiempo' => $this->id_tiempo,
            'vcto_dif_ano' => $this->vcto_dif_ano,
            'aplicar_interes' => $this->aplicar_interes,
            'interes' => $this->interes,
            'id_impuesto' => $this->id_impuesto,
            'fecha_hora' => $this->fecha_hora,
            'inactivo' => $this->inactivo,
        ]);

        $query->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
