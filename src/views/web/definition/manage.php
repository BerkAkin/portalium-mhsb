<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use portalium\theme\widgets\Panel;
//use portalium\theme\widgets\Tabs;
use yii\bootstrap\Tabs;
use diginova\mhsb\Module;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Banks;

$this->title = Module::t('Definitions');
//$data['title'] 	= Html::encode($this->title);

$this->params['breadcrumbs'][] = ['label' => Module::t('Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t('Create Definition');
$this->params['pageDesc'] = Module::t('type definitions');


$data['tabs'] = Tabs::widget([
    'id' => 'definitions',
    'items' => [
        [
            'label' => Module::t('Payment / Collecting'),
            'content' => $this->render(($type == Definitions::GROUP_PAYCOL && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerPayCol,
                'types' => $typesPayCol,
                'type' => Definitions::GROUP_PAYCOL,
                'tab' => 'definitions-tab0',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_PAYCOL, '#' => 'definitions-tab0'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_PAYCOL, '#' => 'definitions-tab0']
            ])
        ],
        [
            'label' => Module::t('Expense / Revenue'),
            'content' => $this->render(($type == Definitions::GROUP_EXPREV && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerExpRev,
                'types' => $typesExpRev,
                'type' => Definitions::GROUP_EXPREV,
                'tab' => 'definitions-tab1',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_EXPREV, '#' => 'definitions-tab1'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_EXPREV, '#' => 'definitions-tab1']
            ])
        ],
        [
            'label' => Module::t('Document'),
            'content' => $this->render(($type == Definitions::GROUP_DOC && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerDoc,
                'types' => $typesDoc,
                'type' => Definitions::GROUP_DOC,
                'tab' => 'definitions-tab2',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_DOC, '#' => 'definitions-tab2'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_DOC, '#' => 'definitions-tab2']
            ])
        ],
        [
            'label' => Module::t('Currency'),
            'content' => $this->render(($type == Definitions::GROUP_CUR && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerCur,
                'types' => $typesCur,
                'type' => Definitions::GROUP_CUR,
                'tab' => 'definitions-tab3',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_CUR, '#' => 'definitions-tab3'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_CUR, '#' => 'definitions-tab3']
            ])
        ],
        [
            'label' => Module::t('Company'),
            'content' => $this->render(($type == Definitions::GROUP_COM && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerCom,
                'types' => $typesCom,
                'type' => Definitions::GROUP_COM,
                'tab' => 'definitions-tab4',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_COM, '#' => 'definitions-tab4'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_COM, '#' => 'definitions-tab4']
            ])
        ],
        [
            'label' => Module::t('Bank'),
            'content' => $this->render(($type == Definitions::TYPE_COM && ($action == 'update' || $action == 'bankcreate')) ? '_bank' : '_index', [
                'model' => $banksModel,
                'provider' => $providerBank,
                'typesCurr' => $typesCurr,
                'types' => $types,
                'tab' => 'definitions-tab5',
                'type' => Definitions::TYPE_COM,
                'url' => ($model->isNewRecord) ?
                    ["bankcreate", 'type' => Definitions::TYPE_COM] :
                    ['update', 'id' => $model->id, 'type' => Definitions::TYPE_COM],
            ])
        ],
        [
            'label' => Module::t('Card'),
            'content' => $this->render(($type == Definitions::GROUP_CARD && ($action == 'update' || $action == 'create')) ? '_cards' : '_index', [
                'model' => $model,
                'provider' => $providerCard,
                'types' => $typesCard,
                'typesParent' => $typesParent,
                'type' => Definitions::GROUP_CARD,
                'tab' => 'definitions-tab6',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_CARD, '#' => 'definitions-tab6'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_CARD, '#' => 'definitions-tab6']
            ])
        ],
        [
            'label' => Module::t('Label'),
            'content' => $this->render(($type == Definitions::GROUP_LAB && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerLab,
                'types' => $typesLab,
                'type' => Definitions::GROUP_LAB,
                'tab' => 'definitions-tab7',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::GROUP_LAB, '#' => 'definitions-tab7'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::GROUP_LAB, '#' => 'definitions-tab7']
            ])
        ],
    ]
]);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_PAYCOL, 'action' => 'create', '#' => 'definitions-tab0'] ,['class' => 'btn btn-success fa fa-exchange','id' => 'create-paycol']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_EXPREV, 'action' => 'create', '#' => 'definitions-tab1'] ,['class' => 'btn btn-success fa fa-credit-card','id' => 'create-exprev']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_DOC, 'action' => 'create', '#' => 'definitions-tab2'] ,['class' => 'btn btn-success fa fa-files-o','id' => 'create-doc']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_CUR, 'action' => 'create', '#' => 'definitions-tab3'] ,['class' => 'btn btn-success fa fa-money','id' => 'create-cur']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_COM, 'action' => 'create', '#' => 'definitions-tab4'] ,['class' => 'btn btn-success fa fa-industry','id' => 'create-com']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_COM, 'action' => 'bankcreate','#' => 'definitions-tab5'] ,['class' => 'btn btn-success fa fa-university','id' => 'create-banks']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_CARD, 'action' => 'create', '#' => 'definitions-tab6'] ,['class' => 'btn btn-success fa fa-credit-card','id' => 'create-card']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::GROUP_LAB, 'action' => 'create', '#' => 'definitions-tab7'] ,['class' => 'btn btn-success fa fa-tag','id' => 'create-label']);
Panel::begin(['title' => Module::t('Definitions'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]);
echo $data['tabs'];
Panel::end();
?>
