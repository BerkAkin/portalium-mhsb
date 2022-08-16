<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Banks;


$columns = [
    [
        'label' => Module::t('Bank Name'),
        'attribute' => 'bank',
        'content' => function($model) {
            return $model->bank_name;
        }
    ],
    [
        'label' => Module::t('Company'),
        'attribute' => 'company.name',
    ],
    [
        'label' => Module::t('Name'),
        'attribute' => 'currency.name',
        'content' => function($model) {
            return $model->name;
        }
    ],
    [
        'label' => Module::t('Number'),
        'attribute' => 'currency.name',
        'content' => function($model) {
            return $model->number;
        }
    ],
    [
        'label' => Module::t('Code'),
        'attribute' => 'currency.name',
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'header' => Module::t('Actions'),
        'headerOptions' => ['class' => 'col-md-2'],
        'buttons' => [
            'delete' => function ($url, $model) use ($type) {
                return Html::a('<span class="btn btn-danger glyphicon glyphicon-trash"></span>',
                    Url::toRoute(['bank/delete', 'id' => $model->id]));
            },
            'update' => function ($url, $model) use ($type) {
                return Html::a('<span class="btn btn-success glyphicon glyphicon-edit"></span>',
                    Url::toRoute(['bank/update',  'type' => $type, 'id' => $model->id]));
            }
        ]
    ]];

//if($type == Definitions::GROUP_PAYCOL || $type == Definitions::GROUP_DOC) unset($columns[1]);
//if($type == Definitions::GROUP_CUR) unset($columns[2]);
?>


<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $modelScale,
                'id' => 'bankGrid',
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>

