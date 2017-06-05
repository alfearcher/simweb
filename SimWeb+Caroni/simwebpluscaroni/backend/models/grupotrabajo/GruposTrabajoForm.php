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
 *	@file GruposTrabajoForm.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 12-08-2015
 * 
 *      @class GruposTrabajoForm
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
use backend\models\Departamento;
use backend\models\UnidadDepartamento;

/**
 * This is the model class for table "grupos_trabajo".
 *
 * @property integer $id_grupo
 * @property string $descripcion
 * @property integer $id_departamento
 * @property integer $id_unidad
 * @property string $fecha
 * @property integer $inactivo
 * @property  DepartamentoName $departamentoName
 * @property  UnidadName $unidadName
 * @property  InactivoName $inactivoName
 */
class GruposTrabajoForm extends \yii\db\ActiveRecord
{
   
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'grupos_trabajo';
    }
  
    /**
    *   Metodo rules(), retorna las reglas de validaciones a la vista index.
    */
    public function rules()
    {
       
        return [
                    [ [ 'descripcion', 'id_departamento', 'id_unidad', 'fecha', 'inactivo' ], 'required' ],
                    [ [ 'id_departamento', 'inactivo', 'id_unidad' ], 'integer' ],
                    [ ['descripcion' ], 'string', 'max' => 200 ],
                ];
    }
    
    /**
    *   Metodo attributeLabels(), retorna las etiquetas de los campos.
    */
    public function attributeLabels()
    {
        return 
        [
            'id_grupo' => Yii::t( 'backend', 'Id Group' ),
            'descripcion' => Yii::t( 'backend', 'Description Group' ),
            'id_departamento' => Yii::t( 'backend', 'Business Department' ),
            'id_unidad' => Yii::t( 'backend', 'Business Unit' ),
            'fecha' => Yii::t( 'backend', 'Date Creation' ),
            'inactivo' => Yii::t( 'backend', 'Status Group' ),
            'inactivoName' => Yii::t( 'backend', 'Status Group' ),
            'departamentoName' => Yii::t( 'backend', 'Description Department' ),
            'unidadName' => Yii::t( 'backend', 'Description Unit' ),
        ];
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas departamentos y grupos_trabajo, las cuales se relacionan  por su id referencial.
    */
    public function getDepartamento()
    {
        return $this->hasOne( Departamento::className(), [ 'id_departamento' => 'id_departamento' ] );
    }
 
    /**
    *   Almacena el campo descripcion de la tabla departamentos, en una variable GET para retornarla a la vista
    */
    public function getDepartamentoName() 
    {
        return $this->departamento->descripcion;
    }
    
    /**
    *   Contiene la relacion de 1 a M, de las tablas unidades_departamentos y grupos_trabajo, las cuales se relacionan  por su id referencial
    */
    public function getUnidad()
    {
        return $this->hasOne( UnidadDepartamento::className(), [ 'id_unidad' => 'id_unidad' ] );
    }
    
    /**
    *   Almacena el campo descripcion de la tabla unidades_departamentos, en una variable GET para retornarla a la vista
    */
    public function getUnidadName() 
    {
        return $this->unidad->descripcion;
    }
    
    /**
    *   Almacena el campo inactivo de la tabla grupos_trabajo, en una variable GET para retornarla a la vista con el valor seteado dependiendo del condicional
    */
    public function getInactivoName() 
    {
        if( $this->inactivo == 0 ) {
            
                    $this->inactivo = 'ACTIVO';
        } else {
                    $this->inactivo = 'INACTIVO';
        }
                    return $this->inactivo;
    }
    
    /**
     *  Metodo consultarGruposTrabajo(), permite verificar si la descripcion del grupo de trabajo es existente o no.
     *  @param type $conn instancia de conexion a base de datos.
     *  @param type $descripcion, varchar.
     *  @return boolean.
     */
    public function consultarGruposTrabajo( $conexion, $conn, $descripcion )
    { 
        if ( $descripcion != '' )  {
            
            $sql = " SELECT id_grupo AS cantidad FROM grupos_trabajo A WHERE A.descripcion = '{$descripcion}' AND A.inactivo = '0' ORDER BY descripcion";
            $command = $conn->createCommand($sql);
            
            if ( !$command ) {
                                   
                return false;
            }
            return $command->queryAll();
        } 
    }
}

 