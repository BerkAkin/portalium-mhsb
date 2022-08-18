<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use diginova\mhsb\Module;
use portalium\theme\widgets\Portlet;

$this->title = Module::t( 'Reports');

$this->params['breadcrumbs'][] = ['label' => Module::t('accounting', 'Accounting'), 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] = Module::t( 'Accounting Reports');
$this->params['pageDesc'] = Module::t( 'get monthly accounting reports');

$data['tabs'] = Tabs::widget([
    'id' => 'reports',
    'items' => [
        [
            'label' => 'Extract',
            'content' => $this->render('_extract', [
			'provider' => $providerExt,
			'date' => $date,
            ])
        ],
        [
            'label' => Module::t('Vat'),
            'content' => $this->render('_vat', [
                'provider' => $providerVat,
                'year' => $year
            ])
        ],
    ]
]);

$actions[] = Html::dropDownList('year',[$year], $years, ['onchange'=>'this.form.submit()']);
$actions[] = Html::button(Module::t('Export'),['class' => 'btn btn-success','id' => 'export-button']);
?>

<?= Html::beginForm(['report/manage'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?php Portlet::begin(['title' => Module::t( 'Reports'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]) ?>
<?= $data['tabs'] ?>
<?php Portlet::end() ?>
<?= Html::endForm() ?>
