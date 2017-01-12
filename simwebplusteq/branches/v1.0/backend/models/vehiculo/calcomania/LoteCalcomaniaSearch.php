<?php

namespace backend\models\vehiculo\calcomania;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\vehiculo\calcomania\LoteCalcomaniaForm;

/**
 * LoteCalcomaniaSearch represents the model behind the search form about `backend\models\vehiculo\calcomania\LoteCalcomaniaForm`.
 */
class LoteCalcomaniaSearch extends LoteCalcomaniaForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_lote_calcomania', 'ano_impositivo', 'rango_inicial', 'rango_final', 'inactivo'], 'integer'],
            [['observacion', 'causa'], 'safe'],
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
        $query = LoteCalcomaniaForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_lote_calcomania' => $this->id_lote_calcomania,
            'ano_impositivo' => $this->ano_impositivo,
            'rango_inicial' => $this->rango_inicial,
            'rango_final' => $this->rango_final,
            'inactivo' => $this->inactivo,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'causa', $this->causa]);
        
        return $dataProvider;
    }

    public function getEstatus($inactivo){
        if ($inactivo == 0) {
            return 'Activo';
        }else{
            return 'Inactivo';
        }
    }
}
