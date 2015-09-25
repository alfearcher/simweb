<?php

namespace backend\controllers\vehiculo\calcomania;

use Yii;
use backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm;
use backend\models\vehiculo\calcomania\FuncionarioCalcomaniaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\conexion\ConexionController;
use yii\helpers\Url;

/**
 * FuncionarioCalcomaniaController implements the CRUD actions for FuncionarioCalcomaniaForm model.
 */
class FuncionarioCalcomaniaController extends Controller
{
    public $conn;
    public $conexion;
    public $transaccion;
    public $layout = 'layout-main';
    
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
     * Lists all FuncionarioCalcomaniaForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FuncionarioCalcomaniaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all FuncionarioCalcomaniaForm models.
     * @return mixed
     */
    public function actionBusquedaFuncionario()
    {
        $msg='';
        $model = new FuncionarioCalcomaniaForm();

        if ($model->load(Yii::$app->request->post())) {
            $t = 'funcionarios';
            $t2 = 'funcionario_calcomania';
            $conn = New ConexionController();
                
            $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
            $this->conexion->open();            
            $transaccion = $this->conexion->beginTransaction();

            if (Yii::$app->request->post('formAsignate') == 1) {
                if (Yii::$app->request->post('btnAccion') == 'Asignate') {                   
                    $arrayDatos = Yii::$app->request->post();

                    $newArrayDatos = array(
                            'id_funcionario' => $arrayDatos["FuncionarioCalcomaniaForm"]['id_funcionario'],
                            'naturaleza' => $arrayDatos["FuncionarioCalcomaniaForm"]['naturaleza'],
                            'ci' => $arrayDatos["FuncionarioCalcomaniaForm"]['ci'],
                            'estatus' => Yii::$app->request->post('estatus'),
                    );
                    if ( $conn->guardarRegistro($this->conexion, $t2, $newArrayDatos ) ) {
                        $transaccion->commit();
                        $tipoError = 0;
                        $msg = "REGISTRO EXITOSO!....Espere";
                    } else {
                        $transaccion->rollBack();
                        $tipoError = 0;
                        $msg = "AH OCURRIDO UN ERROR!....Espere";
                    }
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("busqueda-funcionario")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                if (Yii::$app->request->post('btnAccion') == 'Update') {
                    
                    $arrayDatos = Yii::$app->request->post();
                    $arrayCondition = ['id_funcionario' => $arrayDatos["FuncionarioCalcomaniaForm"]['id_funcionario']];
                    $upDateArray = ['estatus' => Yii::$app->request->post('estatusNew')];

                    if ( $conn->modificarRegistro($this->conexion, $t2, $upDateArray, $arrayCondition ) ) {
                        $transaccion->commit();
                        $tipoError = 0;
                        $msg = "REGISTRO EXITOSO!....Espere";
                    } else {
                        $transaccion->rollBack();
                        $tipoError = 0;
                        $msg = "AH OCURRIDO UN ERROR!....Espere";
                    }
                    $url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("busqueda-funcionario")."'>";
                    return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
                }
                $this->conexion->close();
            }else{
                $arrayDatos = $model->attributes;
                $sql = "select f.* from funcionarios f where f.ci = '".$arrayDatos["ci"]."' and f.naturaleza = '".$arrayDatos["naturaleza"]."'";
                $result = $conn->buscarRegistro($this->conexion, $sql);

                if (empty($result)) {
                    $msg = "<script>alert('El Funcionario no existe')</script>";
                    return $this->render('busqueda-funcionario', [
                        'model' => $model,
                        'msg' => $msg,
                    ]);
                }else{
                    $sqlCondicion = "select fc.estatus from funcionario_calcomania fc where ci = '".$arrayDatos["ci"]."' and naturaleza = '".$arrayDatos["naturaleza"]."'";
                    $result['condicion'] = $conn->buscarRegistro($this->conexion, $sqlCondicion);
                    if (empty($result['condicion'])) {
                        $result['btnAccion'] = 'Asignate';
                        $result['condicion'] = 0;
                    }elseif ($result['condicion'][0]["estatus"] == '0') {
                         $result['condicion'] = 0;
                         $result['btnAccion'] = 'Update';
                    }else{
                        $result['condicion'] = 1;
                        $result['btnAccion'] = 'Update';
                    }
                    return $this->render('create', [
                        'model' => $model,
                        'result' => $result,
                        'msg' => $msg,
                    ]);
                } 
            }           
        } else {
            return $this->render('busqueda-funcionario', [
                'model' => $model,
                'msg' => $msg,
            ]);
        }

        
    }


    public function determinarRangos($seleccion, $postIntervalo = 0){

        $conn = New ConexionController();                
        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
        $this->conexion->open();            
        $transaccion = $this->conexion->beginTransaction();   

        $sqlLote = "select lc.* from lote_calcomania lc where lc.ano_impositivo = '".date('Y')."' and lc.inactivo = 0";
        $resultLote = $conn->buscarRegistro($this->conexion, $sqlLote);

        foreach ($seleccion as $key => $value) {

                    $sql = "select fc.id_funcionario_calcomania, fc.id_funcionario, fc.naturaleza, fc.ci,
                        f.id_funcionario, f.nombres, f.apellidos,
                        dc.id_distribucion_calcomania, dc.id_distribucion_calcomania,
                        dc.id_lote_calcomania, dc.rango_final,
                        dc.rango_inicial, dc.estatus, dc.observacion
                        from funcionario_calcomania fc 
                        inner join funcionarios f 
                        on fc.id_funcionario = f.id_funcionario
                        left join distribucion_calcomania dc
                        on dc.id_distribucion_calcomania = fc.id_funcionario_calcomania
                        where fc.id_funcionario_calcomania = '".$value."'";

                    $result[] = $conn->buscarRegistro($this->conexion, $sql);
                }

                // Dividimos
                $catidadLimite = count($result);

                if ($postIntervalo != 0) {

                    $intervalo = $postIntervalo;

                }else{

                    $divisor = $resultLote[0]['rango_final']  / $catidadLimite;

                    // Comprobamos si es par o impar, el el segundo
                    if ($divisor%2 == 0) {
                       $intervalo = $divisor;
                    }else{
                        $intervalo = $divisor - 1;
                    }
                }
                
                $contador = 0;
                $start = 0;
                $finalRango = (int)$resultLote[0]['rango_final'];
                while ($contador < $catidadLimite) {
                    if ($contador == 0) {
                        $loteArray[$start]['inicio'] = (int)$start+1;
                        $loteArray[$start]['fin'] = (int)$intervalo;
                    }
                    if ($contador == 1) {
                        $loteArray[$contador]['inicio'] = $loteArray[0]['fin'] + 1;
                        $loteArray[$contador]['fin'] = $loteArray[0]['fin'] + $loteArray[$contador]['inicio'] - 1;
                    }
                    if ($contador > 1) {                        
                        $conta = $contador - 1;
                        $loteArray[$contador]['inicio'] = $loteArray[$conta]['fin'] + 1;
                        $loteArray[$contador]['fin'] = $loteArray[$conta]['fin'] + $intervalo - 1;
                    }
                    $contador ++;
                    $keyPosition = $catidadLimite - 1;

                    if ($contador == $catidadLimite) {                        
                        $excento = $resultLote[0]['rango_final'] - $loteArray[$keyPosition]['fin'];
                        $loteArray[$keyPosition]['fin'] = $loteArray[$keyPosition]['fin'] + $excento;
                    }

                }

                $this->conexion->close();

                return $rangoArray = ['result' => $result, 'loteArray' => $loteArray, 'resultLote' => $resultLote];
    }

    /**
     * Displays a single FuncionarioCalcomaniaForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionIntervalo()
    {
        $rangosArray = $this->determinarRangos(Yii::$app->request->post('seleccion'), Yii::$app->request->post('intervalo'));

        $selectIntervalo = 0;

        return $this->render('automatic', [
                    'model' => $rangosArray["result"],
                    'rangosArray' => $rangosArray,
                    'intervalo' => $selectIntervalo,
                ]);
    }

    /**
     * Displays a single FuncionarioCalcomaniaForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionAutomatic()
    {

        $conn = New ConexionController();                
        $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
        $this->conexion->open();            
        $transaccion = $this->conexion->beginTransaction();

        if (Yii::$app->request->post( 'selection' ) != null) {

            if (count(Yii::$app->request->post( 'selection' )) > 1) {

                $rangosArray = $this->determinarRangos(Yii::$app->request->post( 'selection' ), false);

            }else{
                $sql = "select fc.id_funcionario_calcomania, fc.id_funcionario, fc.naturaleza, fc.ci,
                        f.id_funcionario, f.nombres, f.apellidos,
                        dc.id_distribucion_calcomania, dc.id_distribucion_calcomania,
                        dc.id_lote_calcomania, dc.rango_final,
                        dc.rango_inicial, dc.estatus, dc.observacion
                        from funcionario_calcomania fc 
                        inner join funcionarios f 
                        on fc.id_funcionario = f.id_funcionario
                        left join distribucion_calcomania dc
                        on dc.id_distribucion_calcomania = fc.id_funcionario_calcomania
                        where fc.id_funcionario_calcomania = '".Yii::$app->request->post( 'selection' )[0]."'";

                $result = $conn->buscarRegistro($this->conexion, $sql);

            }

            return $this->render('automatic', [
                    'model' => $rangosArray["result"],
                    'rangosArray' => $rangosArray,
                ]);
        } else {           
            $msg = "<script>alert('Debes seleccionar al menos un registro')</script>";

            $searchModel = new FuncionarioCalcomaniaSearch();
            $dataProvider = $searchModel->searchFuncionarioCalcomaniaList(Yii::$app->request->queryParams);

            return $this->render('funcionario-calcomania-list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'msg' => $msg,
            ]);
        }
        $this->conexion->close();
    }

    /**
     * Creates a new FuncionarioCalcomaniaForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        echo "<pre>"; die(var_dump(Yii::$app->request->post())); echo "</pre>";
        $model = new FuncionarioCalcomaniaForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_funcionario_calcomania]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Displays a single FuncionarioCalcomaniaForm model.
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
     * Updates an existing FuncionarioCalcomaniaForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_funcionario_calcomania]);
        } else {
            return $this->actionDistribuirLote();
        }
    }

    /**
     * Deletes an existing FuncionarioCalcomaniaForm model.
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
     * Finds the FuncionarioCalcomaniaForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FuncionarioCalcomaniaForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FuncionarioCalcomaniaForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all FuncionarioCalcomaniaForm models.
     * @return mixed
     */
    public function actionDistribuirLote()
    {

        $msg = '';
        $searchModel = new FuncionarioCalcomaniaSearch();
        $dataProvider = $searchModel->searchFuncionarioCalcomaniaList(Yii::$app->request->queryParams);

        return $this->render('funcionario-calcomania-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'msg' => $msg,
        ]);

        // $t = 'funcionario_calcomania';
        // $t2 = 'funcionarios';
        // $conn = New ConexionController();
            
        // $this->conexion = $conn->initConectar('db');     // instancia de la conexion (Connection)
        // $this->conexion->open();            
        // $transaccion = $this->conexion->beginTransaction();

        // $sql = 'select fc.naturaleza, fc.ci, fc.id_funcionario, fc.id_funcionario_calcomania, fc.estatus, f.id_funcionario, f.nombres, f.apellidos, f.cargo 
        //         from  '.$t.' fc left join '.$t2.' f on fc.id_funcionario = f.id_funcionario
        //         where fc.estatus = 1';

        // $result = $conn->buscarRegistro($this->conexion, $sql);

        // // echo "<pre>"; die(var_dump($result)); echo "</pre>";

        // $this->conexion->close();

        // // $searchModel = new FuncionarioCalcomaniaSearch();
        // // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('funcionario-calcomania-list', [
        //     'model' => $result,
        // ]);
    }
}
