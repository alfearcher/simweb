<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\registromaestro\DatosBasicoForm;

/**
 * DatosBasicoSearch represents the model behind the search form about `backend\models\Contribuyentes`.
 */
class DatosBasicoSearch extends Contribuyentes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_contribuyente', 'ente', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif', 'id_cp', 'inactivo', 'cuenta', 'num_reg', 'extension_horario', 'num_empleados', 'tipo_contribuyente', 'licencia', 'agente_retencion', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'foraneo', 'no_declara', 'econ_informal', 'grupo_contribuyente', 'no_sujeto'], 'integer'],
            [['naturaleza', 'nombres', 'apellidos', 'razon_social', 'representante', 'nit', 'fecha_nac', 'sexo', 'casa_edf_qta_dom', 'piso_nivel_no_dom', 'apto_dom', 'domicilio_fiscal', 'catastro', 'tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro', 'tlf_celular', 'fax', 'email', 'reg_mercantil', 'tomo', 'folio', 'fecha', 'horario', 'id_sim', 'nivel', 'fecha_inclusion', 'fecha_inicio', 'fe_inic_agente_reten', 'ruc'], 'safe'],
            [['capital'], 'number'],
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
        $query = Contribuyentes::find();

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
            'id_contribuyente' => $this->id_contribuyente,
            'ente' => $this->ente,
            'cedula' => $this->cedula,
            'tipo' => $this->tipo,
            'tipo_naturaleza' => $this->tipo_naturaleza,
            'id_rif' => $this->id_rif,
            'id_cp' => $this->id_cp,
            'fecha_nac' => $this->fecha_nac,
            'inactivo' => $this->inactivo,
            'cuenta' => $this->cuenta,
            'num_reg' => $this->num_reg,
            'fecha' => $this->fecha,
            'capital' => $this->capital,
            'extension_horario' => $this->extension_horario,
            'num_empleados' => $this->num_empleados,
            'tipo_contribuyente' => $this->tipo_contribuyente,
            'licencia' => $this->licencia,
            'agente_retencion' => $this->agente_retencion,
            'manzana_limite' => $this->manzana_limite,
            'lote_1' => $this->lote_1,
            'lote_2' => $this->lote_2,
            'lote_3' => $this->lote_3,
            'fecha_inclusion' => $this->fecha_inclusion,
            'fecha_inicio' => $this->fecha_inicio,
            'foraneo' => $this->foraneo,
            'no_declara' => $this->no_declara,
            'econ_informal' => $this->econ_informal,
            'grupo_contribuyente' => $this->grupo_contribuyente,
            'fe_inic_agente_reten' => $this->fe_inic_agente_reten,
            'no_sujeto' => $this->no_sujeto,
        ]);

        $query->andFilterWhere(['like', 'naturaleza', $this->naturaleza])
            ->andFilterWhere(['like', 'nombres', $this->nombres])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'representante', $this->representante])
            ->andFilterWhere(['like', 'nit', $this->nit])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'casa_edf_qta_dom', $this->casa_edf_qta_dom])
            ->andFilterWhere(['like', 'piso_nivel_no_dom', $this->piso_nivel_no_dom])
            ->andFilterWhere(['like', 'apto_dom', $this->apto_dom])
            ->andFilterWhere(['like', 'domicilio_fiscal', $this->domicilio_fiscal])
            ->andFilterWhere(['like', 'catastro', $this->catastro])
            ->andFilterWhere(['like', 'tlf_hab', $this->tlf_hab])
            ->andFilterWhere(['like', 'tlf_hab_otro', $this->tlf_hab_otro])
            ->andFilterWhere(['like', 'tlf_ofic', $this->tlf_ofic])
            ->andFilterWhere(['like', 'tlf_ofic_otro', $this->tlf_ofic_otro])
            ->andFilterWhere(['like', 'tlf_celular', $this->tlf_celular])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'reg_mercantil', $this->reg_mercantil])
            ->andFilterWhere(['like', 'tomo', $this->tomo])
            ->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'horario', $this->horario])
            ->andFilterWhere(['like', 'id_sim', $this->id_sim])
            ->andFilterWhere(['like', 'nivel', $this->nivel])
            ->andFilterWhere(['like', 'ruc', $this->ruc]);

        return $dataProvider;
    }
}
