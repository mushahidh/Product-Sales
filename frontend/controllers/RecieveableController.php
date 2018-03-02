<?php
namespace frontend\controllers;

use Yii;
use common\models\Gl;
use common\models\GlSearch;
use common\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class RecieveableController extends Controller
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

   public function actionIndex()
    {
        $searchModel = new GlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user_id = Yii::$app->user->getId();
        $Role = Yii::$app->authManager->getRolesByUser($user_id);
         if (!isset($Role['super_admin'])) {
$dataProvider->query->where(['account_id' => Yii::$app->user->identity->id]);
         }
$dataProvider->query->where(['order_id' =>Null]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate() {
        $model = new Gl();

        if ($model->load(Yii::$app->request->post())) {
            $photo = UploadedFile::getInstance($model, 'payment_slip');
            if ($photo !== null ) {
              $model = Gl::slipSave($photo, $model);
            }
            $payment_method  = array_search('Cash on Delivery', \common\models\Lookup::$order_status);
            $createRecieveable = Gl::create_gl($model->amount * -1,$model->receivable_user,$model->payable_user,'',$payment_method,$model->payment_slip);
            return $this->redirect(['//gl/view', 'id' => $createRecieveable]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }
     public function actionView($id)
    {
        return $this->render('view', [
            'model' => Gl::findOne(['id'=>$id]),
        ]);
    }
    public function actionGetrecieveableamount($id,$payable_id){
           $receivable_account=\common\models\Account::findOne(['user_id'=>$id,'account_type'=>1]);
        $payable_account=\common\models\Account::findOne(['user_id'=>$payable_id,'account_type'=>2]);
    
        return Gl::totalRecieveable($receivable_account->id,$payable_account->id);
       
    }



}
