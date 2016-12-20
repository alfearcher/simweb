<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Departamento;

/**
 * DepartamentoSearch represents the model behind the search form about `backend\models\Departamento`.
 */
class DepartamentoSearch extends Departamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_departamento', 'inactivo'], 'integer'],
            [['descripion'], 'safe'],
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
        $query = Departamento::find();

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
            'id_departamento' => $this->id_departamento,
            'inactivo' => $this->inactivo,
        ]);

        $query->andFilterWhere(['like', 'descripion', $this->descripion]);

        return $dataProvider;
    }
}
