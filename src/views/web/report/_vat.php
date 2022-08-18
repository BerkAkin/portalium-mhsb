<?php

namespace diginova\reports;

use DateTime;
use yii\helpers\Html;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use portalium\theme\widgets\Portlet;


$actions[] = Html::dropDownList('year',[$year], $years, ['onchange'=>'this.form.submit()']);
$actions[] = Html::button(Module::t('Export'),['class' => 'btn btn-success','id' => 'export-button']);
?>

<?= Html::beginForm(['report/vat'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?php Portlet::begin(['title' => Module::t( 'Vat'), 'icon' => 'icon-plus font-blue-hoki','actions' => $actions]) ?>
<?php Portlet::end() ?>
<?= Html::endForm() ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'layout' => '{items}',
    'columns' => [
        [
            'attribute' => 'month',
            'content' => function ($model, $key, $index, $column) {
                return Module::t( DateTime::createFromFormat('!m', $model['month'])->format('F'));
            },
            'label' => Module::t('Month')
        ],
        [
            'attribute' => 'tax_total_purchases',
            'content' => function ($model) {
                return number_format((float)$model['tax_total_purchases'], 2, '.', '');
            },
            'label' => Module::t('Deducted')
        ],
        [
            'attribute' => 'tax_total_sales',
            'content' => function ($model) {
                return number_format((float)$model['tax_total_sales'], 2, '.', '');
            },
            'label' => Module::t('Calculated')
        ],
        [
            'attribute' => 'tax_status_month',
            'content' => function ($model) {
                return number_format((float)$model['tax_status_month'], 2, '.', '');
            },
            'label' => Module::t('Status')
        ],

    ]
]); ?>



