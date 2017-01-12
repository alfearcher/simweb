<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\TiposApuesta;
use backend\models\TarifasApuesta;
use backend\models\TiposApuestaSearch;

/**
 * TiposApuestaController implements the CRUD actions for TiposApuesta model.
 */
class TiposApuestaController extends Controller
{
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
     * Lists all TiposApuesta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TiposApuestaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TiposApuesta model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TiposApuesta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TiposApuesta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_unidad]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TiposApuesta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_unidad]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TiposApuesta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TiposApuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TiposApuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TiposApuesta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionLists($id)
    {
        $countUnidad = TiposApuesta::find()->where(['clase_apuesta' => $id, 'inactivo' => 0])->count();

        $tiposapuesta = TiposApuesta::find()->where(['clase_apuesta' => $id, 'inactivo' => 0])->all();

        if ( $countUnidad > 0 ) {
            
            echo "<option> Select</option>";
            
            foreach ($tiposapuesta as $tipoapuesta) {
                
                    echo "<option value='" . $tipoapuesta->tipo_apuesta . "'>" . $tipoapuesta->descripcion . "</option>";
            }
        } else {
                    echo "<option> - </option>";
        }
    }
    
    public function actionPorcentaje($id)
    {
        $countporcentaje = TarifasApuesta::find()->where(['tipo_apuesta' => $id])->count();

        $tiposporcentaje = TarifasApuesta::find()->where(['tipo_apuesta' => $id])->all();

        if ( $countporcentaje > 0 ) {
            
            foreach ($tiposporcentaje as $tipoporcentaje) {
                
                if($tipoporcentaje->porcentaje < 0.0999){
                        
                            $cargar= $tipoporcentaje->porcentaje."0";
                
                } else { 
                            $cargar= $tipoporcentaje->porcentaje."00"; 
                        } 
                 echo "<option value='" . $tipoporcentaje->tipo_apuesta . "'>" . $cargar . "</option>";
            }
        } else {
                    echo "<option> - </option>";
        }
    }
}
