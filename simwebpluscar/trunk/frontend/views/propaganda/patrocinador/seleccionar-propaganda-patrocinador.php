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
 *  @file view-solicitud-inscripcion-propaganda.php
 *
 *  @author Manuel Zapata
 *
 *  @date 13-09-2016
 *
 *  @view view-solicitud-inscripcion-propaganda.php
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *  @inherits
 *
 */

    use yii\web\Response;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use backend\models\vehiculo\VehiculoSearch;
    use frontend\models\propaganda\solicitudes\SlPropagandasForm;
    use common\models\propaganda\tarifaspropaganda\TarifasPropagandas;
    use common\models\propaganda\patrocinador\PropagandasPatrocinaodres;

    

?>


 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/propaganda/patrocinador/anular-patrocinador-propaganda/verificar-propaganda'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="row" style="width: 70%;">
    <div class="info-solicitud">
        <div class="row">
            <h3><?= Html::encode('Select Your Advertising') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                        
                [

                    'label' =>"Patrocinador",

                   
                    'value'=> function($data){ 

                        if($data->contribuyente->tipo_naturaleza == 1){

                            return $data->contribuyente->razon_social;

                        }else{

                            return $data->contribuyente->nombres.' '.$data->contribuyente->apellidos;
                        }

                    }
                   

                ],

                [

                    'label' =>"Rif",

                   
                    'value'=> function($data){ 

                        return $data->contribuyente->naturaleza.' '.$data->contribuyente->cedula.'-'.$data->contribuyente->tipo;
                    }

                ],

                [

                    'label' =>"Id Propaganda",

                   
                    'value'=> function($data){ 

                        return $data->propaganda->id_impuesto;
                    }

                ],

                [

                    'label' =>"Tipo Propaganda",

                   
                    'value'=> function($data){ 

                        return $data->propaganda->tipoPropaganda->descripcion;
                    }

                ],

                    
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-verificar-propaganda',

                        'checkboxOptions' => [
                                'id' => 'id-chk-verificar-propaganda',
                              

                                //'onClick' => 'alert("hola " + $(this).val());'
                                //$(this).is(":checked"), permite determinar si un checkbox esta tildado.
                        ],
                        'multiple' => true,
                    ],
        ],
    ]); ?>

     <div class="row">
    <div class="col-sm-4">
    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>

    <div class="col-sm-5" style="margin-left: 0px;">
    
     <?= Html::submitButton("Submit", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;']) ?>

    </div>
  
    <div class="col-sm-2" style="float:right; color:red; font: comic sans ms">
   
    <p><?php echo $errorCheck ?></p>

   
    </div>
    </div>
        </div>
    </div>
</div>