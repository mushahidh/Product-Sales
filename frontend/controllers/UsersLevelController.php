<?php

namespace frontend\controllers;

use Yii;
use common\models\UsersLevel;
use common\models\UsersLevelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersLevelController implements the CRUD actions for UsersLevel model.
 */
class UsersLevelController extends Controller
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
     * Lists all UsersLevel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersLevelSearch();
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
                    $userslevel = new \common\models\UsersLevel();
                    $userslevel->validate();
                    $userslevel->sr = $entry['id'] ;
                    $userslevel->name = $entry['name'] ;
                    $userslevel->display_name = $entry['display_name'] ;
                    $userslevel->parent_id = ''.$entry['parent_id'] ;
                    $userslevel->max_user = ''.$entry['max_user'] ;
                    $userslevel->save();
                 }
                       catch (\Exception $e) {
                           var_dump($userslevel->getErrors());
                           exit();
                     continue;
                 }
            }
        }
        return $this->render('user_upload', [
            'model' => $model,
        ]);
    }
    /**
     * Displays a single UsersLevel model.
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
     * Creates a new UsersLevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsersLevel();

        if ($model->load(Yii::$app->request->post())) {
            $parent_id = UsersLevel::findOne(['id'=>$model->parent_id]);
            $model->parent_id =  $parent_id->sr;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UsersLevel model.
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

    /**
     * Deletes an existing UsersLevel model.
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
     * Finds the UsersLevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UsersLevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsersLevel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
