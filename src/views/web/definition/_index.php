<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Definitions;



$columns = [
    'name',
    'code',
    [
        'label' => Module::t('Type'),
        'attribute' => 'type.name',
        'content' => function($model) {
            if($model->type == 'payment'){
                return Module::t('Payment');
            }
            else if ($model->type == 'collecting'){
                return Module::t('Collecting');
            }
            else if($model->type == 'paycol'){
                return Module::t('Payment & Collecting');
            }
        }
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'header' => Module::t('Actions'),
        'headerOptions' => ['class' => 'col-md-2'],
        'buttons' => [
            'delete' => function ($url, $model) use ($type,$tab) {
                return Html::a('<span class="btn btn-danger glyphicon glyphicon-trash"></span>',
                    Url::toRoute(['definition/delete', 'id' => $model->id, '#' => $tab]));
            },
            'update' => function ($url, $model) use ($type,$tab) {
                return Html::a('<span class="btn btn-success glyphicon glyphicon-edit"></span>',
                    Url::toRoute(['definition/update',  'type' => $type, 'id' => $model->id, '#' => $tab]));
            }
        ]
    ]];


if($type == Definitions::GROUP_PAYCOL || $type == Definitions::GROUP_DOC || $type == Definitions::GROUP_LAB) unset($columns[1]);
if($type == Definitions::GROUP_CUR || $type == Definitions::GROUP_COM || $type == Definitions::GROUP_LAB) unset($columns[2]);


?>

<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $provider,
                //'filterModel' => $modelScale,
                'id' => 'definitionsGrid',
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>

