<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    use kartik\date\DatePicker;
    

    $form = ActiveForm::begin([
        
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'enableClientScript' => true,
        
    ]);   
?>    

        <div class="row">
            <div class="col-sm-7">
                <div class="panel-primary">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="fecha-nac">
                            <?php echo '<label>Check Issue Date</label>';
                             echo DatePicker::widget([
                            'name' => 'check_issue_date', 
                            'value' => date('d-M-Y', strtotime('+2 days')),
                            'options' => ['placeholder' => 'Select issue date ...'],
                            'pluginOptions' => [
                                'format' => 'dd-M-yyyy',
                                'todayHighlight' => true
    ]
]);
                              ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>  

    <?php ActiveForm::end(); ?>