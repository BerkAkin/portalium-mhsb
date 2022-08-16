<?php

namespace diginova\mhsb\controllers\web;


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
use portalium\web\Controller as WebController;
/**
 * SurveysController implements the CRUD actions for Companies model.
 */
class CompanyController extends WebController
{
    public $model;
    public $provider;

    public $types;
    public $group;
    public $statuses;




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
        return $this->redirect(['company/manage']);
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
        $this->model = $model = new Companies();

        $session = Yii::$app->session;
        $postParams = Yii::$app->request->post();
        if ($model->load($postParams) && $model->validate()) {
            if ($model->save()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t('Definition Saved Successfully.')]);
            } else {

                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Saved Successfully.')]);
            }

        }

        return $this->redirect(['company/manage', 'type' => $model->type]);
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
        return $this->redirect(['company/manage','type' => $type, 'id' => $id, 'action' => $this->action->id]);
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

        $this->model = new Companies();
        $modelProvider = new CompaniesSearch();

        $modelProvider->companiesGroup = Companies::getGroups();
        $this->provider = $modelProvider->search($queryParams);

        if (isset($queryParams['id']) && isset($queryParams['type']) && $queryParams['type'] == Definitions::TYPE_COM)
            $this->model = $this->findModel($queryParams['id']);

        $this->statuses = Companies::getStatuses();
        $this->types = Definitions::findAll(['type' => Definitions::TYPE_COM]);
        $this->group = Companies::getGroups();
        
        $data = [
            'model' => $this->model,
            'provider' => $this->provider,
            'statuses' => $this->statuses,
            'types' => $this->types,
            'group' => $this->group,
            'action' => isset($queryParams['action']) ? $queryParams['action']: '',
            'type' => isset($queryParams['type']) ? $queryParams['type']: ''
        ];

        return $this->render('manage', $data);
    }

    protected function findModel($id)
    {
        if (($model = Companies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
