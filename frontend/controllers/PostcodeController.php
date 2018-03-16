<?php

namespace frontend\controllers;

use common\models\Postcode;
use common\models\PostcodeSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PostcodeController implements the CRUD actions for Postcode model.
 */
class PostcodeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Postcode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostcodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionImport()
    {
        $model = new \common\models\Upload();
        $data = "";
        $count = 0;
        $result = 0;
        if ($model->load(Yii::$app->request->post())) {

          $file = \yii\web\UploadedFile::getInstance($model, 'file');
            $data = \common\components\Excel::import($file->tempName, ['setFirstRecordAsKeys' => true]);

            foreach ($data as $entry) {
                 try {
                    $postal_code = new \common\models\Postcode();
                    $postal_code->validate();
                    $postal_code->province = $entry['province'] ;
                    $postal_code->district = $entry['district'] ;
                    $postal_code->zip = ''.$entry['zip'] ;
                    $postal_code->save();
                 }
                       catch (\Exception $e) {
                     continue;
                 }
            }
        }
        return $this->render('user_upload', [
            'model' => $model,
        ]);
    }
    /**
     * Displays a single Postcode model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Postcode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Postcode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Postcode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionAllCode()
    {
        $q = Yii::$app->request->get('q');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \common\models\Postcode::getCodes($q);
    }
    /**
     * Deletes an existing Postcode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Postcode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Postcode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Postcode::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
