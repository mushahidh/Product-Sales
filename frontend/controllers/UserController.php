<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAllcustomer()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->where(['user_level_id' => null]);
        $dataProvider->query->andwhere(['parent_id' => null]);
        return $this->render('customer_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $all_status = \common\models\helpers\Statistics::allStatusDashboard($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'all_status' => $all_status,
        ]);
    }
    public function actionError($error)
    {
        return $this->render('error', [
            'error' => $error,
        ]);
    }

    public function actionImport()
    {
        $model = new \common\models\Upload();
        $data = "";
        $count = 0;
        $result = 0;
        if ($model->load(Yii::$app->request->post())) {

            $array_MR = array();
            $array_SuperVIP = array();
            $array_VIP = array();
            $file = \yii\web\UploadedFile::getInstance($model, 'file');
            $data = \common\components\Excel::import($file->tempName, ['setFirstRecordAsKeys' => true]);

            foreach ($data as $entry) {
                // try {
                if ($entry['Level'] != "อื่นๆ") {
                    $user_model = new User();
                    $user_model->isNewRecord = true;
                    $user_model->id = null;
                    $user_model->username = '' . $entry['User'];
                    $user_model->email = '' . $entry['Email'];
                    $user_model->phone_no = '' . $entry['Phone'];
                    $user_model->address = '' . $entry['Address'];
                    $user_model->province = '' . $entry['Province'];
                    $user_model->first_name = '' . $entry['Name'];
                    $user_model->password = '' . $entry['Password'];

                    $user_model->setPassword($user_model->password);
                    $user_model->generateAuthKey();
                    if (strpos($entry['Level'], 'MR') !== false) {
                        $user_model->parent_id = Yii::$app->user->identity->id;
                        $user_model->user_level_id = array_search('Management Team', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '5000';
                        $user_model->unit_price = '390';
                    } elseif (strpos($entry['Level'], 'Super VIP') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_MR, 'quantity');
                        $parent_ID = $array_MR[$max_key]['id'];
                        $array_MR[$max_key]['quantity'] -= 1000;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('Super Vip Team', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '1000';
                        $user_model->unit_price = '440';
                    } elseif (strpos($entry['Level'], 'VIP') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_SuperVIP, 'quantity');
                        $parent_ID = $array_SuperVIP[$max_key]['id'];
                        $array_SuperVIP[$max_key]['quantity'] -= 500;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('VIP Team', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '500';
                        $user_model->unit_price = '480';
                    } elseif (strpos($entry['Level'], 'Pro') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_VIP, 'quantity');
                        $parent_ID = $array_VIP[$max_key]['id'];
                        $array_VIP[$max_key]['quantity'] -= 250;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('PRO Level', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '250';
                        $user_model->unit_price = '520';
                    } elseif (strpos($entry['Level'], 'Inter') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_VIP, 'quantity');
                        $parent_ID = $array_VIP[$max_key]['id'];
                        $array_VIP[$max_key]['quantity'] -= 100;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('INTER Level', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '100';
                        $user_model->unit_price = '550';
                    } elseif (strpos($entry['Level'], 'Advance') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_VIP, 'quantity');
                        $parent_ID = $array_VIP[$max_key]['id'];
                        $array_VIP[$max_key]['quantity'] -= 50;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('ADVANCE Level', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '50';
                        $user_model->unit_price = '590';
                    } elseif (strpos($entry['Level'], 'Begin') !== false) {
                        $max_key = \common\models\helpers\ArrayUtils::MaxAttributeInAssociativeArray($array_VIP, 'quantity');
                        $parent_ID = $array_VIP[$max_key]['id'];
                        $array_VIP[$max_key]['quantity'] -= 10;
                        $user_model->parent_id = $parent_ID;
                        $user_model->user_level_id = array_search('BEGIN Level', \common\models\Lookup::$user_levels);
                        $user_model->quantity = '10';
                        $user_model->unit_price = '630';
                    }
                    if (!empty($user_model) && !empty($user_model->email)) {
                        $user_model->parent_user = $user_model->parent_id;
                        $result = \common\models\User::CreateUser($user_model);
                        if (strpos($entry['Level'], 'MR') !== false) {
                            $array_MR[] = array('id' => $result, 'quantity' => 5000);
                        } elseif (strpos($entry['Level'], 'Super VIP') !== false) {
                            $array_SuperVIP[] = array('id' => $result, 'quantity' => 1000);
                        } elseif (strpos($entry['Level'], 'VIP') !== false) {
                            $array_VIP[] = array('id' => $result, 'quantity' => 500);
                        }
                    }
                }
                // } catch (\Exception $e) {
                //     var_dump($e);
                //     exit();
                //     continue;
                // }
            }
        }
        return $this->render('user_upload', [
            'model' => $model,
        ]);
    }
    public function actionFullfillment()
    {

        $curl = curl_init();
        $postal_code = '10300';
        $province = 'กรุงเทพมหานคร';
        $district = 'เขตดุสิต';
        $cust_name = "cent";
        $cust_addr = "g5/2";
        $mobile_no = '1223232';
        $sku = "ABSOLUT"; //BEYDEY1
        $external_id = 567576;
        $amount = 780;
        $quantity = 1;
        if (preg_match('/^10.{3}$/', $postal_code) || preg_match('/^11.{3}$/', $postal_code) || preg_match('/^12.{3}$/', $postal_code)) {
            $ship_method = "ALP";
        } else {
            $ship_method = "K2D";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oms.sokochan.com/api/1.0/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{ 	"external_id":"' . $external_id . '","order_number":"' . $external_id . '", "shipping":"' . $ship_method . '","cod_amount":"' . $amount . '","customer":{ "name":"' . $cust_name . '","address":"' . $cust_addr . '", "province":"' . $province . '", "district":"' . $district . '","postal_code":"' . $postal_code . '","mobile_no":"' . $mobile_no . '"}, 	"order_items":[{"item_sku":"' . $sku . '","item_code":"","item_qty":' . $quantity . '}]}',
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic QWJzb2x1dGVBUEk6ODBlMTUxYWM4MWU1ZWQ1MGU4NTY0NDk5YWM3NmM1Mjk=",
                "cache-control: no-cache",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          
            return false;
            die("cURL Error #:" . $err);
        } else {
            $resp = json_decode($response, true);
           
            if ($resp['code'] == 200) {
                return $resp['order_code'];
            } else {
                return false;
            }
        }

    }
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGetParentId($id)
    {
        $parent_id = User::findOne(['id' => $id]);
        return $parent_id->parent_id;
    }

    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            $result = \common\models\User::CreateUser($model);
            if (isset($result["error"])) {
                return $this->redirect(['error', 'error' => $result["error"]]);

            } else if (isset($result["username"])) {
                return $this->render('create',
                    [
                        'model' => $model,
                    ]);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCustomer()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post($model))) {
            $customerCreate = User::CreateCustomer($model);
            if ($customerCreate) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('customer_create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $oldmodel = $this->findModel($id);
            $updateUser = User::updateUser($model, $oldmodel);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetuseraddress($id)
    {
        $user_detail = User::findOne(['id' => $id]);
        return Json::encode($user_detail, $asArray = true);
    }

//  parent user of selected user
    public function actionGetUsers()
    {
        $q = Yii::$app->request->get('q');
        $user_level = Yii::$app->request->get('user_level');
        $company_user = Yii::$app->request->get('company_user');
        $parent_id = Yii::$app->request->get('parent_id');
        $user_id = Yii::$app->request->get('user_id');
        $include_parent = Yii::$app->request->get('include_parent');
        $include_self = Yii::$app->request->get('include_self');
        if (empty($include_parent)) {
            $include_parent = false;
        }
        else
        {
            $include_parent=filter_var($include_parent, FILTER_VALIDATE_BOOLEAN);
        }
        if (empty($include_self)) {
            $include_self = true;
        }
        else
        {
            $include_self=filter_var($include_self, FILTER_VALIDATE_BOOLEAN);
        }
       
        $super_vip_level = array_search('Super Admin', \common\models\Lookup::$user_levels);
        if ($user_level == $super_vip_level) {
            $company_user = false;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \common\models\User::getUsers($q, $parent_id, $user_id, $user_level, $company_user, $include_parent, $include_self);
    }
//child user of selected user
    public function actionChildusers()
    {
        $q = Yii::$app->request->get('q');
        $type = Yii::$app->request->get('type');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \common\models\User::getAllChildUsers($q, $type);
    }
// all level
    public function actionGetLevels()
    {
        $q = Yii::$app->request->get('q');
        $parent_id = Yii::$app->request->get('parent_id');
        $max_user = Yii::$app->request->get('max_user');
        $include_parent = Yii::$app->request->get('include_parent');
        $company_user = Yii::$app->request->get('company_user');
        $include_all_child = false;
        if (!empty($company_user)) {
            $include_all_child = true;
        }
        if (empty($include_parent)) {
            $include_parent = false;
        }
        $id = Yii::$app->request->get('id');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \common\models\UsersLevel::getLevels($q, $parent_id, $max_user, $include_parent, $include_all_child);
    }
    public function actionGetSellerLevels()
    {
        $q = Yii::$app->request->get('q');
        $parent_level = Yii::$app->request->get('parent_level');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \common\models\UsersLevel::getSellerLevels($q, $parent_level);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
