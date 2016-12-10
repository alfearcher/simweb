<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => 'mongosoft\soapserver\Action',
        ];
    }

    /**
     * @param string $name
     * @return string
     * @soap
     */
    public function getIndex()
    {
        return 'Hello World!';
    }
}

?>