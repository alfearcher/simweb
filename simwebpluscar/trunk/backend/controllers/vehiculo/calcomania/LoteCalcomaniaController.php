<?php

namespace backend\controllers\vehiculo\calcomania;

use Yii;
use backend\models\vehiculo\calcomania\LoteCalcomaniaForm;
use backend\models\vehiculo\calcomania\LoteCalcomaniaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\conexion\ConexionController;

/**
 * LoteCalcomaniaController implements the CRUD actions for LoteCalcomaniaForm model.
 */
class LoteCalcomaniaController extends Controller
{
    public $conn;
    public $conexion;
    public $transaccion;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all LoteCalcomaniaForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LoteCalcomaniaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all LoteCalcomaniaForm models.
     * @return mixed
     */
    public function actionBusquedaLote()
    {
        $searchModel = new LoteCalcomaniaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('busqueda-lote', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LoteCalcomaniaForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LoteCalcomaniaForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LoteCalcomaniaForm();
        $msg='';

        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ( $model->validate() ) {
                $t = $model->tableName();
                $conn = New ConexionController();

                $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                $this->conexion->open();

                $arrayDatos = $model->attributes;
                $transaccion = $this->conexion->beginTransaction();

                $arrayDatos['inactivo'] = 0; // Se activa el lote de calcomania que se pretende cargar
                
                if ( $conn->guardarRegistro($this->conexion, $t, $arrayDatos ) ) {
                    $transaccion->commit();
                    $tipoError = 0;
                    $msg = "REGISTRO EXITOSO!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/calcomania/lote-calcomania/index")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("vehiculo/calcomania/lote-calcomania/create")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();
            }else {
                $model->getErrors();
                
                return $this->render('create', [
                    'model' => $model,
                    'msg' => $msg,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'msg' => $msg,
            ]);
        }
    }

    /**
     * Updates an existing LoteCalcomaniaForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        if (Yii::$app->request->post('LoteCalcomaniaForm')["accion"] == 'Update') {
            $model = new LoteCalcomaniaForm();
        }else{
            $model = $this->findModel($id);
        }

        // echo "<pre>"; die(var_dump(Yii::$app->request->post('LoteCalcomaniaForm')["accion"])); echo "</pre>";

        if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {           
            if ( $model->validate() ) {

                $t = $model->tableName();
                $conn = New ConexionController();
                
                $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
                $this->conexion->open();

                $arrayDatos = Yii::$app->request->post();
                $transaccion = $this->conexion->beginTransaction();

                $arrayCondition = ['id_lote_calcomania' => $model->id_lote_calcomania];

                if ($arrayDatos["LoteCalcomaniaForm"]['inactivo'] == 0) {
                    $arrayDatos["LoteCalcomaniaForm"]['causa'] = '';
                }

                $arrayBitacora = array(
                                    'id_lote_calcomania' => $model->id_lote_calcomania,
                                    'usuario' => Yii::$app->user->identity->username,                                        
                                    'estatus' => $arrayDatos["LoteCalcomaniaForm"]['inactivo'],
                                    'fecha_hora' => date('Y-m-d H:i:s'), 
                                );
                $arrayDatosNew = [
                                    'ano_impositivo' => $arrayDatos["LoteCalcomaniaForm"]['ano_impositivo'],
                                    'rango_inicial' => $arrayDatos["LoteCalcomaniaForm"]['rango_inicial'],
                                    'rango_final' => $arrayDatos["LoteCalcomaniaForm"]['rango_final'],
                                    'observacion' => $arrayDatos["LoteCalcomaniaForm"]['observacion'],
                                    'inactivo' => $arrayDatos["LoteCalcomaniaForm"]['inactivo'],
                                    'causa' => $arrayDatos["LoteCalcomaniaForm"]['causa'],
                                ];

                if ( $conn->modificarRegistro($this->conexion, $t, $arrayDatosNew, $arrayCondition ) ) {

                    $tBitacora = 'bitacora_lote_calcomania';   // nombre de la tabla donde se almacenara la bitacora

                    if ( $conn->guardarRegistro($this->conexion, $tBitacora, $arrayBitacora ) ) { // Almacenamos la bitacora
                        $transaccion->commit();
                        $condicionMsj = 'ACTUALIZADO';
                    }else{
                        $condicionMsj = 'NO SE PUDO COMPLETAR - VIOLACION DE SEGURIDAD - CONTACTE CON EL ADMINISTRADOR';
                        $transaccion->rollBack();
                    }
                    $tipoError = 0;
                    $msg = "REGISTRO ".$condicionMsj."!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/vehiculo/calcomania/lote-calcomania/view").'&id='.$model->id_lote_calcomania."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                } else {
                    $transaccion->rollBack();
                    $tipoError = 0;
                    $msg = "AH OCURRIDO UN ERROR!....Espere";
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/vehiculo/vehiculos/view").'&id='.$model->id_lote_calcomania."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close(); 
            }else {
                $model->getErrors();                
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LoteCalcomaniaForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LoteCalcomaniaForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LoteCalcomaniaForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LoteCalcomaniaForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
