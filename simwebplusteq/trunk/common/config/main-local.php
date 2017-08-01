<?php

return [
	'components' => [
		'db'        => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=localhost;dbname=sim_teq',
			//'dsn' => 'mysql:host=192.168.1.15;dbname=sim_teq',
			//'dsn' => 'mysql:host=pinky.ddns.net:3309;dbname=sim_teq',
			//'dsn' => 'mysql:host=192.168.1.15;dbname=sim_teq',
			'dsn' => 'mysql:host=192.168.30.7;dbname=sim_teq',
			//'dsn' => 'mysql:host=pinky.ddns.net:8383;dbname=sim_teq',
			//'dsn' => 'mysql:host=192.168.1.185;dbname=sim_teq',
			'username' => 'adminyii',
			'password' => 'adminyii2015',
			//'password' => 'C4C1QU3-105T3QU35-2017',
			//'username' => 'jperez',
			//'password' => 'jperez',
			//'username' => 'root',
			//'password' => '',
			'charset'  => 'utf8',
		],
		'dbsim' => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=localhost;dbname=sim_teq',
			//'dsn' => 'mysql:host=192.168.30.7;dbname=sim_car_plus',
			'dsn' => 'mysql:host=pinky.ddns.net:3309;dbname=sim_teq',
			'username' => 'adminyii',
			'password' => 'adminyii2015',
			//'username' => 'jperez',
			//'password' => 'jperez',
			//'username' => 'root',
			//'password' => '',
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
			  //'username' => 'manuelz0510@gmail.com',
			   'username' => 'hacienda.guaicaipuro@gmail.com',
			   //'username' => 'pruebasimteq@gmail.com',
			   //'password' => 'pru3b4s1m',
			   'password' => 'alcaldia2017',
			   //'password' => 'Manuel051092',
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
		],
		'catastro' => [
			'class' => 'common\classes\ImagenCatastro'
		]
	],
];
