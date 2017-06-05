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
 *  @file TipoVehiculoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 29/02/2016
 * 
 *  @class TipoVehiculoForm
 *  @brief Clase Modelo para cargar el combo de tipo vehiculo en el formulario de creacion de vehiculo
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *  
 *  
 *  @inherits
 *  
 */


namespace common\models\vehiculo\tipovehiculo;


use Yii;
use yii\base\Model;
use backend\models\funcionario\Funcionario;
use common\conexion\ConexionController;


 

	/**
	 *	Clase principal del formulario _form vista de funcionario.
	 */
	class ClaseVehiculoForm extends TipoVehiculo
	{

	 


    	/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



    	/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario _form
    	 */
	    public function rules()
	    {
	        return [
	                
	                [['descripcion'], 'string', 'max' => 45]
	           

	        ];
	    }


	   

	    /**
	     * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	     * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	     */
	    public function attributeLabels()
	    {
	        return [
	        
	        'tipo_vehiculo' => Yii::t('Frontend', 'Tipo Vehiculo'),
            'descripcion' => Yii::t('Frontend', 'Descripcion'),
	           
	        ];
	    }
	}
?>