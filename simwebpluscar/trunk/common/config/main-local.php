<?php
return [
	'components' => [
		'db'        => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=localhost;dbname=sim_teq',
			'dsn' => 'mysql:host=192.168.30.7;dbname=sim_car_plus',
			//'dsn' => 'mysql:host=pinky.ddns.net;dbname=sim_car',
			'username' => 'adminyii',
			'password' => 'adminyii2015',
			//'username' => 'jperez',
			//'password' => 'jperez',
			//'username' => 'root',
			//'password' => '',
			'charset'  => 'utf8',
		],
		'dbsim' => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=localhost;dbname=sim_teq',
			'dsn' => 'mysql:host=192.168.30.7;dbname=sim_car_plus',
			//'dsn' => 'mysql:host=pinky.ddns.net;dbname=sim_car',
			'username' => 'adminyii',
			'password' => 'adminyii2015',
			//'username' => 'jperez',
			//'password' => 'jperez',
			//'username' => 'root',
			//'password' => '',
			'charset'  => 'utf8',	
		],
		'dbswp'  => [
			'class'    => 'yii\db\Connection',
			//'dsn' => 'mysql:host=localhost;dbname=sim_teq',
			'dsn' => 'mysql:host=192.168.30.7;dbname=sim_car_plus',
			//'dsn' => 'mysql:host=pinky.ddns.net;dbname=sim_car',
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

			'transport' => [
			   'class' => 'Swift_SmtpTransport',
			   'host' => 'smtp.gmail.com',
			   'username' => 'manuel1122@hotmail.com',
			   'password' => 'manuza21',
			   'port' => '587',
			   'encryption' => 'tls',
			   
			],

			'viewPath' => '@common/mail',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.

			

			'useFileTransport' => true,
		],
		'ente'   => [
			'class' => 'common\classes\Ente'
		],
		'oficina' => [
			'class'  => 'common\classes\Oficina'
		],
	],
];
