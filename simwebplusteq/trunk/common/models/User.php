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
 *  @file User.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class User
 *  @brief Clase que permite loguear al usuario comparando sus datos de acceso al sistema.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  findIdentity
 *  findIdentityByAccesToken
 *  findByUsername
 *  getId
 *  getAuthkey
 *  validateAuthkey
 *  validatePassword
 *  isUserAdmin
 *  isUserFuncionario
 *  isUserSimple
 *  
 *  @inherits
 *  
 */
namespace common\models;
 
class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
     
    public $id_user;
	public $id_funcionario;
    public $username;
    public $email;
    public $password;
    public $authkey;
    public $accesstoken;
    public $activate;
    public $role;
	public $verification_code;
	public $salt;
	public $fecha_creacion;
	public $fecha_vcto;
    public $clave;
    
    /* busca la identidad del usuario a través de su $id */
     public static function findIdentity($id_funcionario)
    {
         
        $user = Users::find()
                ->where("activate=:activate", [":activate" => 1])
                ->andWhere("id_funcionario=:id_funcionario", ["id_funcionario" => $id_funcionario])
                ->one();
         
        return isset($user) ? new static($user) : null;
    }
 
    /* Busca la identidad del usuario a través de su token de acceso */
    public static function findIdentityByAccessToken($token, $type = null)
    {
         
        $users = Users::find()
                ->where("activate=:activate", [":activate" => 1])
                ->andWhere("accesstoken=:accesstoken", [":accesstoken" => $token])
                ->all();
    
        foreach ($users as $user) {
            if ($user->accesstoken === $token) {
                return new static($user);
            }
        }
 
        return null;
    }
 
     
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($username)
    {
        $users = Users::find()
                ->where("activate=:activate", ["activate" => 1])
                ->andWhere("username=:username", [":username" => $username])
                ->all();
         
        foreach ($users as $user) {
            if (strcasecmp($user->username, $username) === 0) {
                return new static($user);
            }
        }
 
        return null;
    }
 
    
     /* Regresa el id del usuario */
    public function getId()
    {
        return $this->id_funcionario;
    }
 
    
     // Regresa la clave de autenticación 
    public function getAuthkey()
    {
        return $this->authkey;
    }
 
        
    // Valida la clave de autenticación 
    public function validateAuthkey($authkey)
    {
        return $this->authkey === $authkey;
    }
 
    /**
     * Valida el password
     */
    public function validatePassword($password)
    {
	    		
	    $clave = $password.$this->salt;
       
		//die(var_dump(md5($clave)).var_dump($this->password));  //cfcd208495d565ef66e7dff9f98764da
        /* Valida el password */
        if (crypt($password, $this->password) == $this->password)
        {
        return $password === $password;
        }
				
		return $this->password === md5($clave);
		
    }
	
	 //  -------CONTROL DE ACCESO------
	 // CONTROL DE ACCESO USERS, FUNCIONARIO, ADMIN
	 
	public static function isUserAdmin($id_funcionario)
    {
       if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 3])){
       return true;
       } else {
 
       return false;
       }
 
     }
    public static function isUserFuncionario($id_funcionario)
    {
       if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 2])){
       return true;
       } else {
 
       return false;
       }
 
     }
     public static function isUserSimple($id_funcionario)
     {
        if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 1])){
        return true;
        } else {
 
        return false; 
        }
     }

}
