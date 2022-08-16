<?php

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use diginova\mhsb\models\Definitions;

$form = ActiveForm::begin(['action' => Url::to($url), 'id' => 'form-section', 'class' => 'horizontal-form', 'options' => ['enctype' => 'multipart/form-data'], 'layout' => 'horizontal']);
?>
    <div class="form-actions">
        <?= ($form->field($model, 'name')) ? $form->field($model, 'name') : '' ?>
        <?= ($form->field($model, 'type')->dropDownList($types)) ?  $form->field($model, 'type')->dropDownList($types) : '' ?>
        <?= ($form->field($model, 'code')) ? $form->field($model, 'code') : '' ?>
        <?= ($form->field($model, 'parent')) ? $form->field($model, 'parent') : '' ?>

        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= (Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])) ? Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) : '' ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>