<?php
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Banks;

?>
<div class="form-body">
    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $modelScale,
                'id' => 'documentsGrid',
                'columns' => [
                    'no',
                    [
                        'label' => Module::t('Type'),
                        'attribute' => 'type.name',
                    ],
                    [
                        'label' => Module::t('Company'),
                        'attribute' => 'company.name',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'header' => Module::t('Actions'),
                        'headerOptions' => ['class' => 'col-md-2'],
                        'buttons' => [
                            'delete' => function ($url, $model) use($type) {
                                return Html::a('<span class="btn btn-danger glyphicon glyphicon-trash"></span>',
                                    Url::toRoute(['document/delete','type' => $type, 'id' => $model->id]));
                            },
                            'update' => function ($url, $model) use($type) {
                                return Html::a('<span class="btn btn-success glyphicon glyphicon-edit"></span>',
                                    Url::toRoute(['document/manage', 'type' => $model->type['type'],'action' => 'create','id' => $model->id]));
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

