<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Transactions;

?>
<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $provider,
                //'filterModel' => $modelScale,
                'id' => 'transactionsGrid',
                'columns' => [
                    [
                        'label' => Module::t('Company'),
                        'attribute' => 'company.name',
                    ],
                    [
                        'label' => Module::t('Type'),
                        'attribute' => 'type.name',
                    ],
                    [
                        'label' => Module::t('Amount'),
                        'attribute' => 'amount',
                    ],
                    [
                        'label' => Module::t('Currency'),
                        'attribute' => 'currency.name',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'header' => Module::t('Actions'),
                        'headerOptions' => ['class' => 'col-md-2'],
                        'buttons' => [
                            'delete' => function ($url,$model) use ($type,$tab) {
                                return Html::a('<span class="btn btn-danger glyphicon glyphicon-trash"></span>',
                                    Url::toRoute(['transaction/delete', 'type' => $type,'id' => $model->id,'#' => $tab]));
                            },
                            'update' => function ($url,$model) use ($type,$tab) {
                                return Html::a('<span class="btn btn-success glyphicon glyphicon-edit"></span>',
                                    Url::toRoute(['transaction/update', 'type' => $type,'id' => $model->id,'#' => $tab]));
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
