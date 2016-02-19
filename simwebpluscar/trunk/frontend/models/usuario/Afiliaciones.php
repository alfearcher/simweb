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
 
class Afiliaciones extends Afiliacion implements \yii\web\IdentityInterface
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

    public static function findIdentityByAccessToken($token, $type = null){
        return null;
    }

     public function getId()
    {
        return null;
    }

      public function getAuthkey()
    {
        return null;
    }
 
        
    // Valida la clave de autenticación 
    public function validateAuthkey($authkey)
    {
        return null;
    }
 
     public static function findIdentity($id)
    {
        return null;
    }


   
    
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

    public function findUserPass($userName, $password_hash)
    {
       // die('llegue a find');

        $buscar = Afiliacion::find()
                            ->where([

                                'login' => $userName,
                                'password_hash' => $password_hash,
                                'estatus' => 0,
                                ])
                                ->one(); 

        // $arrayLimpio = ['id_afiliacion' => $buscar[0]->id_afiliacion, 'id_contribuyente' => $buscar[0]->id_contribuyente, 
        //                 'login' => $buscar[0]->login, 'password' => $buscar[0]->password, 'fecha_hora_afiliacion' => $buscar[0]->fecha_hora_afiliacion, 'via_sms' => $buscar[0]->via_sms, 'via_email' => $buscar[0]->via_email,
        //                     'via_tlf_fijo' => $buscar[0]->via_tlf_fijo, 'via_callcenter' => $buscar[0]->via_callcenter,
        //                     'estatus' => $buscar[0]->estatus, 'nivel' => $buscar[0]->nivel, 'confirmar_email' => $buscar[0]->confirmar_email, 'salt' => $buscar[0]->salt, 'password_hash' => $buscar[0]->password_hash  ];
       // die(var_dump($arrayLimpio));                      

                            //die(var_dump($buscar));

        
                
                return new static($buscar);

            
        
                      
    }




    // PRUEBA DE AQUI PARA ABAJO


    
 
   
}
?>