<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use portalium\site\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\Banks;


$form = ActiveForm::begin(['action' => Url::to($url), 'id' => 'form-section', 'class' => 'horizontal-form', 'options' => ['enctype' => 'multipart/form-data'], 'layout' => 'horizontal']);
?>
<div class="form-body">
    <div class="row">
        <div class=" col-lg-6">
    <?= $form->field($model, 'name'); ?>
        </div>
        <div class="col-lg-6">
    <?= $form->field($model, 'number'); ?>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-6">
            <?= $form->field($modelCompany, 'name')->label('Company Name') ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'currency_code')->dropDownList(ArrayHelper::map($typesCurr,'id','name'));?>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-6">
    <?= $form->field($model, 'opening_balance'); ?>
        </div>
        <div class="col-lg-6">
    <?= $form->field($model, 'current_balance'); ?>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-6">
    <?= $form->field($model, 'bank_name'); ?>
        </div>
        <div class="col-lg-6">
    <?= $form->field($model, 'bank_phone'); ?>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-6">
    <?= $form->field($model, 'bank_address'); ?>
        </div>
        <div class="col-lg-6">
    <?= $form->field($model, 'status')->dropDownList($model->statusLabels()); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-10 col-md-5">
    <div class="row">
        <div class="col-md-offset-30 col-md-30">
            <?= Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
       
        </div>
    </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


