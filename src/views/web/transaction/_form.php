<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;

$form = ActiveForm::begin(['action' => Url::to($url), 'id' => 'form-section', 'class' => 'horizontal-form', 'options' => ['enctype' => 'multipart/form-data'], 'layout' => 'horizontal']);
?>

<div class="form-actions">

    <?= $form->field($model, 'company_id')->dropDownList(ArrayHelper::map($companies,'id' ,'name')); ?>
    <?= $form->field($model, 'type_id')->dropDownList(ArrayHelper::map($types,'id' ,'name')); ?>
    <?= $form->field($model,'currency_id')->dropDownList(ArrayHelper::map($currencies,'id','name')); ?>
    <?= $form->field($model,'rate'); ?>
    <?= $form->field($model,'amount'); ?>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
