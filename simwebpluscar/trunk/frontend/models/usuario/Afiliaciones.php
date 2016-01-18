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
namespace frontend\models\usuario;

use common\models\utilidades\Utilidad;
 
class Afiliaciones extends \yii\base\Object implements \yii\web\IdentityInterface
{
     
    public $id_afiliacion;
	public $id_contribuyente;
    public $login;
    public $password;
    public $fecha_hora_afiliacion;
    public $via_sms;
    public $via_email;
    public $via_tlf_fijo;
    public $via_callcenter;
	public $estatus;
	public $nivel;
	public $confirmar_email;
    public $password_hash;
   
    
    // /* busca la identidad del usuario a través de su $id */
    //  public static function findIdentity($id_afiliacion)
    // {
         
    //     $afiliacion = Afiliacion::find()
    //             ->where("estatus=:estatus", [":estatus" => 1])
    //             ->andWhere("id_afiliacion=:id_afiliacion", [":id_afiliacion" => $id_afiliacion])
    //             ->one();
         
    //     return isset($afiliaciones) ? new static($afiliaciones) : null;
    // }
 
    //  Busca la identidad del usuario a través de su token de acceso 
    // public static function findIdentityByAccessToken($token, $type = null)
    // {
         
    //     $afiliaciones = Afiliacion::find()
    //             ->where("activate=:activate", [":activate" => 1])
    //             ->andWhere("accesstoken=:accesstoken", [":accesstoken" => $token])
    //             ->all();
    
    //     foreach ($afiliaciones as $afiliacion) {
    //         if ($afiliacion->accesstoken === $token) {
    //             return new static($afiliacion);
    //         }
    //     }
 
    //     return null;
    // }
 
     
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($email)
    {
        $afiliaciones = Afiliacion::find()
                ->where("estatus=:estatus", ["estatus" => 1])
                ->andWhere("login=:login", [":login" => $email])
                ->all();
         
        foreach ($afiliaciones as $afiliacion) {
            if (strcasecmp($afiliacion->login, $login) === 0) {
                return new static($afiliacion);
            }
        }
 
        return null;
    }
 
    
     /* Regresa el id del usuario */
    // public function getId()
    // {
    //     return $this->id_afiliacion;
    // }
 
    
    //  // Regresa la clave de autenticación 
    // public function getAuthkey()
    // {
    //     return $this->authkey;
    // }
 
        
    // // Valida la clave de autenticación 
    // public function validateAuthkey($authkey)
    // {
    //     return $this->authkey === $authkey;
    // }
 
    /**
     * Valida el password
     */
    public function validatePassword($password)
    {

         $salt = Utilidad::getUtilidad();
                
        $clave = $password + $this->salt;

        /* Valida el password */
        if (crypt($password, $this->password) == $this->password)
        {
        return $password === $password;
        }
                
        return $this->password === md5($clave);
        
    }
	//  //  -------CONTROL DE ACCESO------
	//  // CONTROL DE ACCESO USERS, FUNCIONARIO, ADMIN
	 
	// public static function isUserAdmin($id_funcionario)
 //    {
 //       if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 3])){
 //       return true;
 //       } else {
 
 //       return false;
 //       }
 
 //     }
 //    public static function isUserFuncionario($id_funcionario)
 //    {
 //       if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 2])){
 //       return true;
 //       } else {
 
 //       return false;
 //       }
 
 //     }
 //     public static function isUserSimple($id_funcionario)
 //     {
 //        if (Users::findOne(['id_funcionario' => $id_funcionario, 'activate' => '1', 'role' => 1])){
 //        return true;
 //        } else {
 
 //        return false; 
 //        }
 //     }

}
