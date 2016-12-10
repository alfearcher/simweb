<?php


    use yii\helpers\Html;
    use yii\web\View;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use kartik\form\ActiveForm;
    use yii\grid\GridView;
    use kartik\icons\Icon;
   


?>


<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('frontend', 'Stickers lot generation') ?> </h3>
</div>

<div><br></div>





<!-- FORMULARIO PARA DATOS DEL FUNCIONARIO -->

<?php $form = ActiveForm::begin([
            'id' => 'form-lote-calcomania',
            'method' => 'post',
            //'action' => ['/usuario/crear-usuario-natural/natural'],
             'enableClientValidation' => true,
             'enableAjaxValidation' => false,
             'enableClientScript' => true,

        ]);

?>



    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Stickers lot generation') ?> 
            </div>
            <div class="panel-body" >

<!-- AÑO IMPOSITIVO -->

                <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'ano_impositivo')->textInput(
                                                                [
                                                                'value' => date('Y'),
                                                                'readonly' => true,
                                                                'id'=> 'ano_impositivo',
                                                                ]);
                    ?>
                
                    </div>
                     </div>
                

<!-- FIN DE AÑO IMPOSITIVO <-->

<!-- RANGO INCICIAL -->

                    <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'rango_inicial')->textInput(
                                                                [
                                                                
                                                                'readonly' => false,
                                                                'id'=> 'rango_inicial',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE RANGO INICIAL <-->

<!-- RANGO FINAL -->

                
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'rango_final')->textInput(
                                                                [
                                                                
                                                                'readonly' => false,
                                                                'id'=> 'rango_final',
                                                                ]);
                    ?>
                
                    </div>
                    </div>
                

<!-- FIN DE RANGO FINAL <-->



                <div class="row">
                    <div class="col-sm-4">
                        <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                    </div>

                    <div class="col-sm-4">
                        <?= Html::a('Return',['/menu/vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:-55px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                    </div>
                </div>





            </div>
        </div>
    </div>


    <div class="inmuebles-index">

 
  

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

        'ano_impositivo',
           // 'id_contribuyente',
            //'ano_inicio',
      
            //'liquidado',
            // 'manzana_limite',
            // 'lote_1',
            // 'lote_2',
            // 'nivel',
            // 'lote_3',
            // 'av_calle_esq_dom',
            // 'casa_edf_qta_dom',
            // 'piso_nivel_no_dom',
            // 'apto_dom',
          'rango_inicial',
            // 'medidor',
            // 'id_sim',
            // 'observacion:ntext',
            // 'inactivo',
            // 'catastro',
          'rango_final',
            // 'tipo_ejido',
            // 'propiedad_horizontal',
            // 'estado_catastro',
            // 'municipio_catastro',
            // 'parroquia_catastro',
            // 'ambito_catastro',
             'usuario',
            // 'manzana_catastro',
            // 'parcela_catastro',
            // 'subparcela_catastro',
            // 'nivel_catastro',
            // 'unidad_catastro',

         
        ],
    ]); ?>

  

</div>



  
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO REGISTRO VEHICULO -->

