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
}
?>