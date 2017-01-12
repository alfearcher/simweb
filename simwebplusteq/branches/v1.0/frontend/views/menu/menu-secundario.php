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
 *  @file -menu-secundario.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com, jperez820@hotnail.com
 *
 *  @date 29-12-2015
 *
 *  @view menu-secundario.php
 *  @brief vista del menu secundario de la aplicacion del lado del cliente. Backend de la aplicacion
 *
 */

 	use yii\helpers\Html;
	use yii\helpers\Url;
	use kartik\icons\Icon;
	use yii\bootstrap\Nav;

 //    Icon::addFramework('elusive', [
 //    	'class' => '\common\icons\ElusiveIconAsset',
 //    	'prefix' => 'el ',
	// ]);

	// map to view file
	//Icon::map($this, 'elusive');
	Icon::map($this, Icon::FA);

    //$color = 'color: #337AB7;';
    $color = 'color: #FFFFFF;';
    $tamano = 'fa-2x';
    $tipoIcon = [Icon::FA, 'elusive'];

    $menuOpciones = require(dirname(__DIR__)) . '/menu/opciones-menu-secundario.php';

    $item = '';
    $iconOpcion = '';
    $iconCaption = '';

    // Se obtienen la opciones del icono
    // if ( count($iconOpciones) > 0 ) {
    //     foreach ($iconOpciones as $key => $value) {
    //         if ( strtolower($iconOpciones[$key]) == 'class' ) {
    //             $iconOpcion[$key] = $value;
    //         } elseif ( strtolower($iconOpciones[$key]) == 'style' ) {
    //             $iconOpcion[$key] = $value;
    //         } elseif ( strtolower($iconOpciones[$key]) == 'title' ) {
    //             $iconOpcion[$key] = $value;
    //         } elseif ( strtolower($iconOpciones[$key]) == 'caption' ) {
    //             $iconCaption[$key] = $value;
    //         }
    //     }
    // } else {
    //     $iconOpcion = [
    //         'class' => $item['class'],
    //         'style' => $item['color'],
    //         'title' => $item['title'],
    //     ];
    // }


    $menuItem = '';
    $menuItems = '';
    foreach ( $opciones as $key => $value ) {
    	if ( count($menuOpciones[$key]) > 0 ) {
    		$item = $menuOpciones[$key];
    		$menuItem = ['label' => Icon::show($item['icon'],
    										   [
    										   		'class' => $item['class'],
    										   		'style' => $item['color'],
    										   		'title' => $item['title'],
                                                    //'target' => ( isset($item['target']) ) ? $item['target'] : '',
    										   ],
    										   $item['tipoIcon']
    										   ),// . ' ' . $iconCaption[$key],
    										   'url' => [$opciones[$key]],
    		];
	    	$menuItems[] = $menuItem;
	    	$item = '';
	    	$menuItem = '';
    	}
    }

?>

<?php
	echo Nav::widget([
	    'items' =>
	    	$menuItems
	    	// ['label' => Icon::show('fa fa-file-text', ['class' => $typeLong, 'style' => 'color: #337AB7;'], $typeIcon) . ' ' . Yii::t('backend', 'Create'), 'url' => [$url['create']], 'visible' => $urlVisible['create']],
	     //    ['label' => Icon::show('fa fa-list', ['class' => $typeLong, 'style' => 'color: #337AB7;'], $typeIcon) . ' ' . Yii::t('backend', 'List'), 'url' => [$url['list']], 'visible' => $urlVisible['list']],
	     //    ['label' => Icon::show('fa fa-floppy-o', ['class' => $typeLong, 'style' => 'color: #337AB7;'], $typeIcon) . ' ' . Yii::t('backend', 'Update'), 'url' => [$url['update']], 'visible' => $urlVisible['update']],
	     //    ['label' => Icon::show('fa fa-trash', ['class' => $typeLong, 'style' => 'color: #337AB7;'], $typeIcon) . ' ' . Yii::t('backend', 'Delete'), 'url' => [$url['delete']], 'visible' => $urlVisible['delete']],
	    ,
	    'options' => [
	    			'class' => 'navbar-nav navbar-right',
	    			//'style' => '.fa fa-list: color',
	     ],
	     'encodeLabels' => false,
	]);
 ?>
