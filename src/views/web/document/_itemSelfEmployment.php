<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use diginova\mhsb\models\Items;


if(!is_array($modelItems))
    $modelItems = [0 => $modelItems];

foreach ($modelItems as $key => $value) {
    if ($value instanceof Items) {
        if ($value->isNewRecord)
            $i = $key;
        else
            $i = $value->id;
        ?>

        <tr class="row-item" data-id="<?= $value->id ?>" >
            <td style="text-align: center;vertical-align:middle">  <a class="delete-item"><span class="btn btn-danger glyphicon glyphicon-trash"></span></a></td>
            <td>  <?= $form->field($value,"[$i]name")->label(false)->input('string',['data-type' => 'name']); ?> </td>
            <td>  <?= $form->field($value,"[$i]def_id")->dropDownList(ArrayHelper::map($types,'id', 'name'))->label(false); ?> </td>
            <td>  <?= $form->field($value,"[$i]quantity")->label(false)->input('string',['data-type' => 'quantity']); ?> </td>
            <td>  <?= $form->field($value,"[$i]price")->label(false)->input('string',['data-type' => 'price']); ?> </td>
            <td>  <?= $form->field($value,"[$i]tax")->label(false)->input('string',['data-type' => 'tax']); ?> </td>
            <td>  <?= $form->field($value,"[$i]stoppage")->label(false)->input('string',['data-type' => 'stoppage']); ?> </td>
            <td>  <?= $form->field($value,"[$i]total")->label(false)->input('string',['data-type' => 'collected']); ?> </td>
        </tr

        <?= Html::button('delete'); ?>
        <?php
    }
}
?>

