<?php

namespace diginova\mhsb\controllers\web;


use diginova\mhsb\models\Companies;
use diginova\mhsb\models\CompaniesSearch;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\DocumentsSearch;
use diginova\mhsb\models\Items;
use diginova\site\models\Categories;
use Yii;
use portalium\web\Controller as WebController;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use diginova\mhsb\Module;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\data\ActiveDataProvider;

/**
 * SurveysController implements the CRUD actions for Documents model.
 */
class DocumentController extends WebController
{
    public $model;
    public $modelItems;
    public $provider;
    public $providerSal;
    public $providerPur;
    public $providerEmp;
    public $providerExps;
    public $companyTypes;
    public $companies;
    public $categories;
    public $currencies;
    public $itemTypesSal;
    public $itemTypesEmp;
    public $itemTypesPur;
    public $itemTypesExps;
    public $typesEmp;
    public $typesSal;
    public $typesPur;
    public $typesExps;


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
        return $this->redirect(['document/manage']);
        
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
        $this->model = $model = new Documents();

        $session = Yii::$app->session;
        $postParams = Yii::$app->request->post();

        if (yii::$app->request->isPost) {
            $itemsFormName = (new Items())->formName();
            $valid = ($model->load($postParams) && $model->validate());

            $transaction = Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );

            try {
                $valid = $model->save(false) && $valid;

                if ($postItems = yii::$app->request->post($itemsFormName)) {
                    $modelItems = [];
                    foreach ($postItems as $postItem) {
                        $modelItem = new Items();
                        $modelItem->load($postItem, '');
                        $modelItem->document_id = $model->id;
                        $modelItems[] = $modelItem;
                    }

                    if (!Items::validateMultiple($modelItems)) {
                        $valid = false;
                        $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Items Not Valid Values')]);
                    }
                }

                if ($valid) {
                    if ($postItems) {
                        foreach ($modelItems as $modelItem) {
                            $modelItem->save(false);
                        }
                    }

                    $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Document Saved Successfully.')]);
                    $transaction->commit();

                    return $this->redirect(['manage']);
                } else {
                    $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Documents Not Valid Values.')]);
                    $transaction->rollBack();
                }

            } catch (Exception $e) {
                $transaction->rollBack();
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Documents Not Saved.')]);
                throw new BadRequestHttpException($e->getMessage(), 0, $e);
            }
        }


        return $this->redirect(['document/manage',  'type' => $model->type]);
    }

    public function actionUpdate($type,$id)
    {
        $request = yii::$app->request;
        $session = yii::$app->session;
        $postParams = yii::$app->request->post();
        $queryParams = $request->queryParams;

        $this->model = $model = $this->findModel($id);

        if ($model->load($postParams) && $model->validate()) {
            $itemsFormName = (new Items())->formName();
            $valid = true;
            if ($postItems = yii::$app->request->post($itemsFormName)) {
                $modelItems = [];
                foreach ($postItems as $postItem) {
                    $modelItem = new Items();
                    $modelItem->load($postItem, '');
                    $modelItem->document_id = $model->id;
                    $modelItems[] = $modelItem;
                }

                if (!Items::validateMultiple($modelItems)) {
                    $valid = false;
                    $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Items Not Valid Values')]);
                }
            }
            $transaction = Yii::$app->db->beginTransaction(
                Transaction::SERIALIZABLE
            );

            try {
                if ($valid) {
                    $model->save(false);

                    Items::deleteAll(['document_id' => $model->id]);

                    if ($postItems) {
                        foreach ($modelItems as $modelItem) {
                            $modelItem->save(false);
                        }
                    }

                    $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Document Saved Successfully.')]);
                    $transaction->commit();

                    return $this->redirect(['manage']);
                } else {
                    $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t('Documents Not Valid Values.')]);
                    $transaction->rollBack();
                }

            } catch (Exception $e) {
                $transaction->rollBack();
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Documents Not Saved.')]);
                throw new BadRequestHttpException($e->getMessage(), 0, $e);
            }
        }

        return $this->redirect(['document/manage', 'type' => $type, 'id' => $id, 'action' => $this->action->id]);
    }

    public function actionDelete($id)
    {
        $this->model = $model = $this->findModel($id);
        $session = yii::$app->session;
        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );
        try {
            if (Items::deleteAll(['document_id' => $model->id]) && $model->delete()) {
                $transaction->commit();
                $session->setFlash('flashMessage', ['type' => 'success', 'message' => Module::t( 'Documents Deleted Successfully.')]);
            } else {
                $transaction->rollBack();
                $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Documents Not Deleted.')]);
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            $session->setFlash('flashMessage', ['type' => 'error', 'message' => Module::t( 'Documents Not Deleted.')]);
            throw new BadRequestHttpException($e->getMessage(), 0, $e);
        }


        return $this->redirect(['manage']);
    }

    public function renderForm($queryParams)
    {
        $this->model = new Documents();
        $companiesModel = new Companies();
        $modelProvider = new DocumentsSearch();
        $groups = Definitions::getGroupTypes();
        $dataProvider = new ActiveDataProvider([
            'query' => Documents::find()
            ->join('LEFT JOIN', 'definitions', 'definitions.id = documents.def_id')
            ->where(['definitions.type' => $groups['document']]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        $modelProvider->definitionType = Definitions::TYPE_SAL;
        $this->providerSal = $modelProvider->search($queryParams);

        $modelProvider->definitionType = Definitions::TYPE_PUR;
        $this->providerPur = $modelProvider->search($queryParams);

        $modelProvider->definitionType = Definitions::TYPE_EMP;
        $this->providerEmp = $modelProvider->search($queryParams);
        
        $modelProvider->definitionType = Definitions::TYPE_EXPS;
        $this->providerExps = $modelProvider->search($queryParams);

        if (isset($queryParams['id']) && isset($queryParams['type']) && ($queryParams['type'] == Definitions::TYPE_SAL || $queryParams['type'] == Definitions::TYPE_PUR || $queryParams['type'] == Definitions::TYPE_EMP || $queryParams['type'] == Definitions::TYPE_EXPS))
            $this->model = $this->findModel($queryParams['id']);
            
        $this->modelItems = $this->model->isNewRecord ?
            new Items() :
            (!empty($this->model->items) ? $this->model->items : new Items());


        $typesGroup = Companies::getGroups();
        $this->companies = Companies::find()->all();
        $this->companyTypes = Definitions::findAll(['type' => Definitions::TYPE_COM]);
        $this->currencies = Definitions::findAll(['type' => Definitions::TYPE_CUR]);
        $this->itemTypesPur = Definitions::find()->where(['type' => Definitions::TYPE_EXP])->all();
        $this->itemTypesSal = Definitions::find()->where(['type' => Definitions::TYPE_REV])->all();
        $this->itemTypesEmp = Definitions::find()->where(['type' => Definitions::TYPE_EMP])->all();
        $this->itemTypesExps = Definitions::find()->where(['type' => Definitions::TYPE_EXPS])->all();
        $this->typesPur = Definitions::findAll(['type' => Definitions::TYPE_PUR]);
        $this->typesSal= Definitions::findAll(['type' => Definitions::TYPE_SAL]);
        $this->typesEmp= Definitions::findAll(['type' => Definitions::TYPE_EMP]);
        $this->typesExps = Definitions::findAll(['type' => Definitions::TYPE_EXPS]);

        $taxes = (isset($queryParams['id'])) ? $this->calculateTax($this->model):[];
        $data = [
            'companyTypes'=>$this->companyTypes,
            'typesGroup' => $typesGroup,
            'companiesModel' => $companiesModel,
            'model' => $this->model,
            'taxes' => $taxes,
            'provider' => $this->provider,
            'providerSal' => $this->providerSal,
            'providerPur' => $this->providerPur,
            'providerEmp' => $this->providerEmp,
            'providerExps' => $this->providerExps,
            'modelItems' => $this->modelItems,
            'companies' => $this->companies,
            'categories' => $this->categories,
            'currencies' => $this->currencies,
            'itemTypesPur' => $this->itemTypesPur,
            'itemTypesEmp' => $this->itemTypesEmp,
            'itemTypesSal' => $this->itemTypesSal,
            'itemTypesExps' => $this->itemTypesExps,
            'typesPur' => $this->typesPur,
            'typesSal' => $this->typesSal,
            'typesEmp' => $this->typesEmp,
            'typesExps' => $this->typesExps,
            'action' => isset($queryParams['action']) ? $queryParams['action']: '',
            'type' => isset($queryParams['type']) ? $queryParams['type']: '',
            'dataProvider' => $dataProvider
        ];

        return $this->render('manage', $data);
    }
    public function  calculateTax($model){

        $rows = (new \yii\db\Query())
            ->select('tax')
            ->distinct(1)
            ->from('items')
            ->where('document_id ='.$model->id)
            ->groupBy('tax')
            ->orderBy('tax ASC')
            ->all();

        return $rows;
    }
     public function actionCompany() {
        $model = new Companies();
        $typesGroup = Companies::getGroups();
        $types = Definitions::findAll(['type' => Definitions::TYPE_COM]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->save();
        }

        return $this->renderAjax('_company',[
            'model' => new Companies(),
            'types' => $types,
            'typesGroup'=>$typesGroup
        ]);

    }
    public function actionCompanyValidate() {
        $model = new Companies();
      //  return var_dump(Yii::$app->request->post());
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

    }
    public function actionCompanyList($name = null, $id = null){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($name)) {
            $data = Companies::findAll(['name' => $name]);
            $out['results'] = array_values($data);
        }
        else if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Companies::find($id)->name];
        }
        return $out;
    }

    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

