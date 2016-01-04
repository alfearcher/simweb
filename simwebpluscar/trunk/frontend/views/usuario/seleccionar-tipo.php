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
 *  @file seleccionar-tipo.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuario
 *  @brief .Vista para la seleccion del tipo de usuario que desea registrar.
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  email_existe
 *  username_existe
 *  
 *
 *  @inherits
 *  
 */ 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// 
$this->title = 'Seleccione su tipo de registro';
$this->params['breadcrumbs'][] = $this->title;
?>
 



<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-5">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            <div class="panel-body" >
                <table class="table table-striped">
                    

                
                     <div class="row">
                            <?= Html::a('<strong><center><span class="glyphicon glyphicon-user"> Persona Natural</span></center></strong>', ['/usuario/opcion-crear-usuario/crear-usuario-natural']); ?>
                       </div>
                    <br>
                       <div class="row">
                            <?= Html::a('<strong><center><span class="glyphicon glyphicon-user"> Persona Juridica</span></center></strong>', ['/usuario/crear-usuario-juridico/crear-usuario-juridico']); ?>
                       </div>
                </table>
            </div>
        </div>
    </div>
        

<?php $form->end() ?>

