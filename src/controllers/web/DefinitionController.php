<?php

namespace diginova\mhsb\controllers\web;


use diginova\mhsb\models\Banks;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\CompaniesSearch;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\DefinitionsSearch;
use Yii;

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use diginova\mhsb\Module;
use portalium\web\Controller as WebController;
/**
 * SurveysController implements the CRUD actions for Surveys model.
 */
class DefinitionController extends WebController
{
    private $model;
    private $banksModel;
    private $provider;
    private $providerPayCol;
    private $providerExpRev;
    private $providerDoc;
    private $providerCom;
    private $providerCur;
    private $providerBank;
    private $providerCard;
    private $providerLab;


    private $typesPayCol;
    private $typesExpRev;
    private $typesDoc;
    private $typesCur;
    private $typesCurr;
    private $types;
    private $typesCom;
    private $typesCard;
    private $typesLab;
    private $typesParent;


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
        return $this->redirect(['manage']);
    }

    public function actionManage()
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $queryParams = $request->queryParams;

        return $this->renderForm($queryParams);
    }

    /* definition model types
     * pay-col
     * exp-rev
     * doc
     */
    public function actionCreate()
    {
        $this->model = $model = new Definitions();

        $session = Yii::$app->session;
        $postParams = Yii::$app->request->post();
        if ($model->load($postParams) && $model->validate()) {
            if ($model->save()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t('Definition Saved Successfully.')]);
            } else {
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Saved Successfully.')]);
            }

        }
        return $this->redirect('manage');
    }

    public function actionBankcreate()
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
        return $this->redirect(['manage', 'type' => $model->company_id]);
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
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t('Definition Saved Successfully.')]);
                return $this->redirect(['manage']);
            } else {
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Saved Successfully.')]);
            }

        }
        return $this->redirect(['manage', 'type' => $type, 'id' => $id, 'action' => $this->action->id]);
    }

    public function actionDelete($id)
    {
        $this->model = $model = $this->findModel($id);
        $session = yii::$app->session;
        try {
            if ($model->delete()) {
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t('Definition Deleted Successfully.')]);
            } else {
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Not Deleted Successfully.')]);
            }
        } catch (\Exception $e) {
            $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Definition Delete Error')]);
        }
        return $this->redirect(['manage']);
    }

    public function renderForm($queryParams)
    {
        $this->model = new Definitions();
        $this->banksModel = new Banks();
        $modelProvider = new DefinitionsSearch();

        $modelProvider->definitionGroup = Definitions::GROUP_PAYCOL;
        $this->providerPayCol = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_EXPREV;
        $this->providerExpRev = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_DOC;
        $this->providerDoc = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_CUR;
        $this->providerCur = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_COM;
        $this->providerCom = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_CARD;
        $this->providerCard = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::GROUP_LAB;
        $this->providerLab = $modelProvider->search($queryParams);
        $modelProvider->definitionGroup = Definitions::TYPE_COM;
        $this->providerBank = $modelProvider->search($queryParams);



        if (isset($queryParams['id']) && isset($queryParams['type']))
            $this->model = $this->findModel($queryParams['id']);

        $this->types = Definitions::findAll(['type' => Definitions::TYPE_COM]);
        $this->typesCurr = Definitions::findAll(['type' => Definitions::TYPE_CUR]);
        $this->typesLab = Definitions::findAll(['type' => Definitions::TYPE_LAB]);
        $this->typesPayCol = Definitions::getGroupTypes(Definitions::GROUP_PAYCOL);
        $this->typesExpRev = Definitions::getGroupTypes(Definitions::GROUP_EXPREV);
        $this->typesDoc = Definitions::getGroupTypes(Definitions::GROUP_DOC);
        $this->typesCur = Definitions::getGroupTypes(Definitions::GROUP_CUR);
        $this->typesCom = Definitions::getGroupTypes(Definitions::GROUP_COM);
        $this->typesCard = Definitions::getGroupTypes(Definitions::GROUP_CARD);
        $this->typesLab = Definitions::getGroupTypes(Definitions::GROUP_LAB);
        $this->typesParent = Banks::find()->all();

        $data = [
            'model' => $this->model,
            'banksModel' => $this->banksModel,
            'providerPayCol' => $this->providerPayCol,
            'providerExpRev' => $this->providerExpRev,
            'providerDoc' => $this->providerDoc,
            'providerCur' => $this->providerCur,
            'providerCom' => $this->providerCom,
            'provider' => $this->provider,
            'providerBank'=> $this->providerBank,
            'providerCard'=> $this->providerCard,
            'providerLab' => $this->providerLab,
            'typesPayCol' => $this->typesPayCol,
            'typesExpRev' => $this->typesExpRev,
            'typesCard' => $this->typesCard,
            'typesDoc' => $this->typesDoc,
            'types' => $this->types,
            'typesCur' => $this->typesCur,
            'typesCurr' => $this->typesCurr,
            'typesCom' => $this->typesCom,
            'typesLab' => $this->typesLab,
            'typesParent' => $this->typesParent,
            'action' => isset($queryParams['action']) ? $queryParams['action']: '',
            'type' => isset($queryParams['type']) ? $queryParams['type']: ''
        ];

        return $this->render('manage', $data);

    }

    protected function findModel($id)
    {
        if (($model = Definitions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
