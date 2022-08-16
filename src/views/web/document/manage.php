<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use portalium\theme\widgets\Portlet;
use diginova\mhsb\Module;
use yii\helpers\Url;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\Definitions;

$this->title = Module::t( 'Documents');

$this->params['breadcrumbs'][] = ['label' => Module::t( 'Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t( 'Create Document');
$this->params['pageDesc'] = Module::t( 'sale and purchase');


if($type == Definitions::TYPE_SAL){
    $data['grid'] = $this->render(($action == 'update' || $action == 'create') ?  '_form' : '_index', [
        'companyTypes' => $companyTypes,
        'typesGroup' => $typesGroup,
        'companiesModel' => $companiesModel,
        'model' => $model,
        'taxes' => $taxes,
        'provider' => $providerSal,
        'modelItems' => $modelItems,
        'companies' => $companies,
        'categories' => $categories,
        'types' => $typesSal,
        'currencies' => $currencies,
        'itemTypes' => $itemTypesSal,
        'type' => Definitions::TYPE_SAL,
        'url' => ["create", 'type' => Definitions::TYPE_SAL]         
    ]);
}
else if($type == Definitions::TYPE_PUR){
    $data['grid'] = $this->render(($action == 'update' || $action == 'create') ?  '_form' : '_index', [
        'companyTypes' => $companyTypes,
        'typesGroup' => $typesGroup,
        'companiesModel' => $companiesModel,
        'model' => $model,
        'taxes' => $taxes,
        'provider' => $providerPur,
        'modelItems' => $modelItems,
        'companies' => $companies,
        'categories' => $categories,
        'types' => $typesPur,
        'currencies' => $currencies,
        'itemTypes' => $itemTypesPur,
        'type' => Definitions::TYPE_PUR,
        'url' => ["create", 'type' => Definitions::TYPE_PUR]
    ]);
}
else if($type == Definitions::TYPE_EMP){
    $data['grid'] = $this->render(($action == 'update' || $action == 'create') ?  '_formSelfEmployment' : '_index', [
        'companyTypes' => $companyTypes,
        'typesGroup' => $typesGroup,
        'companiesModel' => $companiesModel,
        'model' => $model,
        'taxes' => $taxes,
        'provider' => $providerEmp,
        'modelItems' => $modelItems,
        'companies' => $companies,
        'categories' => $categories,
        'types' => $typesEmp,
        'currencies' => $currencies,
        'itemTypes' => $itemTypesEmp,
        'type' => Definitions::TYPE_PUR,
        'dataProvider' => $dataProvider,
        'url' => ["create", 'type' => Definitions::TYPE_EMP]
    ]);
}
else if($type == Definitions::TYPE_EXPS){
    $data['grid'] = $this->render(($action == 'update' || $action == 'create') ?  '_form-Expense-Slip' : '_index', [
        'companyTypes' => $companyTypes,
        'typesGroup' => $typesGroup,
        'companiesModel' => $companiesModel,
        'model' => $model,
        'taxes' => $taxes,
        'provider' => $providerExps,
        'modelItems' => $modelItems,
        'companies' => $companies,
        'categories' => $categories,
        'types' => $typesExps,
        'currencies' => $currencies,
        'itemTypes' => $itemTypesExps,
        'type' => Definitions::TYPE_EXPS,
        'dataProvider' => $dataProvider,
        'url' => ["create", 'type' => Definitions::TYPE_EXPS]
    ]);
}
else if($type == null){
   $data['grid'] = $this->render('_index',[
    'typesGroup' => $typesGroup,
    'companiesModel' => $companiesModel,
    'type' => isset($queryParams['type']) ? $queryParams['type']: '',
    'dataProvider' => $dataProvider
    ]);
}


$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_SAL, 'action' => 'create'] ,['class' => 'btn btn-success fa fa-exchange','id' => 'create-sale']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_PUR, 'action' => 'create'] ,['class' => 'btn btn-success fa fa-credit-card','id' => 'create-purchase']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_EMP, 'action' => 'create'] ,['class' => 'btn btn-success fa fa-exchange','id' => 'create-selfemployment']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_EXPS, 'action' => 'create'] ,['class' => 'btn btn-success fa fa-money','id' => 'create-expenseslip']);


Portlet::begin(['title' => Module::t( 'Documents'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]);
echo $data['grid'];
Portlet::end();
?>