<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *	> This library is free software; you can redistribute it and/or modify it under 
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *	> any later version.
 *      > 
 *	> This library is distributed in the hope that it will be usefull, 
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *	> for more details.
 *      > 
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**	
 *	@file ApuestasLicitaSearch.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 17-09-2015
 * 
 *      @class ApuestasLicitaSearch
 *	@brief Clase contiene las reglas de negocios ( Etiquetas, validaciones y busqueda ).
 * 
 *  
 *  
 *  @property
 *  
 *  @method
 *  
 *  @inherits
 *  
 */

namespace backend\models\apuestalicita;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\apuestalicita\ApuestasLicitaForm;

/**
 * ApuestasLicitaSearch represents the model behind the search form about `backend\models\apuestalicita\ApuestasLicitaForm`.
 */
class ApuestasLicitaSearch extends ApuestasLicitaForm
{
    public $contribuyenteName;
    public $inactivoName;
    
    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
        return [
                    [['id_impuesto', 'id_contribuyente'], 'integer'],
                    [['descripcion', 'direccion', 'contribuyenteName' ], 'safe'],
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
    *   Metodo search(), la consulta principal de las apuestas licitas al controllers.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function search( $params ) 
    {
            $query = ApuestasLicitaForm::find();
             
            $dataProvider = new ActiveDataProvider([
                    'query' => $query,
            ]);

            /**
             * Setup your sorting attributes
             * Note: This is setup before the $this->load($params) 
             * statement below
             * Permite realizar la forma de ordenar si es ascendente o descendente
             */
             $dataProvider->setSort( [
                'attributes' => [
                    'id_impuesto',

                    'contribuyenteName' => [
                        'asc' => [ 'contribuyentes.descripcion' => SORT_ASC ],
                        'desc' => [ 'contribuyentes.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'descripcion' => [
                        'asc' => [ 'apuestas.descripcion' => SORT_ASC ],
                        'desc' => [ 'apuestas.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'direccion' => [
                        'asc' => [ 'apuestas.direccion' => SORT_ASC ],
                        'desc' => [ 'apuestas.direccion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                    
                    'inactivoName' => [
                        'asc' => [ 'apuestas.status_apuesta' => SORT_ASC ],
                        'desc' => [ 'apuestas.status_apuesta' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                    
                    'fecha_creacion' => [
                        'asc' => [ 'apuestas.fecha_creacion' => SORT_ASC ],
                        'desc' => [ 'apuestas.fecha_creacion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ]
                ]
            ]);

            if( ! ( $this->load($params) && $this->validate() ) ) {
                
                /**
                 * The following line will allow eager loading with country data 
                 * to enable sorting by country on initial loading of the grid.
                 * Se especificas las tabla con las cuales se requieren hacer el joinWith
                 */ 
                
                $query->joinWith( [ 'contribuyente' ] );
                $query->andWhere( [ 'apuestas.status_apuesta' => '0' ] );
                $query->andWhere( [ 'apuestas.id_contribuyente' => $_SESSION['idContribuyente'] ] );
                
                return $dataProvider;
            }

            /*$this->addCondition($query, 'id_grupo');
            $this->addCondition($query, 'descripcion', true);
            $this->addCondition($query, 'fecha', true);
            $this->addCondition($query, 'fecha', true);
            $this->addCondition($query, 'inactivo', true);*/

            /**
            *  Filtros que permite hacer la busqueda en la base de datos para mostrar solos los 
            *  datos requeridos.
            */
            $query->andWhere( 'apuestas.id_impuesto LIKE "%' . $this->id_impuesto . '%" ' );
            $query->andWhere( 'apuestas.descripcion LIKE "%' . $this->descripcion . '%" ' );
            $query->andWhere( 'apuestas.direccion LIKE "%' . $this->direccion . '%" ' );
            $query->andWhere( [ 'apuestas.status_apuesta' => '0' ] );
            $query->joinWith( [ 'contribuyente' => function ( $q ) {
                    $q->where( 'contribuyentes.razon_social LIKE "%' . $this->contribuyenteName . '%"' );
            } ] );
            $query->andWhere( [ 'apuestas.id_contribuyente' => $_SESSION['idContribuyente'] ] );
            
            
        return $dataProvider;
    }   
    
}
