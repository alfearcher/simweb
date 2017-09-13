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
 *  @file view-maestro-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-06-2017
 *
 *  @view view-maestro-solicitud.php
 *  @brief vista que muestra los datos principales de la solicitud.
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

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
    use kartik\icons\Icon;
    use yii\web\View;
    use backend\controllers\menu\MenuController;
    use yii\widgets\Pjax;
    use yii\widgets\DetailView;

?>
<div class="row" style="width: 100%;">
    <div class="maestro-solicitud">
        <div class="row" style="width: 100%;">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => Yii::t('backend', 'Nro. Solicitud'),
                        'value' => $model->nro_solicitud,
                    ],
                    [
                        'label' => Yii::t('backend', 'Usuario Creador'),
                        'value' => $model->usuario,
                    ],
                    [
                        'label' => Yii::t('backend', 'Fecha'),
                        'value' => $model->fecha_hora_creacion,
                    ],
                    [
                        'label' => Yii::t('backend', 'ID'),
                        'value' => $model->id_contribuyente,
                    ],
                    [
                        'label' => Yii::t('backend', 'Contribuyente'),
                        'value' => $model->getContribuyente($model->id_contribuyente),
                    ],
                    [
                        'label' => Yii::t('backend', 'Impuesto'),
                        'value' => $model->impuestos->descripcion,

                    ],
                    [
                        'label' => Yii::t('backend', 'Tipo/Solicitud'),
                        'value' => $model->tipoSolicitud->descripcion,

                    ],
                    [
                        'label' => Yii::t('backend', 'Nivel Aprobacion'),
                        'value' => $model->nivelAprobacion->descripcion,
                    ],
                    [
                        'label' => Yii::t('backend', 'Condicion'),
                        'value' => $model->estatusSolicitud->descripcion,
                    ],
                    [
                        'label' => Yii::t('backend', 'Causa Negacion'),
                        'value' => ( (int)$model->causa > 0 ) ? $model->causaNegacion->descripcion : '',
                    ],
                    [
                        'label' => Yii::t('backend', 'Observacion'),
                        'value' => $model->observacion,
                    ],
                    [
                        'label' => Yii::t('backend', 'Funcionario Responsable'),
                        'value' => $model->user_funcionario,
                    ],
                    [
                        'label' => Yii::t('backend', 'Fecha Proceso'),
                        'value' => $model->fecha_hora_proceso,
                    ],

                ],
            ])?>
        </div>
    </div>
</div>