<?php

namespace backend\models\vehiculo\calcomania;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm;

/**
 * FuncionarioCalcomaniaSearch represents the model behind the search form about `backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm`.
 */
class FuncionarioCalcomaniaSearch extends FuncionarioCalcomaniaForm
{
    public $funcionarioName;
    public $funcionarioApellido;
    public $funcionarioCargo;
    public $rango_inicial;
    public $loteCalcomaniaRangoInicial;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_funcionario_calcomania', 'id_funcionario', 'estatus', 'ci'], 'integer'],
            [['naturaleza'], 'string'],
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
        $query = FuncionarioCalcomaniaForm::find();

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
            'id_funcionario_calcomania' => $this->id_funcionario_calcomania,
            'id_funcionario' => $this->id_funcionario,
            'estatus' => $this->estatus,
            'naturaleza' => $this->naturaleza,
            'ci' => $this->ci,
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchFuncionarioCalcomaniaList($params)
    {
        $query = FuncionarioCalcomaniaForm::find();
             
            $dataProvider = new ActiveDataProvider([
                    'query' => $query,
            ]);
            
            /**
             * Setup your sorting attributes
             * Note: This is setup before the $this->load($params) 
             * statement below
             * Permite realizar la forma de ordenar si es ascendente o descendente
             */
             $dataProvider->setSort([
                'attributes' => [
                    'id_funcionario' => [
                        'asc' => ['funcionario_calcomania.id_funcionario' => SORT_ASC],
                        'desc' => ['funcionario_calcomania.id_funcionario' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'funcionarioName' => [
                        'asc' => ['funcionarios.nombres' => SORT_ASC],
                        'desc' => ['funcionarios.nombres' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'funcionarioApellido' => [
                        'asc' => ['funcionarios.apellidos' => SORT_ASC],
                        'desc' => ['funcionarios.apellidos' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'naturaleza' => [
                        'asc' => ['funcionario_calcomania.naturaleza' => SORT_ASC],
                        'desc' => ['funcionario_calcomania.naturaleza' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'ci' => [
                        'asc' => ['funcionario_calcomania.ci' => SORT_ASC],
                        'desc' => ['funcionario_calcomania.ci' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'estatus' => [
                        'asc' => ['funcionario_calcomania.estatus' => SORT_ASC],
                        'desc' => ['funcionario_calcomania.estatus' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                ]
            ]);
             
            if(!($this->load($params) && $this->validate())){
                /**
                 * The following line will allow eager loading with country data 
                 * to enable sorting by country on initial loading of the grid.
                 * Se especificas las tabla con las cuales se requieren hacer el joinWith
                 */ 
                $query->joinWith(['funcionarios']);
                $query->andWhere(['funcionario_calcomania.estatus' => 1]);
                // $query->joinWith(['lote_calcomania']);
                // $query->andWhere(['lote_calcomania.id_lote_calcomania' => 'funcionario_calcomania.id_lote_calcomania']);
               // $query->andWhere(['lote_calcomania.ano_impositivo' => date('Y')]);
                return $dataProvider;
            }

            /*$this->addCondition($query, 'id_impuesto');
            $this->addCondition($query, 'ano_impositivo', true);
            $this->addCondition($query, 'fecha_guardado', true);
            $this->addCondition($query, 'inactivo', true);*/
            
            
            return $dataProvider;
    }
}
