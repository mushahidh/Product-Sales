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

        if ($model->load(Yii::$app->request->post())) {
            
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
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
                    $usersPlevel = new \common\models\UserProductLevel();
                    $allLevel = \common\models\UsersLevel::findOne(['sr'=>$entry['user_level_id']]);
                    $usersPlevel->sr = $entry['id'] ;
                    $usersPlevel->units = $entry['units'] ;
                    $usersPlevel->price = $entry['price'] ;
                    $usersPlevel->product_id = $model->product_id;
                    $usersPlevel->user_level_id = $allLevel->id;
                    $usersPlevel->validate();
                    $usersPlevel->save();
                 }
                       catch (\Exception $e) {
                           var_dump($usersPlevel->getErrors());
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
        $settingPrice = \common\models\Setting::find()->one();
        $rolebasedPricing = array_search('Role Based Pricing', \common\models\Lookup::$pricing_level);
        if($rolebasedPricing  == $settingPrice->pricing_method){
         return  $pricingDetail = \common\models\UserProductLevel::pricingData($id, $user_level, $product_id, $type = null, $check_units = true);
        }else{
           return $pricingDetail = \common\models\QuantityBasedPricing::pricingData($id, $user_level, $product_id, $type = null, $check_units = true);
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
