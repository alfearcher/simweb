<?php
/**
 *      @copyright © by ASIS CONSULTORES 2012 - 2016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 *
 *      > This library is free software; you can redistribute it and/or modify it under
 *      > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *      > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *      > any later version.
 *      >
 *      > This library is distributed in the hope that it will be usefull,
 *      > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *      > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *      > for more details.
 *      >
 *      > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *      @file MenuController.php
 *
 *      @author Ronny Jose Simosa Montoya
 *
 *      @date 10-07-2015
 *
 *      @class MenuController
 *      @brief Clase permite realizar la consulta principal de la tabla menu para retornar las opciones padre a la vista menu .
 *
 *
 *
 *      @property
 *
 *      @method
 *
 *      @inherits
 *
 */

namespace backend\controllers\menu;
error_reporting(0);

use Yii;
use yii\helpers\Html;
use kartik\icons\Icon;
use yii\web\Controller;
use common\models\menu\Menu;

class MenuController extends Controller
{
    public $layout = 'layoutbase';

    /**
    *   Metodo actionVertical(), retorna las opciones padre del menu a la vista menu.
    * 	@param $conn, instancia de conexion a base de datos.
    * 	@param $btn, obtiene el valor del boton.
    *   @param $ano_impositivo, integer indica el año impositivo de la propaganda, cero (0) no indica nada.
    */
    public function actionVertical()
    {
        $model =  Menu::find()->where( [ 'inactivo'  => '0' ] )->orderBy( 'id_menu' )->all();
        return $this->render( 'index', [ 'model' => $model ] );
    }



    /**
    *   Metodo que permite renderizar un menu de opciones adicionales y
    *   con url configurables.
    *   @param $opciones array.
    */
    public function actionMenuSecundario($opciones = [] )
    {
        return $this->render('/menu/create-menu-secundario', [
                                                                'opciones' => $opciones,
                                                                //'iconOpciones' => $iconOpciones,
                                                            ]);
    }
}