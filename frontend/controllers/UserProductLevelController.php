<?php

namespace frontend\controllers;

use common\models\UserProductLevel;
use common\models\UserProductLevelSearch;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserProductLevelController implements the CRUD actions for UserProductLevel model.
 */
class UserProductLevelController extends Controller
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
     * Lists all UserProductLevel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserProductLevelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserProductLevel model.
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
     * Creates a new UserProductLevel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserProductLevel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserProductLevel model.
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
     * Deletes an existing UserProductLevel model.
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

    public function actionLevelpakages()
    {
        $q = Yii::$app->request->get('q');
        //  $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        $type_order = Yii::$app->request->get('type_order');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (empty($type) && $type_order == "Order") {

            if (!is_null($q)) {
                $query = new \yii\db\Query();
                $query->select('id as id, units AS text')
                    ->from('user_product_level')
                    ->where(['like', 'units', $q])
                //   ->andWhere(['=', 'user_level_id', $type])
                //  ->andWhere(['like','customer_user_id',$customer_id])
                    ->limit(20);

                $command = $query->createCommand();
                $data = $command->queryAll();
                // if($data){
                $out['results'] = array_values($data);
            } else {
                $query = new \yii\db\Query();
                $query->select('id as id, units AS text')
                    ->from('user_product_level')
                //  ->where(['=', 'user_level_id', $type])
                    ->limit(20);

                $command = $query->createCommand();
                $data = $command->queryAll();
                // if($data){
                $out['results'] = array_values($data);
            }
        } else {

            if (empty($type)) {
                return [];
            }
            if (!is_null($q)) {
                $query = new \yii\db\Query();
                $query->select('id as id, units AS text')
                    ->from('user_product_level')
                    ->where(['like', 'units', $q])
                    ->andWhere(['=', 'user_level_id', $type])
                //  ->andWhere(['like','customer_user_id',$customer_id])
                    ->limit(20);

                $command = $query->createCommand();
                $data = $command->queryAll();
                // if($data){
                $out['results'] = array_values($data);
            } else {

                $query = new \yii\db\Query();
                $query->select('id as id, units AS text')
                    ->from('user_product_level')
                    ->where(['=', 'user_level_id', $type])
                    ->limit(20);

                $command = $query->createCommand();
                $data = $command->queryAll();
                // if($data){
                $out['results'] = array_values($data);
            }
        }
        return $out;
    }
    public function actionGetunits($id)
    {
        $one_unit = UserProductLevel::find()->where(['id' => $id])->one();
        $detai_item['unit'] = $one_unit->units;
        $detai_item['price'] = $one_unit->price;
        return json_encode($detai_item);
    }
    public function actionGetunitsprice($id, $user_level, $product_id, $type = null, $check_units = true)
    {
        if ($type != null) {
            if ($type == "Return") {
                $unit_price = UserProductLevel::find()->select(['min(price) as price'])->where(['product_id' => $product_id])->one();
                $detai_item['price'] = $unit_price['price'];
                return json_encode($detai_item);
            }
        }
        $query = UserProductLevel::find()->where(['product_id' => $product_id]);
        if ($type != 'Request') {
            $query->andWhere(['user_level_id' => $user_level]);
        }
        $query->andWhere(['<=', 'units', $id]);
        if ($check_units == 'false') {
            $price_query = new \yii\db\Query();
            $price_query->select('min(price) as min_price,max(price) as max_price')
                ->from('user_product_level')
                ->where(['product_id' => $product_id]);
            if ($type != 'Request') {
                $price_query->andWhere(['user_level_id' => $user_level]);
            }
            $price_query = $price_query->one();
        }
        $query->orderBy(['price' => SORT_ASC]);
        $one_unit = $query->one();
        if ($one_unit) {
            $detai_item['price'] = $one_unit->price;
            return json_encode($detai_item);
        } else if ($price_query != null) {

            if (UserProductLevel::find()->where(['user_level_id' => $user_level])->andWhere(['product_id' => $product_id])->andWhere(['<', 'units', $id])->count() > 1) {
                $detai_item['price'] = $price_query['min_price'];
            } else {
                $detai_item['price'] = $price_query['max_price'];
            }
            return json_encode($detai_item);
        } else {
            $one_unit = UserProductLevel::find()->where(['user_level_id' => $user_level])->andWhere(['product_id' => $product_id])->min('units');
            if ($one_unit) {
                $detai_item['units'] = $one_unit;
                return json_encode($detai_item);
            } else {
                $product = \common\models\Product::find()->where(['id' => $product_id])->one();
                $detai_item['price'] = $product['price'];
                return json_encode($detai_item);
            }
        }

    }
    /**
     * Finds the UserProductLevel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserProductLevel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserProductLevel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
