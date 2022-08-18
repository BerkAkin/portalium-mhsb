<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use portalium\theme\widgets\Tabs;
use yii\bootstrap\Tabs;
use portalium\site\Module;
use diginova\mhsb\models\Banks;
use diginova\mhsb\models\Companies;
use portalium\theme\widgets\Panel;

$this->title = Module::t('Banks');
$this->params['breadcrumbs'][] = ['label' => Module::t('Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t('Create Banks');
$this->params['pageDesc'] = Module::t('vendor and customer');



$actions[] = Html::a(Module::t(''), ['manage', 'type' => Banks::TYPE_CUR, 'action' => 'create'] ,['class' => 'btn btn-success fa fa-university','id' => 'create-banks']);
Panel::begin(['title' => Module::t('Banks'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]);

echo $this->render(($type == Banks::TYPE_CUR && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
    'model' => $model,
    'dataProvider' => $dataProvider,
    'modelCompany' => $modelCompany,
    'types' => $types,
    'type' => Banks::TYPE_CUR,
    'typesCurr' => $typesCurr,
    'url' => ($model->isNewRecord) ?
        ["create", 'type' => Banks::TYPE_CUR] :
        ['update', 'id' => $model->id, 'type' => Banks::TYPE_CUR],
    
]);


Panel::end();
?>
