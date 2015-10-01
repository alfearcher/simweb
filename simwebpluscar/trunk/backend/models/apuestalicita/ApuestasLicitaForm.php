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
 *	@file ApuestasLicitaForm.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 17-09-2015
 * 
 *      @class ApuestasLicitaForm
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
use backend\models\ContribuyentesForm;

/**
 * This is the model class for table "apuestas".
 *
 * @property string $id_impuesto
 * @property string $id_contribuyente
 * @property string $descripcion
 * @property string $direccion
 * @property string $id_cp
 * @property string $id_sim
 * @property integer $status_apuesta
 * @property ContribuyenteName $ContribuyenteivoName
 * @property InactivoName $InactivoName
 */
class ApuestasLicitaForm extends \yii\db\ActiveRecord
{
    public $est_mun_parr_cp;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apuestas';
    }

    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
        return [
                    [ [ 'id_impuesto', 'id_contribuyente', 'id_cp', 'id_sim' ], 'integer' ],
                    [ [ 'id_contribuyente', 'descripcion', 'direccion', 'id_cp' ], 'required' ],
                    [ [ 'status_apuesta', 'id_impuesto', 'fecha_creacion', 'contribuyenteName' ], 'safe' ]
                ];
    }

    /**
    *   Metodo attributeLabels(), retorna las etiquetas de los campos.
    */
    public function attributeLabels()
    {
        return [
                    'id_impuesto' => Yii::t( 'backend', 'Id Tax' ),
                    'id_contribuyente' => Yii::t( 'backend', 'id Taxpayer' ),
                    'descripcion' => Yii::t( 'backend', 'Description of Lawful Bets' ),
                    'direccion' => Yii::t( 'backend', 'Address' ),
                    'id_cp' => Yii::t( 'backend', 'Id Cp' ),
                    'id_sim' => Yii::t( 'backend', 'Id Sim' ),
                    'status_apuesta' => Yii::t( 'backend', 'Status Bet' ),
                    'fecha_creacion' => Yii::t( 'backend', 'Date Creation' ),
                    'contribuyenteName' => Yii::t( 'backend', 'Taxpayer Name' ),
                    'inactivoName' => Yii::t( 'backend', 'Status Bet' ),    
                ];
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas apuestas y contribuyentes, las cuales se relacionan  por su id_contribuyente referencial.
    */
    public function getContribuyente()
    {
        return $this->hasOne( ContribuyentesForm::className(), [ 'id_contribuyente' => 'id_contribuyente' ] );
    }
 
    /**
    *   Almacena el campo descripcion de la tabla departamentos, en una variable GET para retornarla a la vista
    */
    public function getContribuyenteName() 
    {
        return $this->contribuyente->razon_social;
    }
    
    public function getInactivoName() 
    {
        if( $this->status_apuesta == 0 ) {
            
                    $this->status_apuesta = 'ACTIVO';
        } else {
                    $this->status_apuesta = 'INACTIVO';
        }
                    return $this->status_apuesta;
    }
}
