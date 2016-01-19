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
use frontend\models\usuario\loginForm;
use frontend\models\usuario\Afiliaciones;
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
   
    
    /* busca la identidad del usuario a través de su $id */
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
        //die('no encontro ');

        }             


    }   

    public function ValidarUsuarioContribuyente($x)
    {
        $validarC = CrearUsuarioJuridico::find()
                                    ->where([
                                    'id_contribuyente' => $x->id_contribuyente,
                                    ])
                                    ->one();

    
                if($validarC){
                // die('encontro');

                return $validarC;

                } else {

                    return false;
                //die('no encontro ');

                }             


    }
 }