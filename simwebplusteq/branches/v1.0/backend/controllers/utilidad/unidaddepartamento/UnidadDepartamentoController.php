<?php

namespace backend\controllers\utilidad\unidaddepartamento;

use Yii;
use backend\models\utilidad\unidaddepartamento\UnidadDepartamento;
use backend\models\utilidad\unidaddepartamento\UnidadDepartamentoForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UnidadDepartamentoController implements the CRUD actions for UnidadDepartamento model.
 */
class UnidadDepartamentoController extends Controller
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
     * Lists all UnidadDepartamento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UnidadDepartamentoForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UnidadDepartamento model.
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
     * Creates a new UnidadDepartamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UnidadDepartamento();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_unidad]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UnidadDepartamento model.
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
     * Deletes an existing UnidadDepartamento model.
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
     * Finds the UnidadDepartamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UnidadDepartamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UnidadDepartamento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionLists($id)
    {
       // die('hola, entro a list');
        $countUnidad = UnidadDepartamento::find()->where(['id_departamento' => $id, 'inactivo' => 0])->count();

        $unidades = UnidadDepartamento::find()->where(['id_departamento' => $id, 'inactivo' => 0])->all();

        if ( $countUnidad > 0 ) {
            foreach ($unidades as $unidad) {
                echo "<option value='" . $unidad->id_unidad . "'>" . $unidad->descripcion . "</option>";
            }
        } else {
            echo "<option> - </option>";
        }
    }

}
