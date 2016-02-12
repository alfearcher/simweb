<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PruebaForm extends Model
{
    public $fecha;
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['fecha'], 'required'],
            [['fecha'], 'date', 'format' => 'dd-MM-yyyy'],

            // email has to be a valid email address
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fecha' => 'Calendario',
        ];
    }

   
}
