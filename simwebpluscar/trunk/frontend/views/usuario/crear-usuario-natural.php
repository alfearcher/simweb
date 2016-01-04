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
 *  @file crear-usuario-natural.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class crear-usuario-natural
 *  @brief .Vista para el formulario de la busqueda de persona natural  
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
use backend\models\registromaestro\TipoNaturaleza;
// 
$this->title = 'Busqueda Persona Natural';

$this->params['breadcrumbs'][] = $this->title;
$lista = ['Extranjero' , 'Venezolano'];
?>
 



<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            <div class="panel-body" >
                <table class="table table-striped">
                  


                            <div class="col-sm-6">
                            <div class="row" style="width:100%;">
                                <p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('frontend', 'Naturaleza') ?></small></i></p>
                        
<!-- COMBO NATURALEZA -->
                                <div class="container-fluid" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;">
                                    <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'naturaleza')->dropDownList($lista,[
                                                                                                    'id' => 'naturaleza',
                                                                                                    'prompt' => Yii::t('frontend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])->label(false)
                                            ?>
                                        </div>
                                    </div>
<!-- FIN DE COMBO NATURALEZA -->

<!-- CEDULA -->
                                    <div class="col-sm-4" style="padding-left: 25px;">

                                          
                                               <div class="cedula">
                                                 <?= $form->field($model, 'cedula')->textInput([
                                                                                            'id' => 'cedula',
                                                                                            'style' => 'height:32px;width:122px;',
                                                                                           
                                                                                            //'maxlength' => $maxLength,
                                                                                          ])->label(false) ?>
                                        </div>
                                    </div>
                    </div> <!-- Fin de row -->

               </table>
               
               <!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <?= Html::submitButton(Yii::t('backend', Yii::t('frontend', 'Search')),
                                                                                                      [
                                                                                                        'id' => 'btn-update',
                                                                                                        'class' => 'btn btn-success',
                                                                                                        'name' => 'btn-update',
                                                                                                        'value' => 1,
                                                                                                      ])
                                            ?>
                                             <?= Html::a(Yii::t('frontend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
                                        </div>
                                    </div>
<!-- Fin de Boton para aplicar la actualizacion -->

                                

<!-- Boton para salir de la actualizacion -->
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                           
                                        </div>
                                    </div>
<!-- Fin de Boton para salir de la actualizacion -->
      
            </div>
        </div>
    </div>
        

<?php $form->end() ?>

