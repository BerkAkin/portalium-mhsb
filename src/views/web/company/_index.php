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



$columns = [
    'name',
    [
        'label' => Module::t('Status'),
        'attribute' => 'type.name',
        'content' => function($model) {
            $statuses = Companies::statusLabels();
            return $statuses[$model->status];
        }
    ],
    [
        'label' => Module::t('Type'),
        'attribute' => 'type.name',
    ],
    [
        'label' => Module::t('Group'),
        'attribute' => 'group',
        'content' => function($model) {
            $groups = Companies::groupLabels();
            return $groups[$model->group];
        }
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'header' => Module::t('Actions'),
        'headerOptions' => ['class' => 'col-md-2'],
        'buttons' => [
            'delete' => function ($url, $model) use ($type) {
                return Html::a('<span class="btn btn-danger glyphicon glyphicon-trash"></span>',
                    Url::toRoute(['company/delete', 'id' => $model->id]));
            },
            'update' => function ($url, $model) use ($type) {
                return Html::a('<span class="btn btn-success glyphicon glyphicon-edit"></span>',
                    Url::toRoute(['company/update',  'type' => $type, 'id' => $model->id]));
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
                'dataProvider' => $provider,
                //'filterModel' => $modelScale,
                'id' => 'companiesGrid',
                'columns' => $columns,
            ]); ?>
        </div>
    </div>
</div>

