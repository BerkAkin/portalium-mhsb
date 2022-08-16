<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use portalium\site\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Definitions;

$form = ActiveForm::begin(['action' => Url::to($url), 'id' => 'form-section', 'class' => 'horizontal-form', 'options' => ['enctype' => 'multipart/form-data'], 'layout' => 'horizontal']);
?>

<div class="form-actions">
    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'domain'); ?>
    <?= $form->field($model, 'email'); ?>
    <?= $form->field($model, 'def_id')->dropDownList(ArrayHelper::map($types,'id','name')); ?>
    <?= $form->field($model, 'group')->dropDownList($model->groupLabels()); ?>
    <?= $form->field($model, 'tax_id_number'); ?>
    <?= $form->field($model, 'tax_department'); ?>
    <?= $form->field($model, 'tax_country'); ?>
    <?= $form->field($model, 'address'); ?>
    <?= $form->field($model, 'status')->dropDownList($model->statusLabels()); ?>
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

