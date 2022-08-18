<?php

namespace diginova\mhsb\controllers\web;

use portalium\web\Controller as WebController;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Transactions;
use diginova\mhsb\models\TransactionsSearch;
use Yii;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use diginova\mhsb\Module;

class TransactionController extends WebController
{
    public $model;

    public $providerPay;
    public $providerCol;
    public $providerCus;
    public $providerVen;
    public $providerCusVen;

    public $typesPay;
    public $typesCol;
    public $companiesCol;
    public $companiesPay;
    public $currencies;
    public $companies;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['transaction/manage']);
    }

    public function actionManage()
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $queryParams = $request->queryParams;

        return $this->renderForm($queryParams);
    }



    public function actionCreate()
    {
        $this->model = $model = new Transactions();

        $session = Yii::$app->session;
        $postParams = Yii::$app->request->post();
        if ($model->load($postParams) && $model->validate()) {
            if ($model->save()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Definition Saved Successfully.')]);
            } else {

                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Definition Not Saved Successfully.')]);
            }
        }

        return $this->redirect(['transaction/manage', 'type' => $model->type]);
    }

    public function actionUpdate($type, $id)
    {
        $request = yii::$app->request;
        $session = yii::$app->session;
        $postParams = yii::$app->request->post();
        $queryParams = $request->queryParams;

        $this->model = $model = $this->findModel($id);

        if ($model->load($postParams) && $model->validate()) {
            if ($model->save()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Definition Saved Successfully.')]);
                return $this->redirect(['manage']);
            } else {
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Definition Not Saved Successfully.')]);
            }
        }
        return $this->redirect(['transaction/manage','type' => $type, 'id' => $id, 'action' => $this->action->id]);
    }

    public function actionDelete($id){
        $model = $this->findModel($id);
        $session = yii::$app->session;
        try{
            if($model->delete()){
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Definition Deleted Successfully.')]);
            }else{
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Definition Not Deleted Successfully.')]);
            }
        }catch (\Exception $e){
            $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Definition Delete Error')]);
        }
        return $this->redirect(['manage']);
    }


    public function renderForm($queryParams){

        $this->model = new Transactions();
        $modelProvider = new TransactionsSearch();

        $modelProvider->definitionType = Definitions::TYPE_COL;
        $this->providerCol = $modelProvider->search($queryParams);
        $modelProvider->definitionType = Definitions::TYPE_PAY;
        $this->providerPay = $modelProvider->search($queryParams);
        $modelProvider->companyType = Companies::GROUP_CUSTOMER;
        $this->providerCus = $modelProvider->search($queryParams);
        $modelProvider->companyType = Companies::GROUP_CUSVEN;
        $this->providerCusVen = $modelProvider->search($queryParams);
        $modelProvider->companyType = Companies::GROUP_VENDOR;
        $this->providerVen = $modelProvider->search($queryParams);

        if (isset($queryParams['id']) && isset($queryParams['type']) && ($queryParams['type'] == Definitions::TYPE_COL || $queryParams['type'] == Definitions::TYPE_PAY))
            $this->model = $this->findModel($queryParams['id']);

        $this->companiesPay = Companies::find()
            ->orWhere(['OR',['group' => Companies::GROUP_VENDOR],['group' => Companies::GROUP_CUSVEN]])
            ->all();
        $this->companiesCol = Companies::find()
            ->orWhere(['OR',['group' =>Companies::GROUP_CUSTOMER],['group' => Companies::GROUP_CUSVEN]])
            ->all();

        $this->typesPay = Definitions::findAll(['type' => Definitions::TYPE_PAY]);
        $this->typesCol = Definitions::findAll(['type' => Definitions::TYPE_COL]);
        $this->currencies = Definitions::findAll(['type' => Definitions::TYPE_CUR]);

        $data = [
            'model' => $this->model,
            'providerPay' => $this->providerPay,
            'providerCol' => $this->providerCol,
            'companiesCol' => $this->companiesCol,
            'companiesPay' => $this->companiesPay,
            'typesPay' => $this->typesPay,
            'typesCol' => $this->typesCol,
            'currencies' => $this->currencies,
            'action' => isset($queryParams['action']) ? $queryParams['action']: '',
            'type' => isset($queryParams['type']) ? $queryParams['type']: ''
        ];

        return $this->render('manage', $data);
    }

    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
