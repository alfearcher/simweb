<?php 
	namespace backend\controllers;

	use Yii;
	//use yii\filters\AccessControl;
	use yii\web\Controller;
	//use yii\filters\VerbFilter;


	class MenuController extends controller
	{

		public $layout = 'layoutbase';


		public function actionVertical()
		{
			return $this->render('menuvertical2');
		}

	}
 ?>