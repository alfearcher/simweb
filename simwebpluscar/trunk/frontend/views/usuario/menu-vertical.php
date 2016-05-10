<?php
	use yii\helpers\Html;
	use yii\widgets\Menu;
	use kartik\nav\NavX;
	use yii\bootstrap\NavBar;
  	use kartik\sidenav\SideNav;
  	use kartik\icons\Icon;
  	use yii\helpers\Url;

    
  	session_start();

  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 
?>

<div class="col-xs-3">
	<div class="menu-funcionario" style="margin-left:-110px;">
		<?=
		  SideNav::widget([
		    'type' => SideNav::TYPE_DEFAULT,
		    'encodeLabels' => false,
		    'headingOptions' => ['method' => 'post'],
		    //'heading' => '<span class="fa fa-list-alt fa-2x"></span>&nbsp;<i> Menu Principal</i>',
		    'heading' => Icon::show('fa fa-list-alt',['class' => 'fa-2x'], $typeIcon) . '&nbsp;<h4>' . Yii::t('frontend', 'Main Menu') . '</h4>',
		    'items' => [
		        // Important: you need to specify url as 'controller/action',
		        // not just as 'controller' even if default action is used.

		    	// ['label' => Icon::show('fa fa-street-view',['class' => $typeLong], $typeIcon) . '&nbsp; Solicitudes',
		     //    	'items' => [
		     //    			['label' => 'Crear Funcionario', 'url' => ['/contribuyente/crear/crear'], 'options' => ['value' => '300', 'id' => '300']],
		     //    			['label' => 'Modificar Funcionario', 'url' => '#', 'options' => ['value' => '301', 'id' => '301']],
		     //    			['label' => 'Desincorporar Funcionario', 'url' => '#', 'options' => ['value' => '302', 'id' => '302']],
		     //    			['label' => 'Asignar a Grupo de Trabajo', 'url' => '#', 'options' => ['value' => '303', 'id' => '303']],
		     //    			['label' => Icon::show('user',['class' => 'fa-2x'], $typeIcon) . 'Usuarios', 'url' => '#',
		     //    				'items' => [
		     //    							['label' => 'Crear Cuenta','url' => '#'],
		     //    							['label' => 'Desincorporar Cuenta','url' => '#'],
		     //    				]
		     //    			],

		     //    			['label' => Icon::show('fa fa-users',['class' => 'fa-2x'], $typeIcon) . 'Grupos de Trabajo (Perfiles)', 'url' => '#',
		     //    				'items' => [
		     //    							['label' => 'Crear Grupo de Trabajo (Perfiles)','url' => ['grupotrabajo/grupos-trabajo/create']],
		     //    							['label' => 'Modificar Grupo de Trabajo','url' => ['grupotrabajo/grupos-trabajo/index']],
		     //    							['label' => 'Desincorporar Grupo de Trabajo','url' => ['grupotrabajo/grupos-trabajo/desincorporacion']],
		     //    				]
		     //    			],
		     //    	]
		     //    ],

		    	// // &nbsp; espacio en blanco html
		     //    ['label' => Icon::show('fa fa-book',['class' => $typeLong], $typeIcon) . '&nbsp; Registros Maestros',
		     //    	'items' => [
		     //    			['label' => 'Registros Datos Básicos', 'url' => ['/registromaestro/datosbasico/create'], 'options' => ['value' => '520', 'id' => '500']],
		     //    			//Icon::show('fa fa-book',['class' => 'fa-2x'], $typeIcon) . '&nbsp;
		     //    	]
		     //    ],

		        ['label' => Icon::show('fa fa-laptop',['class' => $typeLong], $typeIcon) . '&nbsp; Solicitudes',
		        	'items' => [
		        		//	SOLICITUDES DE ACTIVIDADES ECONOMICAS
		        		['label' => Icon::show('fa fa-briefcase',['class' => $typeLong], $typeIcon) . '&nbsp; Actividades Economicas',
		        			'items' => [
		        						['label' => 'Inscripción de Actividades Economicas','url' => ['/aaee/inscripcionactecon/inscripcion-actividad-economica/index', 'id' => 1]],
		        					   	['label' => 'Inscripción de Sucursales', 'url' => ['/aaee/inscripcionsucursal/inscripcion-sucursal/index']],
		        					   	['label' => 'Autorizar Ramos', 'url' => ['/aaee/autorizarramo/autorizar-ramo/index']],
		        					   	['label' => 'Renovación de Licencias', 'url' => ['ordenanza/impuesto/ordenanza/index']],
		        					   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Modificion de Datos',
		        					   		'items' => [
		        					   				['label' => 'Anexo de Ramo', 'url' => '#'],
		        					   				['label' => 'Desincorporacion de Ramo', 'url' => '#'],
		        					   				['label' => 'Corrección de RIF', 'url' => ['/aaee/correccioncedularif/correccion-cedula-rif/index']],
		        					   				['label' => 'Corrección de Razón Social', 'url' => ['/aaee/correccionrazonsocial/correccion-razon-social/index']],
		        					   				['label' => 'Cambio de Domicilio Fiscal', 'url' => ['/aaee/correcciondomicilio/correccion-domicilio-fiscal/index']],
		        					   				['label' => 'Cambio de Representante Legal', 'url' => '#'],
		        					   				['label' => 'Aumento de Capital', 'url' => ['/aaee/correccioncapital/correccion-capital/index']],
		        					   				['label' => 'Cambio de Otros Datos', 'url' => '#'],
		        					   		]
		        					   	],
		        					   	['label' => Icon::show('fa fa-table',['class' => $typeLong], $typeIcon) . '&nbsp; Declaracion',
		        					   		'items' => [
		        					   				['label' => 'Estimada', 'url' => '#'],
		        					   				['label' => 'Definitiva', 'url' => '#'],
		        					   				['label' => 'Sustitutiva', 'url' => '#'],
		        					   		]
		        					   	],
		        					   	['label' => Icon::show('fa fa-hand-paper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Cesación de Actividades',
		        					   		'items' => [
		        					   				['label' => 'Temporal', 'url' => '#'],
		        					   				['label' => 'Definitiva', 'url' => '#'],
		        					   		]
		        					   	],
		        			]
		        		],

		        		//	SOLICITUDES DE INMUEBLES URBANOS
		        		['label' => Icon::show('fa fa-home',['class' => $typeLong], $typeIcon) . '&nbsp; Inmuebles Urbanos',
		        			'items' => [
		        						['label' => 'Inscripción de Inmuebles Urbanos', 'url' => ['inmueble/inscripcion/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos','id' =>60]],
		        						['label' => 'Avaluo Catastral', 'url' => '#'],
		        						['label' => 'Certificado Catastral', 'url' => '#'],
		        						['label' => 'Renovación de Certificado Catastral', 'url' => '#'],
		        						['label' => 'Solvencia', 'url' => '#'],
		        						['label' => 'Cambio de Numero Catastral', 'url' => ['/inmueble/inmuebles-urbanos/index']],
		        						['label' => 'Cambio de Propietario', 'url' => ['inmueble/cambio-propietario-inmuebles-urbanos/cambio-propietario-inmuebles']],
		        						['label' => 'Integración de Parcela', 'url' => ['/inmueble/integracion-inmuebles-urbanos/integracion-inmuebles']],
		        						['label' => 'Desintegración de Parcela', 'url' => ['inmueble/desintegracion-inmuebles-urbanos/desintegracion']],
		        						['label' => 'Cambio a Propiedad Horizontal', 'url' => ['/inmueble/inmuebles-urbanos/index']],
		        						['label' => 'Cambio de Otros Datos del Inmueble', 'url' => ['/inmueble/cambiootrosdatos/cambio-otros-datos-inmuebles-urbanos/index','id' =>3]],
		        						['label' => 'Modificar Avaluo', 'url' => '#'],
		        						['label' => 'Desincorporación de Inmueble', 'url' => ['inmueble/desincorporacion/desincorporacion-inmuebles-urbanos/index','id' =>3], 'options' => ['onclick' => "setId(50)"]],

		        			]
		        		],

		        		//	SOLICITUDES DE VEHICULOS
		        		['label' => Icon::show('fa fa-car',['class' => $typeLong], $typeIcon) . '&nbsp; Vehiculos',
		        			'items' => [
		        						['label' => 'Inscripci&oacute;n de Vehiculos' , 'url' =>['/vehiculo/registrar/registrar-vehiculo/registrar-vehiculo', 'id' =>60]],
		        						['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . 'Calcomania',
					        				'items' => [
			        							['label' => 'Administrar funcionarios responsables de entregar calcomania','url' => ['vehiculo/calcomania/funcionario-calcomania/busqueda-funcionario']],
			        							['label' => 'Generar Lote de Calcomania','url' => ['vehiculo/calcomania/lote-calcomania/create']],
			        							['label' => 'Administrar Lote de Calcomanias Generadas','url' => ['vehiculo/calcomania/lote-calcomania/busqueda-lote']],
			        							['label' => 'Asignar Calcomanias a funcionario (Distribuir entre funcionario responsables)','url' => ['vehiculo/calcomania/funcionario-calcomania/distribuir-lote']],
			        							['label' => 'Administrar Entrega de calcomania','url' => '#'],
			        							['label' => 'Cierre de Lote','url' => '#'],
					        				],
					        			],
		        						['label' => 'Solvencia', 'url' => '#'],
		        						['label' => 'Cambio de Propietario', 'url' => ['/vehiculo/cambiopropietario/opcion-cambio-propietario/seleccionar-tipo-cambio-propietario', 'id' => 9]],
		        						['label' => 'Cambio de Placa', 'url' => ['/vehiculo/cambioplaca/cambio-placa-vehiculo/vista-seleccion', 'id' => 74], 'options' => []],
		        						['label' => 'Desincorporaci&oacute;n de Vehiculo', 'url' => ['/vehiculo/desincorporacion/desincorporacion-vehiculo/vista-seleccion', 'id' => 4]],
		        						['label' => 'Cambio de Otros Datos del Vehiculo', 'url' => ['/vehiculo/cambiodatos/cambio-datos-vehiculo/vista-seleccion', 'id' => 5]],
		        			],
		        		],

		        		//	PROPAGANDA COMERCIAL
		        		['label' => Icon::show('fa fa-file-powerpoint-o',['class' => $typeLong], $typeIcon) . '&nbsp; Propaganda Comercial',
		        			'items' => [
		        						['label' => 'Inscripción de Propagandas', 'url' => ['propaganda/propaganda/create']],
		        						//['label' => 'Renovación de Propagandas', 'url' => '#'],
		        						['label' => 'Desincorporación de Propaganda', 'url' => ['propaganda/propaganda/desincorporacion']],
		        						['label' => 'Cambio de Otros Datos de Propaganda', 'url' => ['propaganda/propaganda/index']],
		        						//['label' => 'Solvencia', 'url' => '#'],
		        			]
		        		],

		        		//	ESPECTACULO PUBLICO
		        		['label' => Icon::show('fa fa-cubes',['class' => $typeLong], $typeIcon) . '&nbsp; Espectaculo Publico',
		        			'items' => [
		        						['label' => 'Inscripción de Espectaculo', 'url' => '#'],
		        						['label' => 'Suspención de Espectaculo', 'url' => '#'],
		        						['label' => 'Sellado y Troquelado de Entradas', 'url' => '#'],
		        						['label' => 'Cambio de Otros Datos de Espectaculos', 'url' => '#'],
		        			]
		        		],

		        		//	APUESTAS LICITAS
		        		['label' => Icon::show('fa fa-diamond',['class' => $typeLong], $typeIcon) . '&nbsp; Apuestas Licitas',
		        			'items' => [
		        						['label' => 'Inscripción de Apuestas', 'url' => ['apuestalicita/apuestas-licita/create']],
		        						['label' => 'Cambio de Otros Datos de Apuestas Licitas', 'url' => ['apuestalicita/apuestas-licita/index']],
		        						['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        			]
		        		],

		        		//	Otras
		        		['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Otras',
		        			'items' => [
		        						['label' => 'Pago Parcial', 'url' => '#'],
		        						['label' => 'Convenio de Pago', 'url' => '#'],
		        						['label' => 'Cheque Devuelto', 'url' => '#'],
		        						['label' => 'Credito Fiscal', 'url' => '#'],
		        						['label' => 'Multas', 'url' => '#'],
		        						['label' => 'Fiscalización', 'url' => '#'],
		        						['label' => 'Auditorias', 'url' => '#'],
		        						['label' => 'Registro de Agente de Retención', 'url' => '#'],
		        						['label' => 'Recibo de Pago', 'url' => '#'],
		        						['label' => 'Cierre', 'url' => '#'],
		        						//['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        						//['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        			]
		        		],
		        	]
		        ],


		        // //	PANEL MAESTRO
        		// ['label' => Icon::show('fa fa-calculator',['class' => $typeLong], $typeIcon) . '&nbsp; Panel Maestro',
        		// 	'items' => [
        		// 				['label' => 'Pagar Planilla con Planilla(s)', 'url' => '#'],
        		// 				['label' => 'Eliminación de Planillas', 'url' => '#'],
        		// 				['label' => 'Listado de Solicitudes de Anulación de Planillas', 'url' => '#'],
        		// 				['label' => 'Aprobación de Solicitudes de Anulación de Planillas', 'url' => '#'],
        		// 				['label' => 'Solicitud para Anular Planillas', 'url' => '#'],
        		// 				['label' => 'Supervisores autorizados', 'url' => '#'],
        		// 	]
        		// ],


        		// //	AJUSTAR PAGOS
        		// ['label' => Icon::show('fa fa-pencil-square-o',['class' => $typeLong], $typeIcon) . '&nbsp; Ajustar Pagos',
        		// 	'items' => [
        		// 				['label' => 'Ajustar Formas de Pagos', 'url' => '#'],
        		// 				['label' => 'Ajustar Pagos Inconsistentes', 'url' => '#'],
        		// 				['label' => 'Listado Planillas-Bancos vs Planillas-Pagadas', 'url' => '#'],
        		// 				['label' => 'Ajustar Cuentas Bancarias Sobre Pagos', 'url' => '#'],
        		// 				['label' => 'Ajustar Planillas por Ingresos', 'url' => '#'],
        		// 				['label' => 'Ajustar Pagos Por Fecha', 'url' => '#'],
        		// 	]
        		// ],


        		// //	PAGOS
        		// ['label' => Icon::show('fa fa-money',['class' => $typeLong], $typeIcon) . '&nbsp; Pagos',
        		// 	'items' => [
        		// 				['label' => 'Recibo', 'url' => '#'],
        		// 				['label' => 'Caja', 'url' => '#'],
        		// 				['label' => 'Elaborar Deposito', 'url' => '#'],
        		// 				['label' => 'Pagos Anteriores', 'url' => '#'],
        		// 				['label' => 'Exoneraciones y Exenciones', 'url' => '#'],
        		// 				['label' => 'Cuentas Por Cobrar', 'url' => '#'],
        		// 				['label' => 'Procesar Pagos en Lote', 'url' => '#'],
        		// 				['label' => 'Registros TXT', 'url' => '#'],
        		// 	]
        		// ],


		        // //	CONFIGURACIONES
        		// ['label' => Icon::show('fa fa-cog',['class' => $typeLong], $typeIcon) . '&nbsp; Configuraciones',
        		// 	'items' => [
        		// 				['label' => 'Configurar Ordenanzas', 'url' => '#'],
        		// 				['label' => 'Configurar Vencimiento de Documentos', 'url' => '#'],
        		// 				['label' => 'Configurar Solicitudes', 'url' => '#'],
        		// 				['label' => 'Configurar Convenios de Pagos', 'url' => '#'],
        		// 				['label' => 'Configurar Dirección de Tributos',
        		// 				            'url' => ['/hola/saludo2'],
        		// 				            'id' => 10,
        		// 				            'click' => 'alert("Button 2 clicked");'],
        		// 	]
        		// ],




        		/*
		        // 'Products' menu item will be selected as long as the route is 'product/index'
		        ['label' => 'Books', 'icon' => 'book', 'items' => [
		            ['label' => 'New Arrivals', 'url' => "#"],
		            ['label' => 'Most Popular', 'url' => "#"],
		            ['label' => 'Read Online', 'icon' => 'cloud', 'items' => [
		                ['label' => 'Online 1', 'url' => "#"],
		                ['label' => 'Online 2', 'url' => "#"]
		            ]],
		        ]],
		        ['label' => 'Categories', 'icon' => 'tags', 'items' => [
		            ['label' => 'Fiction', 'url' => "#"],
		            ['label' => 'Historical', 'url' => "#"],
		            ['label' => 'Announcements', 'icon' => 'bullhorn', 'items' => [
		                ['label' => 'Event 1', 'url' => "#"],
		                ['label' => 'Event 2', 'url' => "#"]
		            ]],
		        ]],
		        ['label' => 'Profile', 'icon' => 'user', 'url' => "#"],
		        */
		    ],
		]);
		?>


	</div>
</div>






	


	

