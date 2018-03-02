<?php

namespace frontend\controllers;

use Yii;
use common\models\StockIn;
use common\models\StockInSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * StockInController implements the CRUD actions for StockIn model.
 */
class StockInController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all StockIn models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new StockInSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StockIn model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StockIn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new StockIn();
        if ($model->load(Yii::$app->request->post())) {
            $save = Stockin::CreateStock($model);
            if ($save) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionCancel() {
        $data = Yii::$app->request->post();
        $order_id = $data['id'];
        return \common\models\Order::cancel_request($order_id);
    }

    public function actionApprove() {
        $data = Yii::$app->request->post();
        $order_id = $data['id'];
        $user_id = $data['user_id'];
        $order_request_id = $data['order_request_id'];
        return StockIn::approve($order_id, $user_id, $order_request_id);
    }

    /**
     * Updates an existing StockIn model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionGetunits($id, $user_id) {
        return StockIn::getRemaningStock($id, $user_id);
    }

    public function actionAllstock() {
        $q = Yii::$app->request->get('q');
        $type = Yii::$app->request->get('type');
        $type_order = Yii::$app->request->get('type_order');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return StockIn::getStocks($q, $type, $type_order);
    }

    /**
     * Deletes an existing StockIn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StockIn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockIn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = StockIn::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
