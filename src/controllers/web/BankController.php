<?php

namespace diginova\mhsb\controllers\web;

use diginova\mhsb\models\Banks;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\CompaniesSearch;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\DefinitionsSearch;
use diginova\mhsb\models\Transactions;
use diginova\mhsb\models\TransactionsSearch;
use Yii;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use diginova\mhsb\Module;
use yii\data\ActiveDataProvider;
use portalium\web\Controller as WebController;
/**
 * SurveysController implements the CRUD actions for Bank model.
 */
class BankController extends WebController
{
    public $model;
    public $provider;
    public $types;
    public $typesGroup;
    public $statuses;
    public $typesCurr;
    public $companyName;




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
        return $this->redirect(['bank/manage']);
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
        $this->model = $model = new Banks();

        $session = Yii::$app->session;
        $postParams = Yii::$app->request->post();

        if ($model->load($postParams) && $model->validate()) {

            if ($model->save()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t('Definition Saved Successfully.')]);
            } else {

                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Saved Successfully.')]);
            }
        }
        else{

        }

        return $this->redirect(['bank/manage', 'type' => $model->company_id]);
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
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Saved Successfully.')]);
            }


        }
        return $this->redirect(['bank/manage','type' => $type, 'id' => $id, 'action' => $this->action->id]);
    }

    public function actionDelete($id){
        $this->model = $model = $this->findModel($id);
        $session = yii::$app->session;
        try{
            if($model->delete()){
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Definition Deleted Successfully.')]);
            }else{
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Deleted Successfully.')]);
            }
        }catch (\Exception $e){
            $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Delete Error')]);
        }
        return $this->redirect(['manage']);
    }

    public function renderForm($queryParams){

        $this->model = new Banks();
        $modelCompany = new Companies();
        $modelProvider = new CompaniesSearch();

        $modelProvider->typesGroup = Definitions::TYPE_COM;
        $this->provider = $modelProvider->search($queryParams);
        $modelProvider->group = Definitions::TYPE_CUR;
        $this->provider = $modelProvider->search($queryParams);

        if (isset($queryParams['id']) && isset($queryParams['type']) && $queryParams['type'] == Definitions::TYPE_CUR){
            $this->model = $this->findModel($queryParams['id']);
            $modelCompany = Companies::find()->where(['id' => $this->model->company_id])->one();
        }
            

        $this->statuses = Banks::getStatuses();
        $this->types = Definitions::findAll(['type' => Definitions::TYPE_COM]);
        $this->typesCurr = Definitions::findAll(['type' => Definitions::TYPE_CUR]);
 

        $dataProvider = new ActiveDataProvider([
            'query' => Banks::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $data = [
            'model' => $this->model,
            'modelCompany' => $modelCompany,
            'dataProvider' => $dataProvider,
            'statuses' => $this->statuses,
            'types' => $this->types,
            'typesCurr' => $this->typesCurr,
            'typesGroup' => $this->typesGroup,
            'action' => isset($queryParams['action']) ? $queryParams['action']: '',
            'type' => isset($queryParams['type']) ? $queryParams['type']: ''
        ];
        return $this->render('manage', $data);
    }

    protected function findModel($id)
    {
        if (($model = Banks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
