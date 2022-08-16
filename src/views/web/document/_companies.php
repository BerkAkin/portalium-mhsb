<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use portalium\theme\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use portalium\site\Module;
use portalium\theme\widgets\GridView;
use yii\bootstrap\Modal;
use diginova\mhsb\models\Definitions;

Pjax::begin(['id'=> 'pjaxFormCompanies', 'enablePushState' => false]);
$form = ActiveForm::begin(['action' => Url::to(["company"]),'method' => "POST", 'validationUrl'=>Url::to(["company-validate"]), 'id' => 'form-companies', 'enableAjaxValidation' => true, 'enableClientValidation'=> false, 'options' =>["data-pjax"=>true]]);
Modal::begin([
    'closeButton' => ['label' => 'x'],
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modalCompanies',
    'toggleButton' => false
]); ?>
    <div class="form-actions">
        <div class="form-group row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'name');?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'domain'); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'email'); ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'def_id')->dropDownList(ArrayHelper::map($types,'id','name')); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'group')->dropDownList($typesGroup); ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'tax_id_number'); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'tax_department'); ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'tax_country'); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="form-group col-md-6">
                <?= $form->field($model, 'address'); ?>
            </div>
            <div class="form-group col-md-6">
                <?= $form->field($model, 'status')->dropDownList($model->statusLabels()); ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <?= Html::submitButton( Module::t('Add'), ['class' => 'btn btn-success', 'id'=> "addCompany"]); ?>
            </div>
        </div>
        <?php Modal::end(); ?>
        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
    </div>
<?php
$js = <<<JS
$(document).on('click', '#modalButton', function () { 
	$('#modalCompanies').modal('toggle');
});
$(document).on('click', '#addCompany', function () {
	$('#modalCompanies').modal('toggle');
 });
JS;

$this->registerJs($js, View::POS_END);
