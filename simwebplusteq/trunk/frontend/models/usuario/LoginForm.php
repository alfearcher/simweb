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
 *  @file LoginForm.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 11-01-2016
 *
 *  @class LoginForm
 *  @brief Clase que permite validar cada uno de los datos del formulario login.
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  validatePassword
 *  login
 *  getUser
 *
 *  @inherits
 *
 */
namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use common\models\utilidades\Utilidad;
use frontend\models\usuario\Afiliaciones;
use common\conexion\ConexionController;
use common\models\contribuyente\ContribuyenteBase;
/**
 * LoginForm es el model del login de acceso.
 */
class LoginForm extends Model
{
    public $id_afiliacion;
    public $id_contribuyente;
    public $login;
    //public $password;
    public $fecha_hora_afiliacion;
    public $via_sms;
    public $via_email;
    public $via_tlf_fijo;
    public $via_callcenter;
    public $estatus;
    public $nivel;
    public $confirmar_email;
    public $password_hash;
    public $email;
    public $password;
    public $rememberMe = true;
    public $salt;


    private $_user = false;


    /**
     * Metodo que retorna los roles de validacion.
     */
    public function rules()
    {
        return [
            // username y password son requeridos
            [['email', 'password'], 'required', 'message' => 'Campo requerido'],
            // rememberMe es un valor booleano
            ['email' , 'validatePassword'],
          //  ['rememberMe', 'boolean'],

            // password es validado por validatePassword()

        ];
    }



	/*
	 *  Metodo que retorna los nombres de los atributos
	 */
	  public function attributeLabels()
    {
        return [
        'email' => Yii::t('frontend', 'Email'),
        'password' => Yii::t('frontend', 'Password'),
	      'rememberMe' => Yii::t('frontend', 'RememberMe'),
              ];
    }

    public function validatePassword($attribute, $params)
    {
        $pass = $this->password;

        $utilidad = Utilidad::getUtilidad();
        $password = $pass.$utilidad;

        $password_hash = md5($password);

            $buscar = Afiliacion::find()
                                ->where([
                                    'login' => $this->email,
                                    'password_hash' => $password_hash,
                                    'estatus' => 0,
                                    ])
                                ->one();


          $contribuyente = new ContribuyenteBase();
           
                 
          if ( $buscar == null ) {
              $buscar = Afiliacion::find()
                                ->where([
                                    'login' => $this->email,
                                    'password' => $pass,
                                    'estatus' => 0,
                                    ])
                                ->one();

                $activo = $contribuyente->getDatosContribuyenteSegunID($buscar['id_contribuyente']);
                

                if  ($activo['inactivo'] == 1){
                    $this->addError($attribute, 'Contribuyente inactivo.');
                 }
          } else {

                $activo = $contribuyente->getDatosContribuyenteSegunID($buscar['id_contribuyente']);
                

                if  ($activo['inactivo'] == 1){
                    $this->addError($attribute, 'Contribuyente inactivo.');
                 }
          }
          
// die(var_dump($buscar));
           

           if ( $buscar['password_hash'] == null ) {
//aqui llego a la rutina para empezar el cambio a password_hash nota: entro a esta rutina por conseguir null dicha variable en la base de datos
             $cambio=self::actionCambioClave($buscar['id_contribuyente']);


                $buscar2 = Afiliacion::find()
                                ->where([
                                    'login' => $this->email,
                                    'password_hash' => $cambio,
                                    'estatus' => 0,
                                    ])
                                ->one();
               
                if  ($buscar2 == false){
                    $this->addError($attribute, 'Usuario o password incorrecto.');
                }


           } else {

                 
                 
                 if  ($buscar == false){
                    $this->addError($attribute, 'Usuario o password incorrecto.');
                 }
           }
        }


     public function login()
    {

        if ($this->validate()) {

            //die(var_dump($this->getUser()));
            return Yii::$app->user->login($this->getUser(),  10*10*10);
            //3600*24*30 : 0
        } else {
            return false;
        }
    }

    public function getUser()
    {

          $pass = $this->password;
          $utilidad = Utilidad::getUtilidad();
          $password = $pass.$utilidad;

           $password_hash = md5($password);

         if ($this->_user === false) {

            $this->_user = Afiliaciones::findByUsername($this->email, $password_hash);


        }
      // die(var_dump($this->_user));

        return $this->_user;
    }

    public function actionCambioClave($idContribuyente)
    {
//412ed1db244a8641cd696e6262bbe705 password adminteq
//9a1z1 primera prueba clave
//21azaa segunda clave de prueba
//const $utilidad = '14adf8';
//
//prueba en login---- affj9 MARTINEZRAMON1943@GMAIL.COM
 try {
              // $afiliado = Afiliacion::find()->where("login=:login", [":login" => $this->email])
              //                               //->andwhere('estatus' => 0)
              //                               ->asArray()->one();


              $afiliado = Afiliacion::find()->where('id_contribuyente =:id_contribuyente',
                                                      [':id_contribuyente' => $idContribuyente])
                                            ->asArray()->one();


              $salt = Utilidad::getUtilidad();

              $password1 = $afiliado['password'].$salt;

              $password_hash = md5($password1);

              $arregloDatos = ['password_hash' => $password_hash];

              $conexion = new ConexionController();

              $conn = $conexion->initConectar('db');

              $conn->open();

              $transaccion = $conn->beginTransaction();

              $tableName= 'afiliaciones';
              $arregloDatos = ['password_hash' => $password_hash,
                                'password' => 0,
                                'estatus'=> 0];
               $arregloCondition = ['id_contribuyente' => $afiliado['id_contribuyente']];


                  if ($conexion->modificarRegistroNatural($conn, $tableName, $arregloDatos, $arregloCondition)){

                      $transaccion->commit();
                      $conn->close();
                      return $password_hash;


                  }else{

                      $transaccion->rollback();
                      $conn->close();
                      return false;

                  }



             } catch ( Exception $e ) {
                //echo $e->errorInfo[2];
             }


        }



}
