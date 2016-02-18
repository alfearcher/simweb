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
 *  @file Afiliaciones.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 04-01-2016
 * 
 *  @class Afiliaciones
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
 *  
 *  ValidarUsuario
 *  ValidarUsuarioContribuyente
 *  buscarDatos
 *  
 *  
 *  
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use common\models\utilidades\Utilidad;
use frontend\models\usuario\loginForm;
use frontend\models\usuario\Afiliacion;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
 
class Afiliaciones extends Afiliacion
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
   
    
    /**
     * [ValidarUsuario description] Metodo que valida el password ingresado por el usuario
     * con el password almacenado en la base de datos para poder iniciar sesion
     * @param  $model [description] modelo que viene cargado con los datos que envia el usuario al introducirlos
     * en el login y enviarlos 
     * @return [description] retorna los datos encontrados al controlador para realizar otras busquedas
     */
    public function ValidarUsuario($model)
    {
    
        $salt = Utilidad::getUtilidad();
     
        $password = $model->password.$salt;

        $clave = md5($password);

        $ValidarUsuario = Afiliacion::find()
                                    ->where([
                                    'login' => $model->email,
                                    'password_hash' => $clave,
                                    ])
                                    ->one();

            if($ValidarUsuario){
        
                return $ValidarUsuario;  
        
            } else {

                return false;
            }             


    }   
    /**
     * [ValidarUsuarioContribuyente description] metodo que busca en la tabla contribuyente para verificar si el usuario existe
     * @param  $validarPassword [description] envia el modelo para buscar el id del contribuyente en la tabla contribuyente
     */
    public function ValidarUsuarioContribuyente($validarPassword)
    {
    
        $validar = CrearUsuarioJuridico::find()
                                         ->where([
                                        'id_contribuyente' => $validarPassword->id_contribuyente,
                                        
                                         ])
                                        ->one();

            if($validar){
                
                return $validar;

            } else {

                return false;
            }             


    }

    /**
     * [buscarDatos description] metodo que busca si el id del contribuyente existe en la tabla afiliaciones
     * @param   $id_contribuyente [description] id contribuyente que llega de la tabla contribuyente para buscarlo en afiliaciones
     * @return [type] [description] retorna el modelo con los datos encontrados
     */
    public function buscarDatos($id_contribuyente)
    {
    
        $buscarDatos = Afiliacion::find()
                                   ->where([
                                   'id_contribuyente' => $id_contribuyente,
                                    ])
                                    ->one();

            if($buscarDatos){
                
                return $buscarDatos;

            } else {

                return false;
            }             


    }

    

    public function validarUsuarioActivo($validarContribuyente){
        
        $buscarDatos = Afiliacion::find()
                                   ->where([
                                   'id_contribuyente' => $validarContribuyente->id_contribuyente,
                                  // die($validarContribuyente->id_contribuyente),
                                   'estatus' => 1,
                                    ])
                                    ->all();

                                    //die(var_dump($buscarDatos));

                                    

            if($buscarDatos == true){
                   // die('encontro estatus');
                
                return $buscarDatos;

            } else {

                return false;
            }             
    }




    // PRUEBA DE AQUI PARA ABAJO


     public static function findIdentity($id_contribuyente)
    {
         
        $user = Afiliacion::find()
                ->where("estatus=:estatus", [":estatus" => 1])
                ->andWhere("id_contribuyente=:id_contribuyente", ["id_contribuyente" => $id_contribuyente])
                ->one();

                die(var_dump($user));
         
        return isset($user) ? new static($user) : null;
    }
 
    /* Busca la identidad del usuario a través de su token de acceso */
    
     
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($email)
    {
        $users = Afiliacion::find()
                ->where("estatus=:estatus", ["estatus" => 1])
                ->andWhere("login=:login", [":login" => $email])
                ->all();
         
        foreach ($users as $user) {
            if (strcasecmp($user->login, $email) === 0) {
                return new static($user);
            }
        }
 
        return null;
    }
 
    
     /* Regresa el id del usuario */
    public function getId()
    {
        return $this->id_contribuyente;
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
  
}
?>