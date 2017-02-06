<?php

return [
	'components' => [
		'db'        => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=192.168.1.15;dbname=sim_teq',
			'dsn' => 'mysql:host=192.168.1.7;dbname=sim_teq',
			'username' => 'adminyii',
			//'password' => 'adminyii2015',
			'password' => 'C4C1QU3-105T3QU35-2017',
			'charset'  => 'utf8',
		],
		'dbsim' => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=192.168.30.7;dbname=sim_car_plus',
			'username' => 'adminyii',
			'password' => 'adminyii2015',
			'charset'  => 'utf8',
		],

		'mailer'    => [
			'class'    => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			//'useFileTransport' => false,
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.



			'transport' => [
			   'class' => 'Swift_SmtpTransport',
			   'host' => 'smtp.gmail.com',
			   'username' => 'hacienda.guaicaipuro@gmail.com',
			   'password' => 'alcaldia2017',
			   //'username' => 'pruebasimteq@gmail.com',
			   //'password' => 'pru3b4s1m',
			   'port' => '587',
			   'encryption' => 'tls',
			],


			//'useFileTransport' => true,
		],
		'ente'   => [
			'class' => 'common\classes\Ente'
		],
		'oficina' => [
			'class'  => 'common\classes\Oficina'
		],
		'solicitud' => [
			'class' => 'common\classes\EventoSolicitud'
		],
		'lapso' => [
			'class' => 'common\classes\ControlLapso'
		],
		'mesdias' => [
			'class' => 'common\classes\MesDias'
		],
		'identidad' => [
			'class' => 'common\classes\Identidad'
		],
		'ayuda' => [
			'class' => 'common\classes\GestorAyuda'
		]
	],
];
