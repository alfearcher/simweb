<?php

namespace backend\models\vehiculo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\vehiculo\VehiculosForm;

/**
 * VehiculosSearch represents the model behind the search form about `backend\models\VehiculosForm`.
 */
class VehiculosSearch extends VehiculosForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_vehiculo', 'id_contribuyente', 'uso_vehiculo', 'ano_compra', 'ano_vehiculo', 'no_ejes', 'liquidado', 'status_vehiculo', 'nro_puestos', 'clase_vehiculo', 'tipo_vehiculo'], 'integer'],
            [['placa', 'marca', 'modelo', 'color', 'fecha_inicio', 'medida_cap', 'serial_motor', 'serial_carroceria', 'nro_calcomania'], 'safe'],
            [['precio_inicial', 'exceso_cap', 'capacidad', 'peso'], 'number'],
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
        $query = VehiculosForm::find();

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
            'id_vehiculo' => $this->id_vehiculo,
            'id_contribuyente' => $this->id_contribuyente,
            'uso_vehiculo' => $this->uso_vehiculo,
            'precio_inicial' => $this->precio_inicial,
            'fecha_inicio' => $this->fecha_inicio,
            'ano_compra' => $this->ano_compra,
            'ano_vehiculo' => $this->ano_vehiculo,
            'no_ejes' => $this->no_ejes,
            'liquidado' => $this->liquidado,
            'status_vehiculo' => $this->status_vehiculo,
            'exceso_cap' => $this->exceso_cap,
            'capacidad' => $this->capacidad,
            'nro_puestos' => $this->nro_puestos,
            'peso' => $this->peso,
            'clase_vehiculo' => $this->clase_vehiculo,
            'tipo_vehiculo' => $this->tipo_vehiculo,
        ]);

        $query->andFilterWhere(['like', 'placa', $this->placa])
            ->andFilterWhere(['like', 'marca', $this->marca])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'medida_cap', $this->medida_cap])
            ->andFilterWhere(['like', 'serial_motor', $this->serial_motor])
            ->andFilterWhere(['like', 'serial_carroceria', $this->serial_carroceria])
            ->andFilterWhere(['like', 'nro_calcomania', $this->nro_calcomania]);

        return $dataProvider;
    }

    public function searchPlaca($params)
    {
        $query = VehiculosForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'placa' => $this->placa,
        ]);

        $query->andFilterWhere(['like', 'placa', $this->placa]);

        return $dataProvider;
    }
}
