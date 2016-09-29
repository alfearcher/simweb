<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            //'dateFormat' => 'dd.MM.yyyy',
            // Configuración para el formato de montos.
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'EUR',
       ],
        'i18n' => [
        	'translations' => [
        		'frontend' => [
        			'class' => 'yii\i18n\PhpMessageSource',
        			'basePath' => '@common/messages'
        		],
        		'backend' => [
        			'class' => 'yii\i18n\PhpMessageSource',
        			'basePath' => '@common/messages'
        		],
        	],
        ],
    ],
    'language' => 'es',     // Lenguaje en que apareceran los mensajes y etiquetas del sistema
];
