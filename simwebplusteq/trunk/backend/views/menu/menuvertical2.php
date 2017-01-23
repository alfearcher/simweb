<?php
	use yii\helpers\Html;
	use yii\widgets\Menu;
	use kartik\nav\NavX;
	use yii\bootstrap\NavBar;
  	use kartik\sidenav\SideNav;
  	use kartik\icons\Icon;
  	use yii\helpers\Url;


  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);
?>

<div class="col-xs-3">
	<div class="menu-funcionario" >
		<?=
		  SideNav::widget([
		    'type' => SideNav::TYPE_DEFAULT,
		    'encodeLabels' => false,
		    'headingOptions' => ['method' => 'post'],
		    //'heading' => '<span class="fa fa-list-alt fa-2x"></span>&nbsp;<i> Menu Principal</i>',
		    'heading' => Icon::show('fa fa-list-alt',['class' => 'fa-2x'], $typeIcon) . '&nbsp;<h4>' . Yii::t('backend', 'Main Menu') . '</h4>',
		    'items' => [
		        // Important: you need to specify url as 'controller/action',
		        // not just as 'controller' even if default action is used.

		    	['label' => Icon::show('fa fa-street-view',['class' => $typeLong], $typeIcon) . '&nbsp; Funcionarios',
		        	'items' => [
		        			['label' => 'Crear Funcionario', 'url' => ['/funcionario/funcionario/index-create'], 'options' => ['value' => '300', 'id' => '300']],
		        			['label' => 'Modificar Funcionario', 'url' => ['/funcionario/funcionario/view-lista'], 'options' => ['value' => '301', 'id' => '301']],
		        			//['label' => 'Desincorporar Funcionario', 'url' => '#', 'options' => ['value' => '302', 'id' => '302']],
		        			//['label' => 'Asignar a Grupo de Trabajo', 'url' => '#', 'options' => ['value' => '303', 'id' => '303']],
		        			['label' => 'Asignar Solicitud', 'url' => ['funcionario/solicitud/funcionario-solicitud/index-create'], 'options' => ['value' => '304', 'id' => '304']],
		        			['label' => 'Desincorporar Solicitud', 'url' => ['funcionario/solicitud/funcionario-solicitud/index-delete'], 'options' => ['value' => '305', 'id' => '305']],
		        			['label' => 'Listar Solicitud', 'url' => ['funcionario/solicitud/solicitud-asignada/index'], 'options' => ['value' => '306', 'id' => '306']],
		        			['label' => Icon::show('user',['class' => 'fa-2x'], $typeIcon) . 'Usuarios', 'url' => '#',
		        				'items' => [
		        							['label' => 'Crear Cuenta','url' =>['opcion-funcionario/buscar-funcionario-usuario']],
		        							['label' => 'Desincorporar Cuenta','url' => '#'],
		        				]
		        			],

		        			// ['label' => Icon::show('fa fa-users',['class' => 'fa-2x'], $typeIcon) . 'Grupos de Trabajo (Perfiles)', 'url' => '#',
		        			// 	'items' => [
		        			// 				['label' => 'Crear Grupo de Trabajo (Perfiles)','url' => ['grupotrabajo/grupos-trabajo/create']],
		        			// 				['label' => 'Modificar Grupo de Trabajo','url' => ['grupotrabajo/grupos-trabajo/index']],
		        			// 				['label' => 'Desincorporar Grupo de Trabajo','url' => ['grupotrabajo/grupos-trabajo/desincorporacion']],
		        			// 	]
		        			// ],
		        	]
		        ],

		        ['label' => Icon::show('fa fa-list',['class' => $typeLong], $typeIcon) . '&nbsp; Listados',
		        	'items' => [
		        		['label' => 'Solicitudes de Declaracion', 'url' => ['/aaee/listado/listado-solicitud-declaracion/index']],
		        	],
		        ],
		    	// &nbsp; espacio en blanco html
		        // ['label' => Icon::show('fa fa-book',['class' => $typeLong], $typeIcon) . '&nbsp; Registros Maestros',
		        // 	'items' => [
		        // 			['label' => 'Registros Datos Básicos', 'url' => ['/registromaestro/datosbasico/create'], 'options' => ['value' => '520', 'id' => '500']],
		        // 			//Icon::show('fa fa-book',['class' => 'fa-2x'], $typeIcon) . '&nbsp;
		        // 	]
		        // ],

		        ['label' => Icon::show('fa fa-laptop',['class' => $typeLong], $typeIcon) . '&nbsp; Solicitudes',
		        	'items' => [
		        		//	SOLICITUDES DE ACTIVIDADES ECONOMICAS
		        		['label' => Icon::show('fa fa-briefcase',['class' => $typeLong], $typeIcon) . '&nbsp; Actividades Economicas',
		        			'items' => [
		        						['label' => 'Inscripción de Actividades Economicas','url' => ['/aaee/inscripcionactecon/inscripcion-actividad-economica/index', 'id' => 81]],
		        					   	['label' => 'Inscripción de Sucursales', 'url' => ['/aaee/inscripcionsucursal/inscripcion-sucursal/index', 'id' => 85]],
		        					   	['label' => 'Autorizar Ramos', 'url' => ['/aaee/autorizarramo/autorizar-ramo/index', 'id' => 96]],
		        					   	// ['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Licencia',
		        					   	// 	'items' => [
	        					   		// 		['label' => 'Solicitar Emision', 'url' => ['/aaee/licencia/licencia-solicitud/index', 'id' => 113]],
	        					   		// 		['label' => 'Descargar Licencia', 'url' => ['/aaee/licencia/licencia-generar/index']],
		        					   	// 	]
		        					   	// ],
		        					   	// ['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					   	// 	'items' => [
	        					   		// 		['label' => 'Solicitar Emision', 'url' => ['/aaee/solvencia/solvencia-actividad-economica-solicitud/index', 'id' => 114]],
	        					   		// 		['label' => 'Descargar Solvencia', 'url' => ['/aaee/solvencia/solvencia-actividad-generar/index']],
		        					   	// 	]
		        					   	// ],
		        					   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Modificion de Datos',
		        					   		'items' => [
		        					   				['label' => 'Anexo de Ramo', 'url' => ['/aaee/anexoramo/anexo-ramo/index', 'id' => 70]],
		        					   				['label' => 'Desincorporacion de Ramo', 'url' => ['/aaee/desincorporaramo/desincorporar-ramo/index', 'id' => 103]],
		        					   				['label' => 'Corrección de RIF', 'url' => ['/aaee/correccioncedularif/correccion-cedula-rif/index', 'id' => 87]],
		        					   				//['label' => 'Corrección de RIF', 'url' => Yii::$app->urlManagerFrontend->createUrl('aaee/correccioncedularif/correccion-cedula-rif/index') .'&id=87'],
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
		        					   	// ['label' => Icon::show('fa fa-hand-paper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Cesación de Actividades',
		        					   	// 	'items' => [
		        					   	// 			['label' => 'Temporal', 'url' => '#'],
		        					   	// 			['label' => 'Definitiva', 'url' => '#'],
		        					   	// 	]
		        					   	// ],
		        			]
		        		],

		        		//	SOLICITUDES DE INMUEBLES URBANOS
		        		['label' => Icon::show('fa fa-home',['class' => $typeLong], $typeIcon) . '&nbsp; Inmuebles Urbanos',
		        			'items' => [
		        						// ['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					 //   		'items' => [
	        					  //  				['label' => 'Solicitar Emision', 'url' => ['/inmueble/solvencia/solvencia-inmueble/index', 'id' => 116]],
	        					  //  				['label' => 'Descargar Solvencia', 'url' => ['/inmueble/solvencia/solvencia-inmueble-generar/index']],
		        					 //   		]
		        					 //   	],
		        						['label' => 'Inscripción de Inmuebles Urbanos', 'url' => ['/inmueble/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos']],
		        						['label' => 'Avaluo Catastral', 'url' => ['/inmueble/avaluo-catastral-inmuebles-urbanos/index']],
		        						['label' => 'Datos Registro de Propiedad', 'url' => ['/inmueble/registros-inmuebles-urbanos/index']],
		        						['label' => 'Certificado Catastral', 'url' => ['/inmueble/cedula-catastral-inmuebles-urbanos/index']],
		        						['label' => 'Renovación de Certificado Catastral', 'url' => ['/inmueble/renovacion-certificado-catastral-inmuebles-urbanos/index']],
		        						['label' => 'Linderos', 'url' => ['/inmueble/linderos-inmuebles-urbanos/index']],
		        						['label' => 'Cambio de Numero Catastral', 'url' => ['/inmueble/cambio-numero-catastral-inmuebles-urbanos/index']],
		        						['label' => 'Cambio de Propietario(VENDEDOR)', 'url' => ['inmueble/cambio-propietario-vendedor-inmuebles-urbanos/index','id' =>12]], // vendedor:12
		        						['label' => 'Cambio de Propietario(COMPRADOR)', 'url' => ['inmueble/cambio-propietario-comprador-inmuebles-urbanos/buscar-vendedor','id' =>11]], // comprador:11
		        						['label' => 'Integración de Parcela', 'url' => ['/inmueble/integracion-inmuebles-urbanos/integracion-inmuebles']],
		        						['label' => 'Desintegración de Parcela', 'url' => ['inmueble/desintegracion-inmuebles-urbanos/index']],
		        						['label' => 'Cambio a Propiedad Horizontal', 'url' => ['/inmueble/cambio-a-propiedad-horizontal-inmuebles-urbanos/index']],
		        						['label' => 'Cambio de Otros Datos del Inmueble', 'url' => ['/inmueble/cambio-otros-datos-inmuebles-urbanos/index']],
		        						['label' => 'Modificar Avaluo', 'url' => '#'],
		        						['label' => 'Desincorporación de Inmueble', 'url' => ['/inmueble/desincorporacion-inmuebles-urbanos/index']],

		        			]
		        		],

		        		//	SOLICITUDES DE VEHICULOS
		        		['label' => Icon::show('fa fa-car',['class' => $typeLong], $typeIcon) . '&nbsp; Vehiculos',
		        			'items' => [
		        						// ['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Solvencia',
		        					 //   		'items' => [
	        					  //  				['label' => 'Solicitar Emision', 'url' => ['/vehiculo/solvencia/solvencia-vehiculo/index', 'id' => 115]],
	        					  //  				['label' => 'Descargar Solvencia', 'url' => ['/vehiculo/solvencia/solvencia-vehiculo-generar/index']],
		        					 //   		]
		        					 //   	],
		        						['label' => 'Inscripci&oacute;n de Vehiculos', 'url' => ['vehiculo/vehiculos/create']],
		        						// ['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . 'Calcomania',
					        			// 	'items' => [
			        					// 		['label' => 'Administrar funcionarios responsables de entregar calcomania','url' => ['/vehiculo/calcomania/administrarfuncionario/administrar-funcionario/busqueda-funcionario']],
			        					// 		['label' => 'Deshabilitar Funcionarios para la gestion de Calcomanias','url' => ['/vehiculo/calcomania/deshabilitarfuncionario/deshabilitar-funcionario/busqueda-funcionario']],

			        					// 		['label' => 'Generar Lote de Calcomania','url' => ['/vehiculo/calcomania/generarlote/generar-lote-calcomania/generar-lote-calcomania']],

			        					// 		['label' => 'Deshabilitar Lote de Calcomanias','url' => ['/vehiculo/calcomania/deshabilitarlote/deshabilitar-lote-calcomania/busqueda-lote-calcomania']],

			        					// 		['label' => 'Administrar Lote de Calcomanias Generadas','url' => ['/vehiculo/calcomania/administrarlotecalcomania/administrar-lote-calcomania/busqueda-multiple']],

			        					// 		['label' => 'Asignar Calcomanias a funcionario (Distribuir entre funcionario responsables)','url' => ['/vehiculo/calcomania/administrarcalcomaniafuncionario/administrar-calcomania-funcionario/seleccionar-funcionario']],


			        					// 		['label' => 'Administrar Entrega de calcomania','url' => ['/vehiculo/calcomania/asignarcalcomaniacontribuyente/asignar-calcomania-contribuyente/seleccionar-tipo-contribuyente']],

			        					// 		['label' => 'Cierre de Lote','url' => ['/vehiculo/calcomania/cierrelote/cierre-lote-calcomania/busqueda-lote']],

					        			// 	],
					        			// ],
		        						// ['label' => 'Solvencia', 'url' => '#'],
		        						// ['label' => 'Cambio de Propietario', 'url' => '#'],
		        						// ['label' => 'Cambio de Placa', 'url' => ['vehiculo/vehiculos/cambio-placa']],
		        						// ['label' => 'Desincorporaci&oacute;n de Vehiculo', 'url' => '#'],
		        						// ['label' => 'Cambio de Otros Datos del Vehiculo', 'url' => ['vehiculo/vehiculos/busqueda']],
		        			],
		        		],

		        		//	PROPAGANDA COMERCIAL
		        		['label' => Icon::show('fa fa-file-powerpoint-o',['class' => $typeLong], $typeIcon) . '&nbsp; Propaganda Comercial',
		        			'items' => [
		        						['label' => 'Inscripción de Propagandas', 'url' => ['propaganda/inscripcionpropaganda/inscripcion-propaganda/index', 'id' => 75]],
		        						//['label' => 'Renovación de Propagandas', 'url' => '#'],
		        						// ['label' => 'Desincorporación de Propaganda', 'url' => ['propaganda/propaganda/desincorporacion']],
		        						// ['label' => 'Cambio de Otros Datos de Propaganda', 'url' => ['propaganda/propaganda/index']],
		        						//['label' => 'Solvencia', 'url' => '#'],
		        			]
		        		],

		        		//	ESPECTACULO PUBLICO
		        // 		['label' => Icon::show('fa fa-cubes',['class' => $typeLong], $typeIcon) . '&nbsp; Espectaculo Publico',
		        // 			'items' => [
		        // 						['label' => 'Inscripción de Espectaculo', 'url' => '#'],
		        // 						['label' => 'Suspención de Espectaculo', 'url' => '#'],
		        // 						['label' => 'Sellado y Troquelado de Entradas', 'url' => '#'],
		        // 						['label' => 'Cambio de Otros Datos de Espectaculos', 'url' => '#'],
		        // 			]
		        // 		],

		        // 		//	APUESTAS LICITAS
		        // 		['label' => Icon::show('fa fa-diamond',['class' => $typeLong], $typeIcon) . '&nbsp; Apuestas Licitas',
		        // 			'items' => [
		        // 						['label' => 'Inscripción de Apuestas', 'url' => ['apuestalicita/apuestas-licita/create']],
		        // 						['label' => 'Cambio de Otros Datos de Apuestas Licitas', 'url' => ['apuestalicita/apuestas-licita/index']],
		        // 						['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        // 			]
		        // 		],

		        // 		//	Otras
		        		// ['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Otras',
		        		// 	'items' => [
		        		// 				['label' => 'Pago Parcial', 'url' => '#'],
		        		// 				['label' => 'Convenio de Pago', 'url' => '#'],
		        		// 				['label' => 'Cheque Devuelto', 'url' => '#'],
		        		// 				['label' => 'Credito Fiscal', 'url' => '#'],
		        		// 				['label' => 'Multas', 'url' => '#'],
		        		// 				['label' => 'Fiscalización', 'url' => '#'],
		        		// 				['label' => 'Auditorias', 'url' => '#'],
		        		// 				['label' => 'Registro de Agente de Retención', 'url' => '#'],
		        		// 				['label' => 'Recibo de Pago', 'url' => '#'],
		        		// 				['label' => 'Cierre', 'url' => '#'],
		        		// 				//['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        		// 				//['label' => 'Declaración de Apuestas Licitas', 'url' => '#'],
		        		// 	]
		        		// ],
		        	]
		        ],

		        //	Recibo
        		// ['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . '&nbsp; Recibo',
        		// 	'items' => [
        		// 				['label' => 'Crear Recibo de Pago', 'url' => ['recibo/recibo/index']],
        		// 				['label' => 'Consulta de Recibos', 'url' => ['recibo/recibo-consulta/index']],
        		// 				['label' => 'Solicitar Anulación de Recibo', 'url' => ['recibo/anular-recibo/index']],
        		// 	]
        		// ],

        		//	Planilla
        		['label' => Icon::show('fa fa-indent',['class' => $typeLong], $typeIcon) . '&nbsp; Liquidacion',
        			'items' => [
        						['label' => 'Liquidar Actividad Economica', 'url' => ['aaee/liquidar/liquidar-actividad-economica/index']],
        						['label' => 'Liquidar Actividad Economica (Definitiva)', 'url' => ['aaee/liquidar/liquidar-actividad-economica-definitiva/index']],
        						['label' => 'Liquidar Inmueble Urbano', 'url' => ['inmueble/liquidar/liquidar-inmueble/index']],
        						['label' => 'Liquidar Vehiculo', 'url' => ['vehiculo/liquidar/liquidar-vehiculo/index']],
        						//['label' => 'Liquidar Propaganda', 'url' => ['propaganda/liquidar/liquidar-propaganda/index']],
        						['label' => 'Liquidar Tasa', 'url' => ['tasa/liquidar/liquidar-tasa/index']],
        						['label' => 'Consulta de Planilla', 'url' => ['planilla/planilla-consulta/index']],
        			]
        		],

		        //	PANEL MAESTRO
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


        		//	AJUSTAR PAGOS
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


        		//	PAGOS
        		// ['label' => Icon::show('fa fa-money',['class' => $typeLong], $typeIcon) . '&nbsp; Pagos y Deudas',
        		// 	'items' => [
        		// 				['label' => 'Recibo', 'url' => '#'],
        		// 				['label' => 'Caja', 'url' => '#'],
        		// 				['label' => 'Elaborar Deposito', 'url' => '#'],
        		// 				['label' => 'Pagos Anteriores', 'url' => '#'],
        		// 				['label' => 'Exoneraciones y Exenciones', 'url' => '#'],
        		// 				['label' => 'Cuentas Por Cobrar', 'url' => '#'],
        		// 				['label' => 'Procesar Pagos en Lote', 'url' => '#'],
        		// 				['label' => 'Registros TXT', 'url' => '#'],

        		// 				['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Deudas',

    					 //   		'items' => [
    					 //   				['label' => 'Verificar Deudas', 'url' => ['/deudas/deudascontribuyente/deudas-contribuyente/verificar-deudas-contribuyente']],





		        // 					   		]
		        // 					   	],


	        	// 				['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Pagos',

					   		// 		'items' => [
					   		// 		['label' => 'Verificar Pagos', 'url' => ['/pagos/pagoscontribuyente/pagos-contribuyente/verificar-pagos-contribuyente']],


	        	// 				   	]
	        	// 				],
        		// 	]

        		// ],


        		//PRESUPUESTOS
        			['label' => Icon::show('fa fa-bar-chart',['class' => $typeLong], $typeIcon) . '&nbsp; Presupuestos',
		        	'items' => [
		        		//	SOLICITUDES DE PRESUPUESTOS
		        		['label' => Icon::show('fa fa-briefcase',['class' => $typeLong], $typeIcon) . '&nbsp; Niveles de Presupuestos',
		        			'items' => [
		        						// ['label' => 'Registro','url' => ['/presupuesto/nivelespresupuesto/registrar/registro-niveles-presupuestarios/registro-niveles-presupuestarios']],
		        					 //   	['label' => 'Modifiacion', 'url' => ['/presupuesto/nivelespresupuesto/modificar/modificar-niveles-presupuesto/vista-seleccion']],
		        					 //   	['label' => 'Inactivacion', 'url' => ['/presupuesto/nivelespresupuesto/inactivar/inactivar-niveles-presupuesto/vista-seleccion']],

		        					 //   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Codigos Presupuestarios',
		        					 //   		'items' => [
		        					 //   				['label' => 'Registrar', 'url' => ['/presupuesto/codigopresupuesto/registrar/registrar-codigo-presupuestario/registro-codigo-presupuesto']],
		        					 //   				['label' => 'Modificar/Inactivar', 'url' => ['/presupuesto/codigopresupuesto/modificarinactivar/modificar-codigo-presupuestario/busqueda-codigo-multiple']],

		        					 //   				['label' => 'Cambiar Codigos Presupuestarios entre Niveles Presupuestarios', 'url' => ['presupuesto/codigopresupuesto/cambiarcodigo/cambiar-codigo-presupuestario/busqueda-codigo-presupuestario']],

		        					 //   		]
		        					 //   	],

		        					   	// ['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Ordenanza de Presupuestos',
		        					   	// 	'items' => [

		        					   	// 			['label' => 'Registro', 'url' => ['/presupuesto/ordenanza/registrar/registrar-ordenanza-presupuesto/registrar-ordenanza-presupuesto']],
		        					   	// 			['label' => 'Modificar/Inactivar', 'url' => ['/presupuesto/ordenanza/modificarinactivar/modificar-inactivar-ordenanza-presupuesto/busqueda-ordenanza-presupuesto']],

		        					   	// 	]
		        					   	// ],


		        					   	//  	['label' => Icon::show('fa fa-briefcase',['class' => $typeLong], $typeIcon) . '&nbsp; Presupuestos',
		        					   	// 	'items' => [



		        					   	// 			['label' => 'Cargar Presupuesto', 'url' => ['/presupuesto/cargarpresupuesto/registrar/cargar-presupuesto/vista-ordenanza-presupuesto']],
		        					   	// 			['label' => 'Modificar/Inactivar', 'url' => ['/presupuesto/cargarpresupuesto/modificarinactivar/modificar-inactivar-presupuesto/vista-presupuesto']],

		        					   	// 	]
		        					   	// ],
		        					   	// ['label' => Icon::show('fa fa-table',['class' => $typeLong], $typeIcon) . '&nbsp; Reportes',
		        					   	// 	'items' => [
		        					   	// 			['label' => 'Generar Reporte', 'url' => '#'],

		        					   	// 	]
		        					   	// ],

		        			]
		        		],



		        	//TASAS
        			['label' => Icon::show('fa fa-bar-chart',['class' => $typeLong], $typeIcon) . '&nbsp; Tasas',
		        	'items' => [
		        		//	SOLICITUDES DE PRESUPUESTOS
		        		['label' => Icon::show('fa fa-briefcase',['class' => $typeLong], $typeIcon) . '&nbsp; Catalogo de Tasas',
		        			'items' => [
		        						// ['label' => 'Registro','url' => ['/tasas/registrar/registrar-tasas/registro-tasas']],

		        					 //   	['label' => 'Modificar/inactivar', 'url' => ['/tasas/modificarinactivar/modificar-inactivar-tasas/busqueda-tasa']],

		        					 //   	['label' => 'Replicar Tasas', 'url' => ['/tasas/replicar/replicar-tasas/busqueda-lote-tasas']],

		        					 //   ['label' => 'Reportes', 'url' => ['/tasas/reportes/reportes-tasas/busqueda-multiple-reportes']],

		        					 //   	['label' => Icon::show('fa fa-pencil',['class' => $typeLong], $typeIcon) . '&nbsp; Codigo Subniveles',
		        					 //   		'items' => [
		        					 //   				['label' => 'Registrar', 'url' => ['/tasas/codigosubnivel/registrar/registrar-codigo-subnivel/registro-codigo-subnivel']],

		        					 //   				['label' => 'Modificar/Inactivar', 'url' => ['/tasas/codigosubnivel/modificarinactivar/modificar-inactivar-codigo-subnivel/modificar-codigo-subnivel']],

		        					 //   		]
		        					 //   	],

		        			]
		        		],

		        		],

		        		],

		        		//	SOLICITUDES DE INMUEBLES URBANOS
		        		// ['label' => Icon::show('fa fa-home',['class' => $typeLong], $typeIcon) . '&nbsp; Inmuebles Urbanos',
		        		// 	'items' => [
		        		// 				['label' => 'Inscripción de Inmuebles Urbanos', 'url' => ['/inmueble/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos']],
		        		// 				['label' => 'Avaluo Catastral', 'url' => ['/inmueble/avaluo-catastral-inmuebles-urbanos/index']],
		        		// 				['label' => 'Certificado Catastral', 'url' => '#'],
		        		// 				['label' => 'Renovación de Certificado Catastral', 'url' => '#'],
		        		// 				['label' => 'Solvencia', 'url' => '#'],
		        		// 				['label' => 'Cambio de Numero Catastral', 'url' => ['/inmueble/cambio-numero-catastral-inmuebles-urbanos/index']],
		        		// 				['label' => 'Cambio de Propietario(VENDEDOR)', 'url' => ['inmueble/cambio-propietario-vendedor-inmuebles-urbanos/index','id' =>12]], // vendedor:12
		        		// 				['label' => 'Cambio de Propietario(COMPRADOR)', 'url' => ['inmueble/cambio-propietario-comprador-inmuebles-urbanos/buscar-vendedor','id' =>11]], // comprador:11
		        		// 				['label' => 'Integración de Parcela', 'url' => ['/inmueble/integracion-inmuebles-urbanos/index']],
		        		// 				['label' => 'Desintegración de Parcela', 'url' => ['inmueble/desintegracion-inmuebles-urbanos/index']],
		        		// 				['label' => 'Cambio a Propiedad Horizontal', 'url' => ['/inmueble/cambio-a-propiedad-horizontal-inmuebles-urbanos/index']],
		        		// 				['label' => 'Cambio de Otros Datos del Inmueble', 'url' => ['/inmueble/cambio-otros-datos-inmuebles-urbanos/index']],
		        		// 				['label' => 'Modificar Avaluo', 'url' => '#'],
		        		// 				['label' => 'Desincorporación de Inmueble', 'url' => ['/inmueble/desincorporacion-inmuebles-urbanos/index']],

		        		// 	]
		        		// ],

		        		//	SOLICITUDES DE VEHICULOS
		        		// ['label' => Icon::show('fa fa-car',['class' => $typeLong], $typeIcon) . '&nbsp; Vehiculos',
		        		// 	'items' => [
		        		// 				['label' => 'Inscripci&oacute;n de Vehiculos', 'url' => ['vehiculo/vehiculos/create']],
		        		// 				['label' => Icon::show('fa fa-newspaper-o',['class' => $typeLong], $typeIcon) . 'Calcomania',
					       //  				'items' => [
			        	// 						['label' => 'Administrar funcionarios responsables de entregar calcomania','url' => ['/vehiculo/calcomania/administrarfuncionario/administrar-funcionario/busqueda-funcionario']],
			        	// 						['label' => 'Deshabilitar Funcionarios para la gestion de Calcomanias','url' => ['/vehiculo/calcomania/deshabilitarfuncionario/deshabilitar-funcionario/busqueda-funcionario']],

			        	// 						['label' => 'Generar Lote de Calcomania','url' => ['/vehiculo/calcomania/generarlote/generar-lote-calcomania/generar-lote-calcomania']],

			        	// 						['label' => 'Deshabilitar Lote de Calcomanias','url' => ['/vehiculo/calcomania/deshabilitarlote/deshabilitar-lote-calcomania/busqueda-lote-calcomania']],

			        	// 						['label' => 'Administrar Lote de Calcomanias Generadas','url' => ['/vehiculo/calcomania/administrarlotecalcomania/administrar-lote-calcomania/busqueda-multiple']],

			        	// 						['label' => 'Asignar Calcomanias a funcionario (Distribuir entre funcionario responsables)','url' => ['/vehiculo/calcomania/administrarcalcomaniafuncionario/administrar-calcomania-funcionario/seleccionar-funcionario']],


			        	// 						['label' => 'Administrar Entrega de calcomania','url' => ['/vehiculo/calcomania/asignarcalcomaniacontribuyente/asignar-calcomania-contribuyente/seleccionar-tipo-contribuyente']],

			        	// 						['label' => 'Cierre de Lote','url' => ['/vehiculo/calcomania/cierrelote/cierre-lote-calcomania/busqueda-lote']],

					       //  				],
					       //  			],
		        		// 				// ['label' => 'Solvencia', 'url' => '#'],
		        		// 				// ['label' => 'Cambio de Propietario', 'url' => '#'],
		        		// 				// ['label' => 'Cambio de Placa', 'url' => ['vehiculo/vehiculos/cambio-placa']],
		        		// 				// ['label' => 'Desincorporaci&oacute;n de Vehiculo', 'url' => '#'],
		        		// 				// ['label' => 'Cambio de Otros Datos del Vehiculo', 'url' => ['vehiculo/vehiculos/busqueda']],
		        		// 	],
		        		// ],

		        		//	PROPAGANDA COMERCIAL


		        		//	ESPECTACULO PUBLICO


		        		//	APUESTAS LICITAS



		        	]
		        ],



		        //	CONFIGURACIONES
        		// ['label' => Icon::show('fa fa-cog',['class' => $typeLong], $typeIcon) . '&nbsp; Configuraciones',
        		// 	'items' => [
        		// 				['label' => 'Configurar Ordenanzas', 'url' => '#'],
        		// 				['label' => 'Configurar Vencimiento de Documentos', 'url' => '#'],
        		// 				['label' => 'Configurar Solicitudes', 'url' => ['/configuracion/solicitud/configurar-solicitud/index']],
        		// 				['label' => 'Configurar Convenios de Pagos', 'url' => ['/configuracion/convenios/configurar-convenios/create']],
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