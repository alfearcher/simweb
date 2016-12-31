<?php
	use yii\helpers\Html;
	use yii\widgets\Menu;
	use kartik\nav\NavX;
	use yii\bootstrap\NavBar;
  	use kartik\sidenav\SideNav;
  	use kartik\icons\Icon;
  	use yii\helpers\Url;
  	//use rmrevin\yii\fontawesome\FontAwesome;


  	//session_start();

  	//rmrevin\yii\fontawesome\AssetBundle::register($this);

  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);


?>

<div class="col-xs-3">
	<div class="menu-funcionario" style="margin-left:-110px;">
		<?=
		  SideNav::widget([
		    'type' => SideNav::TYPE_SUCCESS,
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
		        						['label' => 'Inscripción de Actividades Economicas','url' => ['/aaee/inscripcionactecon/inscripcion-actividad-economica/index', 'id' => 81]],
		        					   	['label' => 'Inscripción de Sucursales', 'url' => ['/aaee/inscripcionsucursal/inscripcion-sucursal/index', 'id' => 85]],
		        					   	['label' => 'Autorizar Ramo(s)', 'url' => ['/aaee/autorizarramo/autorizar-ramo/index', 'id' => 96]],
		        					   	//['label' => 'Licencias', 'url' => ['/aaee/licencia/licencia-base/index']],
		        					   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Licencia',
		        					   		'items' => [
	        					   				['label' => 'Solicitar Emision', 'url' => ['/aaee/licencia/licencia-solicitud/index', 'id' => 113]],
	        					   				['label' => 'Descargar Licencia', 'url' => ['/aaee/licencia/licencia-generar/index']],
		        					   		]
		        					   	],

	        					   		['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					   		'items' => [
	        					   				['label' => 'Solicitar Emision', 'url' => ['/aaee/solvencia/solvencia-actividad-economica-solicitud/index', 'id' => 114]],
	        					   				['label' => 'Descargar Solvencia', 'url' => ['/aaee/solvencia/solvencia-actividad-generar/index']],
		        					   		]
		        					   	],

		        					   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Modificion de Datos',
		        					   		'items' => [
		        					   				['label' => 'Anexo de Ramo', 'url' => ['/aaee/anexoramo/anexo-ramo/index', 'id' => 70]],
		        					   				['label' => 'Desincorporacion de Ramo', 'url' => ['/aaee/desincorporaramo/desincorporar-ramo/index', 'id' => 103]],
		        					   				['label' => 'Corrección de RIF', 'url' => ['/aaee/correccioncedularif/correccion-cedula-rif/index', 'id' => 87]],
		        					   				['label' => 'Corrección de Razón Social', 'url' => ['/aaee/correccionrazonsocial/correccion-razon-social/index', 'id' => 95]],
		        					   				['label' => 'Corrección Fecha Inicio de Actividad', 'url' => ['/aaee/correccionfechainicio/correccion-fecha-inicio/index', 'id' => 98]],
		        					   				['label' => 'Cambio de Domicilio Fiscal', 'url' => ['/aaee/correcciondomicilio/correccion-domicilio-fiscal/index', 'id' => 86]],
		        					   				['label' => 'Cambio de Representante Legal', 'url' => ['/aaee/correccionreplegal/correccion-representante-legal/index', 'id' => 56]],
		        					   				['label' => 'Aumento de Capital', 'url' => ['/aaee/correccioncapital/correccion-capital/index', 'id' => 66]],
		        					   				['label' => 'Cambio de Otros Datos', 'url' => '#'],
		        					   		]
		        					   	],
		        					   	['label' => Icon::show('fa fa-table',['class' => $typeLong], $typeIcon) . '&nbsp; Declaracion',
		        					   		'items' => [
		        					   				['label' => 'Estimada', 'url' =>  ['/aaee/declaracion/declaracion-estimada/index', 'id' => 108]],
		        					   				['label' => 'Definitiva', 'url' => ['/aaee/declaracion/declaracion-definitiva/index', 'id' => 110]],
		        					   				['label' => 'Sustitutiva', 'url' => ['/aaee/declaracion/sustitutiva/sustitutiva/index', 'id' => 111]],
		        					   				['label' => 'Consulta', 'url' => ['/aaee/declaracion/consulta/consulta-declaracion/index']],
		        					   		]
		        					   	],
		        					   	['label' => Icon::show('fa fa-caret-square-o-up',['class' => $typeLong], $typeIcon) . '&nbsp; Cesación de Actividades',
		        					   		'items' => [
		        					   				['label' => 'Temporal', 'url' => '#'],
		        					   				['label' => 'Definitiva', 'url' => '#'],
		        					   		]
		        					   	],
		        			]
		        		],


		        		// ['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Prueba de Mpdf',
		        		// 	'items' => [
		        		// 				['label' => 'Prueba', 'url' => ['/mpdf/prueba-mpdf/pdf']],
		        		// 				['label' => 'Consulta', 'url' => ['/mpdf/prueba-mpdf/liquidar']],
		        		// 				['label' => 'deuda', 'url' => ['/mpdf/prueba-mpdf/deuda']],
		        		// 				['label' => 'pago', 'url' => ['/mpdf/prueba-mpdf/pago']],
		        		// 				// ['label' => 'Solicitar Anulación de Recibo', 'url' => '#'],
		        		// 	]
		        		// ],



		        		//	SOLICITUDES DE INMUEBLES URBANOS
		        		['label' => Icon::show('fa fa-home',['class' => $typeLong], $typeIcon) . '&nbsp; Inmuebles Urbanos',
		        			'items' => [
		        						['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					   		'items' => [
	        					   				['label' => 'Solicitar Emision', 'url' => ['/inmueble/solvencia/solvencia-inmueble/index', 'id' => 116]],
	        					   				['label' => 'Descargar Solvencia', 'url' => ['/inmueble/solvencia/solvencia-inmueble-generar/index']],
		        					   		]
		        					   	],
		        						['label' => 'Inscripción de Inmuebles Urbanos', 'url' => ['inmueble/inscripcion/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos','id' =>68]],
		        						['label' => 'Avaluo Catastral', 'url' => '#'],
		        						['label' => 'Certificado Catastral', 'url' => '#'],
		        						['label' => 'Renovación de Certificado Catastral', 'url' => '#'],
		        						//['label' => 'Solvencia', 'url' => '#'],
		        						['label' => 'Cambio de Numero Catastral', 'url' => ['/inmueble/cambionumerocatastral/cambio-numero-catastral-inmuebles-urbanos/index', 'id' =>7]],
		        						['label' => 'Cambio de Propietario(VENDEDOR)', 'url' => ['inmueble/cambiopropietariovendedor/cambio-propietario-vendedor-inmuebles-urbanos/index','id' =>12]], // vendedor:12
		        						['label' => 'Cambio de Propietario(COMPRADOR)', 'url' => ['inmueble/cambiopropietariocomprador/cambio-propietario-comprador-inmuebles-urbanos/buscar-vendedor','id' =>11]], // comprador:11
		        						['label' => 'Integración de Parcela', 'url' => ['/inmueble/integracion/integracion-inmuebles-urbanos/integracion-inmuebles', 'id' =>107]],
		        						['label' => 'Desintegración de Parcela', 'url' => ['inmueble/desintegracion/desintegracion-inmuebles-urbanos/index', 'id' =>106]],
		        						['label' => 'Cambio a Propiedad Horizontal', 'url' => ['/inmueble/cambiopropiedadhorizontal/cambio-propiedad-horizontal-inmuebles-urbanos/index', 'id' =>3]],
		        						['label' => 'Cambio de Otros Datos del Inmueble', 'url' => ['/inmueble/cambiootrosdatos/cambio-otros-datos-inmuebles-urbanos/index','id' =>80]],
		        						['label' => 'Modificar Avaluo', 'url' => '#'],
		        						['label' => 'Desincorporación de Inmueble', 'url' => ['inmueble/desincorporacion/desincorporacion-inmuebles-urbanos/index','id' =>1], 'options' => ['onclick' => "setId(50)"]],

		        			]
		        		],

		        		//	SOLICITUDES DE VEHICULOS
		        		['label' => Icon::show('fa fa-car',['class' => $typeLong], $typeIcon) . '&nbsp; Vehiculos',
		        			'items' => [
		        						['label' => 'Inscripci&oacute;n de Vehiculos' , 'url' =>['/vehiculo/registrar/registrar-vehiculo/registrar-vehiculo', 'id' =>60]],
		        						['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . 'Calcomania',
					        				'items' => [
			        							['label' => 'Solicitud de reposición de calcomania por extravió o daño','url' => ['/vehiculo/calcomania/solicitud-extravio/seleccionar-calcomania', 'id' => 82]],

					        				],
					        			],
		        						['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					   		'items' => [
	        					   				['label' => 'Solicitar Emision', 'url' => ['/vehiculo/solvencia/solvencia-vehiculo/index', 'id' => 115]],
	        					   				['label' => 'Descargar Solvencia', 'url' => ['/vehiculo/solvencia/solvencia-vehiculo-generar/index']],
		        					   		]
		        					   	],
		        						['label' => 'Cambio de Propietario', 'url' => ['/vehiculo/cambiopropietario/opcion-cambio-propietario/seleccionar-tipo-cambio-propietario', 'id' => 9]],
		        						['label' => 'Cambio de Placa', 'url' => ['/vehiculo/cambioplaca/cambio-placa-vehiculo/vista-seleccion', 'id' => 74], 'options' => []],
		        						['label' => 'Desincorporaci&oacute;n de Vehiculo', 'url' => ['/vehiculo/desincorporacion/desincorporacion-vehiculo/vista-seleccion', 'id' => 4]],
		        						['label' => 'Cambio de Otros Datos del Vehiculo', 'url' => ['/vehiculo/cambiodatos/cambio-datos-vehiculo/vista-seleccion', 'id' => 5]],
		        			],
		        		],

		        		//	PROPAGANDA COMERCIAL
		        		['label' => Icon::show('fa fa-file-powerpoint-o',['class' => $typeLong], $typeIcon) . '&nbsp; Propaganda Comercial',
		        			'items' => [
		        						['label' => 'Inscripción de Propagandas', 'url' => ['/propaganda/crearpropaganda/crear-propaganda/crear-propaganda'	, 'id' => 75]],
		        						['label' => 'Cambio de Otros Datos de Propaganda', 'url' => ['/propaganda/modificarpropaganda/modificar-propaganda/vista-seleccion', 'id' => 84]],
		        						//['label' => 'Renovación de Propagandas', 'url' => '#'],
		        						['label' => 'Desincorporación de Propaganda', 'url' => ['propaganda/desincorporarpropaganda/desincorporar-propaganda/vista-seleccion', 'id' => 97]],

		        						['label' => 'Asignar Patrocinador', 'url' => ['propaganda/patrocinador/asignar-patrocinador-propaganda/seleccion', 'id' => 102]],

										['label' => 'Anular Patrocinador', 'url' => ['propaganda/patrocinador/anular-patrocinador-propaganda/seleccion', 'id' => 109]],

		        						['label' => 'Catalogo', 'url' => ['propaganda/catalogo/catalogo-propaganda/vista-catalogo-propaganda']],

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

		        		//	Recibo
		        		['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Recibo',
		        			'items' => [
		        						['label' => 'Crear Recibo de Pago', 'url' => ['recibo/recibo/index']],
		        						['label' => 'Consulta de Recibos', 'url' => ['recibo/recibo-consulta/index']],
		        						['label' => 'Solicitar Anulación de Recibo', 'url' => ['recibo/anular-recibo/index']],
		        			]
		        		],

		        		//	Planilla.
		        		['label' => Icon::show('fa fa-indent',['class' => $typeLong], $typeIcon) . '&nbsp; Liquidacion',
		        			'items' => [
		        						['label' => 'Liquidar Actividad Economica', 'url' => ['aaee/liquidar/liquidar-actividad-economica/index']],
		        						['label' => 'Liquidar Actividad Economica (Definitiva)', 'url' => ['aaee/liquidar/liquidar-actividad-economica-definitiva/index']],
		        						['label' => 'Liquidar Inmueble Urbano', 'url' => ['inmueble/liquidar/liquidar-inmueble/index']],
		        						['label' => 'Liquidar Vehiculo', 'url' => ['vehiculo/liquidar/liquidar-vehiculo/index']],
		        						['label' => 'Consulta de Planilla', 'url' => ['planilla/planilla-consulta/index']],
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

//	Solicitudes Elaboradas
		        		['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Elaboradas',
		        			'items' => [
		        						['label' => 'Pendientes', 'url' => ['/solicitud/solicitud-creada/index-search']],
		        						['label' => 'Historico', 'url' => '#'],

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











