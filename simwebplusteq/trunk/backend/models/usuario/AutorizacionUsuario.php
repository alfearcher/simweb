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
 *  @file AutorizacionUsuario.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 28-08-2017
 * 
 *  @class AutorizacionUsuario
 *  @brief Clase que contiene las rules y los metodos para la validacion de las rutas de perfiles de usuarios
 * 
 *  
 * 
 *  
 *  
 *  @property
 *  getFuncionarioAutorizado
 *  estaAutorizado
 *  
 *  @method
 *  
 *  
 *  @inherits
 *  
 */
namespace backend\models\usuario;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\models\User;
use backend\models\usuario\PerfilUsuario;

use yii\data\ActiveDataProvider;

class AutorizacionUsuario extends Users
{

/**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return [type] [description]
	     */
	    private function getFuncionarioAutorizado($u, $r)
	    {
	    	

	    	$perfil = PerfilUsuario::find()
                ->where("inactivo=:activate", [":activate" => 0])
                ->andWhere("username=:username", ["username" => $u])
                ->andWhere("ruta=:ruta", ["ruta" => $r])
                ->one();

            if ($perfil) { 
            	return true;
            } else { 
            	return false;
            }
            
            
	    }



	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario, $ruta)
	    {
	    	$listaUsuarioAutorizado = self::getFuncionarioAutorizado($usuario, $ruta);
	    	
	    	if($listaUsuarioAutorizado == true){
	    	//if ( count($listaUsuarioAutorizado) > 0 ) {
	    	
	    		//foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    		//	if ( $value == $usuario ) {
	    				return true;
	    		//	}
	    		//}
	    	}
	    	return false;
	    }

}
