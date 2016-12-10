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
 *  @file SiteController.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 19-05-2015
 * 
 *  @class SiteController
 *  @brief Clase que permite controlar el login y el control de acceso a los usuarios.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  behaviors
 *  actions
 *  actionIndex
 *  actionIndex2
 *  actionIndex3
 *  actionLogin
 *  actionLogout
 *  
 *  
 *  
 *  @inherits
 *  
 */
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
//use app\models\FormContribuyente;
//use app\models\Contribuyente;
//use app\models\FormRegister;

//revisar  cammon/models/ ... backend/models/
use common\models\Users;
use common\models\User;
use yii\web\Session;

// mandar url
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;

class SiteController extends Controller
{

  public $layout = 'layout-login'; 


// ------------CONTROL DE ACCESO----------------
// metodo para tener acceso segun el rol de usuario

    public function behaviors()
    {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'only' => ['logout', 'user', 'admin', 'funcionario'],
            'rules' => [
                [
                    //El administrador tiene permisos sobre las siguientes acciones
                    'actions' => ['logout', 'admin'],
                    //Esta propiedad establece que tiene permisos
                    'allow' => true,
                    //Usuarios autenticados, el signo ? es para invitados
                    'roles' => ['@'],
                    //Este método nos permite crear un filtro sobre la identidad del usuario
                    //y así establecer si tiene permisos o no
                    'matchCallback' => function ($rule, $action) {
                        //Llamada al método que comprueba si es un administrador
                        return User::isUserAdmin(Yii::$app->user->identity->id);
                    },
                ],
				 [
                   //Los usuarios funcionarios tienen permisos sobre las siguientes acciones
                   'actions' => ['logout', 'funcionario'],
                   //Esta propiedad establece que tiene permisos
                   'allow' => true,
                   //Usuarios autenticados, el signo ? es para invitados
                   'roles' => ['@'],
                   //Este método nos permite crear un filtro sobre la identidad del usuario
                   //y así establecer si tiene permisos o no
                   'matchCallback' => function ($rule, $action) {
                      //Llamada al método que comprueba si es un usuario simple
                      return User::isUserFuncionario(Yii::$app->user->identity->id);
                  },
               ],
                [
                   //Los usuarios simples tienen permisos sobre las siguientes acciones
                   'actions' => ['logout', 'user'],
                   //Esta propiedad establece que tiene permisos
                   'allow' => true,
                   //Usuarios autenticados, el signo ? es para invitados
                   'roles' => ['@'],
                   //Este método nos permite crear un filtro sobre la identidad del usuario
                   //y así establecer si tiene permisos o no
                   'matchCallback' => function ($rule, $action) {
                      //Llamada al método que comprueba si es un usuario simple
                      return User::isUserSimple(Yii::$app->user->identity->id);
                  },
               ],
            ],
        ],
     //Controla el modo en que se accede a las acciones, en este ejemplo a la acción logout
     //sólo se puede acceder a través del método post
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ],
    ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
// ------------VISTAS------------
//
//
// index del funcionario
    public function actionIndex()
    {
        return $this->render('index');
    }
// index del usuario	
    public function actionIndex2()
    {
        return $this->render('index2');
    }
// vista del admin
    public function actionIndex3()
    {
        return $this->render('index3');
    }


// --------LOGIN---------
// 
// acceder a las cuentas de usuarios
// BD preguntas secretas ---> tablas: preguntaseguridad, preguntasusuarios 

     public function actionLogin()
     {
         if (!\Yii::$app->user->isGuest) 
  	     {
               if (User::isUserAdmin(Yii::$app->user->identity->id_funcionario))
               {
                   return $this->redirect(["site/index3"]);
               }
  	         if (User::isUserFuncionario(Yii::$app->user->identity->id_funcionario))
               {
                   return $this->redirect(["site/index"]);
               }
               else
               {
                   return $this->redirect(["site/index2"]);
               }
         }	   
	   
	    
          $model = new LoginForm();
          if ($model->load(Yii::$app->request->post()) && $model->login()) 
          {
                if (User::isUserAdmin(Yii::$app->user->identity->id_funcionario))
                {
                    //return $this->redirect(["site/index3"]);
                      //return $this->redirect(["@backend/views/menu/vertical"]);
      
                }
  	              if (User::isUserFuncionario(Yii::$app->user->identity->id_funcionario))
                  {
                    //return $this->redirect(["site/index"]);

                  //MenuController::actionVertical();

                    return $this->redirect(["/menu/vertical"]);                  
                    
                  }
                  else
                  {
                    return $this->redirect(["site/index2"]);
                  }
          } 
		      else 
		      {
              return $this->render('login', [
                                   'model' => $model,
                                             ]);
          }
     }

       // accion para cerrar sesiones de usuarios
     public function actionLogout()
     {
         Yii::$app->user->logout();
		 return $this->redirect(["site/login"]);
         //return $this->goHome();
     }
	 
}