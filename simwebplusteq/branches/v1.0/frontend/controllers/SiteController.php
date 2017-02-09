<?php
namespace frontend\controllers;

use Yii;
use frontend\models\usuario\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\usuario\Afiliaciones;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use frontend\controllers\mensaje\MensajeController;
use frontend\models\usuario\PreguntaSeguridadContribuyenteForm;
use frontend\controller\usuario\PreguntaSeguridadContribuyenteController;
use common\models\contribuyente\ContribuyenteBase;

//session_start();


/**
 * Site controller
 */
class SiteController extends Controller
{

public $layout = "layout-login";

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogin()
    {


        $mensajeError = '';
        $model = New LoginForm();

        $postData = Yii::$app->request->post();

        if ( $model->load($postData) &&  Yii::$app->request->isAjax ) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

             if ($model->load(Yii::$app->request->post()) && $model->login())  {

            if ($model->validate()){

                $validar = new Afiliaciones();

                $validarPassword = $validar->ValidarUsuario($model);

               // die(var_dump($model));

                if ($validarPassword){

                    $validarContribuyente = $validar->ValidarUsuarioContribuyente($validarPassword);






                if ($validarContribuyente){

                    $_SESSION['idContribuyente'] = $validarContribuyente->id_contribuyente;
                    $_SESSION['nombre'] = $validarContribuyente->nombres;
                    $_SESSION['apellido'] = $validarContribuyente->apellidos;


                    $validarActivo = $validar->validarUsuarioActivo($validarContribuyente);

                        if ($validarActivo == true){
                            return MensajeController::actionMensaje('Your status is inactive, please go to your city hall ');

                        }else{


                    $pregunta = new PreguntaSeguridadContribuyenteForm();

                    $preguntaSeguridad = $pregunta->ValidarPreguntaSeguridad($model, $validarPassword->id_contribuyente);



                    if ($validarPassword and $validarContribuyente == true and $preguntaSeguridad == null){

                        return $this->redirect(['/usuario/pregunta-seguridad-contribuyente/crear-pregunta-seguridad-contribuyente',

                                                                                        'id_contribuyente' => $validarPassword->id_contribuyente,

                                                                                                                        ]);
                    }else{



                        return $this->redirect(['menu-vertical']);



                    }
                    }
                    } else {

                        return MensajeController::actionMensaje('Your are not signed yet, Please go back and sign ');
                    }

                    } else {

                        $model->addError('email', 'Usuario Y/o Contraseña Incorrectas');

                    }

            }

            }
            return $this->render('login' , ['model' => $model]);

    }


    public function actionLogout()
    {
        //die('llegue a logout');
        Yii::$app->user->logout();

        return $this->redirect(['/site/index']);
    }

    public function actionLogout2()
    {
        //die('llegue a logout');
        Yii::$app->user->logout();

        return $this->redirect(['/site/index']);
    }



    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionMenuVertical()
    {
        return $this->render('/menu/menu-vertical');
    }


    /**
      * Metodo que renderiza una vista cuando el sistema este en mantenimiento.
      * @return [type] [description]
      */
     public function actionOffline()
     {
         return $this->render('offline');
     }




}
