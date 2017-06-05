<?php
/**
 *      @copyright © by ASIS CONSULTORES 2012 - 2016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *      > This library is free software; you can redistribute it and/or modify it under 
 *      > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *      > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *      > any later version.
 *      > 
 *      > This library is distributed in the hope that it will be usefull, 
 *      > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *      > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *      > for more details.
 *      > 
 *      > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *      @file PropagandaSearch.php
 *  
 *      @author Ronny Jose Simosa Montoya
 * 
 *      @date 18-08-2015
 * 
 *      @class PropagandaSearch
 *      @brief Clase contiene las reglas de negocios ( Etiquetas, validaciones y busqueda ).
 * 
 *  
 *  
 *      @property
 *  
 *      @method
 *  
 *      @inherits
 *  
 */

namespace backend\models\propaganda;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\propaganda\PropagandaForm;

class PropagandaSearch extends PropagandaForm
{ 
    public $usoName;
    public $claseName;
    public $inactivoName;
    public $contribuyenteName;  
  
    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
        return [
            [ ['id_impuesto', 'ano_impositivo' ], 'integer' ],
            [ ['direccion', 'fecha_desde', 'observacion', 'usoName', 'claseName', 'contribuyenteName', 'id_impuesto' ], 'safe' ]
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
    *   Metodo search(), la consulta principal de las propagandas al controllers.
    * 	@param $searchModel, array obtiene los valores filtrados por los campos de busqueda.
    * 	@param $dataProvider, array obtiene los valores de la consulta principal.
    */
    public function search( $params ) 
    {
            $query = PropagandaForm::find();
            
            $dataProvider = new ActiveDataProvider( [
                    'query' => $query,
            ] );

            /**
             * Setup your sorting attributes
             * Note: This is setup before the $this->load($params) 
             * statement below
             * Permite realizar la forma de ordenar si es ascendente o descendente
             */
             $dataProvider->setSort( [
                'attributes' => [
                    'id_impuesto',

                    'ano_impositivo' => [
                        'asc' => [ 'propagandas.ano_impositivo' => SORT_ASC ],
                        'desc' => [ 'propagandas.ano_impositivo' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'contribuyenteName' => [
                        'asc' => [ 'contribuyentes.razon_social' => SORT_ASC ],
                        'desc' => [ 'contribuyentes.razon_social' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                    
                    'claseName' => [
                        'asc' => [ 'clases_propagandas.descripcion' => SORT_ASC ],
                        'desc' => [ 'clases_propagandas.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                    
                    'usoName' => [
                        'asc' => [ 'usos_propagandas.descripcion' => SORT_ASC ],
                        'desc' => [ 'usos_propagandas.descripcion' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'fecha_guardado' => [
                        'asc' => [ 'propagandas.fecha_guardado' => SORT_ASC ],
                        'desc' => [ 'propagandas.fecha_guardado' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],

                    'inactivoName' => [
                        'asc' => [ 'propagandas.inactivo' => SORT_ASC ],
                        'desc' => [ 'propagandas.inactivo' => SORT_DESC ],
                        'default' => SORT_ASC
                    ],
                ]
            ]);

            if( ! ($this->load($params) && $this->validate() ) ) {
                
                /**
                 * The following line will allow eager loading with country data 
                 * to enable sorting by country on initial loading of the grid.
                 * Se especificas las tabla con las cuales se requieren hacer el joinWith
                 */ 
                $query->joinWith( [ 'uso' ] );
                $query->joinWith( [ 'clase' ] );
                $query->joinWith( [ 'contribuyente' ] );
                $query->andWhere( [ 'propagandas.inactivo' => '0' ] );
                $query->andWhere( [ 'propagandas.id_contribuyente' => $_SESSION['idContribuyente'] ] );
                
                return $dataProvider;
            }

            $this->addCondition( $query, 'id_impuesto' );
            $this->addCondition( $query, 'ano_impositivo', true );
            $this->addCondition( $query, 'inactivo', true );
            
            /**
            *  Filtros que permite hacer la busqueda en la base de datos para mostrar solos los 
            *  datos requeridos.
            */
            $query->andWhere( 'propagandas.id_impuesto LIKE "%' . $this->id_impuesto . '%" ' );
            $query->andWhere( 'propagandas.ano_impositivo LIKE "%' . $this->ano_impositivo . '%" ' );
            $query->andWhere( 'propagandas.inactivo LIKE "%' . $this->inactivoName . '%" ' );
            $query->andWhere( [ 'propagandas.inactivo' => '0' ] );
            $query->andWhere( [ 'propagandas.id_contribuyente' => $_SESSION['idContribuyente'] ] );
            
            $query->joinWith( [ 'uso' => function ( $q ){
                    $q->where( 'usos_propagandas.descripcion LIKE "%' . $this->usoName . '%"' );
            } ] );
            $query->joinWith( [ 'clase' => function ( $q ) {
                    $q->where( 'clases_propagandas.descripcion LIKE "%' . $this->claseName . '%"' );
            } ] );
            $query->joinWith( [ 'contribuyente' => function ( $q ) {
                    $q->where( 'contribuyentes.razon_social LIKE "%' . $this->contribuyenteName . '%"' );
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
        $attribute = "propagandas.$attribute";

            if ($partialMatch) {
                        $query->andWhere( [ 'like', $attribute, $value ] );
            } else {
                        $query->andWhere( [ $attribute => $value ] );
            }
    }
}
