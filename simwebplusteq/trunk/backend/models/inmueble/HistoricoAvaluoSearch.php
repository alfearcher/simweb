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
 *  @file HistoricoAvaluoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-11-2016
 *
 *  @class HistoricoAvaluoSearch
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

	namespace backend\models\inmueble;

 	use Yii;
	use backend\models\inmueble\avaluo\HistoricoAvaluo;
	use backend\models\inmueble\LinderosForm;




	/**
	* Clase
	*/
	class HistoricoAvaluoSearch extends LinderosForm
	{
		
        public static function tableName()
        {
            return 'historicos_avaluos';
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


        $query = LinderosForm::find()->where(['id_contribuyente' => $_SESSION['idContribuyente']])->andWhere(['inactivo' => 0]);
        //$query = InmueblesUrbanosForm::find();
        
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
            'id_impuesto' => $this->id_impuesto,
            'id_contribuyente' => $this->id_contribuyente,
            'ano_inicio' => $this->ano_inicio,
            'liquidado' => $this->liquidado,
            'manzana_limite' => $this->manzana_limite,
            'lote_1' => $this->lote_1,
            'lote_2' => $this->lote_2,
            'lote_3' => $this->lote_3,
            'inactivo' => $this->inactivo,
            'id_habitante' => $this->id_habitante,
            'tipo_ejido' => $this->tipo_ejido,
            'propiedad_horizontal' => $this->propiedad_horizontal,
            'estado_catastro' => $this->estado_catastro,
            'municipio_catastro' => $this->municipio_catastro,
            'parroquia_catastro' => $this->parroquia_catastro,
            'sector_catastro' => $this->sector_catastro,
            'manzana_catastro' => $this->manzana_catastro,
            'parcela_catastro' => $this->parcela_catastro,
            'subparcela_catastro' => $this->subparcela_catastro,
            'unidad_catastro' => $this->unidad_catastro,
        ]);

        $query->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'nivel', $this->nivel])
            ->andFilterWhere(['like', 'av_calle_esq_dom', $this->av_calle_esq_dom])
            ->andFilterWhere(['like', 'casa_edf_qta_dom', $this->casa_edf_qta_dom])
            ->andFilterWhere(['like', 'piso_nivel_no_dom', $this->piso_nivel_no_dom])
            ->andFilterWhere(['like', 'apto_dom', $this->apto_dom])
            ->andFilterWhere(['like', 'tlf_hab', $this->tlf_hab])
            ->andFilterWhere(['like', 'medidor', $this->medidor])
            ->andFilterWhere(['like', 'id_sim', $this->id_sim])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'catastro', $this->catastro])
            ->andFilterWhere(['like', 'ambito_catastro', $this->ambito_catastro])
            ->andFilterWhere(['like', 'nivel_catastro', $this->nivel_catastro]);

        return $dataProvider;
    }





		

	}
?>