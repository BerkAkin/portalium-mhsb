<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use portalium\theme\widgets\Portlet;
//use portalium\theme\widgets\Tabs;
use yii\bootstrap\Tabs;
use portalium\site\Module;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Companies;

$this->title = Module::t('Companies');

$this->params['breadcrumbs'][] = ['label' => Module::t('Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t('Create Company');
$this->params['pageDesc'] = Module::t('vendor and customer');


$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_COM, 'action' => 'create'] ,['class' => 'btn btn-success glyphicon glyphicon-plus','id' => 'create-company']);
Portlet::begin(['title' => Module::t('Companies'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]);
//echo $data['tabs'];
echo $this->render(($type == Definitions::TYPE_COM && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
    'model' => $model,
    'provider' => $provider,
    'types' => $types,
    'group' => $group,
    'type' => Definitions::TYPE_COM,
    'url' => ($model->isNewRecord) ?
        ["create", 'type' => Definitions::TYPE_COM] :
        ['update', 'id' => $model->id, 'type' => Definitions::TYPE_COM]
]);
Portlet::end();
?>
