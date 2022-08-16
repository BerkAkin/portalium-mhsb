<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use portalium\theme\widgets\Portlet;
use yii\bootstrap\Tabs;
use diginova\mhsb\Module;
use diginova\mhsb\models\Definitions;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Module::t('Transactions');

$this->params['breadcrumbs'][] = ['label' => Module::t( 'Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t( 'Create Transaction');
$this->params['pageDesc'] = Module::t( 'collecting and payment');

$tabs = Tabs::widget([
    'id' => 'transactions',
    'items' => [
        [
            'label' => Module::t('Collecting'),
            'content' => $this->render(($type == Definitions::TYPE_COL && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerCol,
                'companies' => $companiesCol,
                'types' => $typesCol,
                'currencies' => $currencies,
                'type' => Definitions::TYPE_COL,
                'tab' => 'transactions-tab0',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::TYPE_COL,  '#' => 'transactions-tab0'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::TYPE_COL, '#' => 'transactions-tab0']
            ])
        ],
        [
            'label' => Module::t('Payment'),
            'content' => $this->render(($type == Definitions::TYPE_PAY && ($action == 'update' || $action == 'create')) ? '_form' : '_index', [
                'model' => $model,
                'provider' => $providerPay,
                'companies' => $companiesPay,
                'types' => $typesPay,
                'currencies' => $currencies,
                'type' => Definitions::TYPE_PAY,
                'tab' => 'transactions-tab1',
                'url' => ($model->isNewRecord) ?
                    ["create", 'type' => Definitions::TYPE_PAY,  '#' => 'transactions-tab1'] :
                    ['update', 'id' => $model->id, 'type' => Definitions::TYPE_PAY, '#' => 'transactions-tab1']
            ])
        ],
    ]
]);

$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_COL, 'action' => 'create', '#' => 'transactions-tab0'] ,['class' => 'btn btn-success glyphicon glyphicon-import','id' => 'create-col']);
$actions[] = Html::a(Module::t(''), ['manage', 'type' => Definitions::TYPE_PAY, 'action' => 'create', '#' => 'transactions-tab1'] ,['class' => 'btn btn-danger glyphicon glyphicon-export','id' => 'create-pay']);
?>
<?php Portlet::begin(['title' => Module::t( 'Transactions'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]); ?>
<?= $tabs ?>
<?php Portlet::end(); ?>
