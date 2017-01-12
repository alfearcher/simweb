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
 *	@file GruposTrabajoSearch.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 12-08-2015
 * 
 *      @class GruposTrabajoSearch
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

namespace backend\models\grupotrabajo;

use Yii;
use yii\base\model;
use yii\data\ActiveDataProvider;
use backend\models\grupotrabajo\GruposTrabajoForm;

class GruposTrabajoSearch extends GruposTrabajoForm
{
    
     
    public $departamentoName;
    public $unidadName;
    public $inactivoName;
    
    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
        return  [
                    [ [ 'id_grupo' ], 'integer' ],
                    [ [ 'descripcion', 'departamentoName', 'unidadName' ], 'safe' ]
                ];
    }
    
    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        return Model::scenarios();
    }
   
    /**
    *   Metodo search(), la consulta principal de los grupo de trabajo al controllers.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function search( $params ) 
    {
            $query = GruposTrabajoForm::find();
             
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
                    'id_grupo',

                    'descripcion' => [
                        'asc' => [ 'grupos_trabajo.descripcion' => SORT_ASC ],
                        'desc' => [ 'grupos_trabajo.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'fecha' => [
                        'asc' => [ 'grupos_trabajo.fecha' => SORT_ASC ],
                        'desc' => [ 'grupos_trabajo.fecha' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'departamentoName' => [
                        'asc' => [ 'departamentos.descripcion' => SORT_ASC ],
                        'desc' => [ 'departamentos.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'unidadName' => [
                        'asc' => [ 'unidades_departamentos.descripcion' => SORT_ASC ],
                        'desc' => [ 'unidades_departamentos.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'inactivoName' => [
                        'asc' => [ 'grupos_trabajo.inactivo' => SORT_ASC ],
                        'desc' => [ 'grupos_trabajo.inactivo' => SORT_DESC ],
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
                $query->joinWith( [ 'departamento' ] );  
                $query->joinWith( [ 'unidad' ] );
                $query->andWhere( [ 'grupos_trabajo.inactivo' => '0' ] );
                
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
            $query->andWhere( 'grupos_trabajo.id_grupo LIKE "%' . $this->id_grupo . '%" ' );
            $query->andWhere( 'grupos_trabajo.descripcion LIKE "%' . $this->descripcion . '%" ' );
            $query->andWhere( 'grupos_trabajo.inactivo LIKE "%' . $this->inactivoName . '%" ' );
            $query->andWhere( [ 'grupos_trabajo.inactivo' => '0' ] );
            $query->joinWith( [ 'departamento' => function ( $q ) {
                    $q->where( 'departamentos.descripcion LIKE "%' . $this->departamentoName . '%"' );
            } ] );
            $query->joinWith( [ 'unidad' => function ( $q ) {
                    $q->where( 'unidades_departamentos.descripcion LIKE "%' . $this->unidadName . '%"' );
            } ] );
            
        return $dataProvider;
    }   
    
    protected function addCondition( $query, $attribute, $partialMatch = false )
    {
        if( ( $pos = strrpos($attribute, '.' ) ) !== false ) {
                    $modelAttribute = substr( $attribute, $pos + 1 );
        } else {
                    $modelAttribute = $attribute;
        }
            $value = $this->$modelAttribute;
        
        if(trim( $value ) === '' ) {
            return;
        }
 
        /* 
        * The following line is additionally added for right aliasing
        * of columns so filtering happen correctly in the self join
        */
        $attribute = "apuestas.$attribute";

            if ($partialMatch) {
                        $query->andWhere( [ 'like', $attribute, $value ] );
            } else {
                        $query->andWhere( [ $attribute => $value ] );
            }
    }
    
    
}

    
    

