<?php

use diginova\mhsb\models\Definitions;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use diginova\mhsb\Module;
use portalium\theme\widgets\GridView;
use yii\widgets\ajax;
use app\models\Companies;
use yii\web\JsExpression;
use diginova\mhsb\models\Documents;


$form = ActiveForm::begin(['action' => Url::to($url), 'id' => 'form-document', 'options' => ['enctype' => 'multipart/form-data']]);
?>

<div class="modal-show">
    <div class="row">
        <div class="col-md-offset-1 col-md-5">
            <?= $form->field($model, 'no'); ?>
        </div>
        <div class="col-md-5">
            <?php $data = ArrayHelper::map($companies, 'id', 'name'); ?>
            <?= 
            $form->field($model, "company_id")->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => 'Search for a company ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'data' => new JsExpression('function(params) { return {name:params.term}; }')
                ],
            ]);
            ?>

        </div>

            <div class="col-md">
            <?= Html::button(Yii::t('app', ''), ['value' => Url::to(['modal/show']),
                'title' => Yii::t('app', 'Showing modal for {model_name}', [
                    'model_name' => 'modal model']), 'header' => Yii::t('app', 'Modal Model'), 'class' => 'btn btn-info glyphicon glyphicon-plus', 'style'=> "margin-top:23px;" ,'id' => 'modalButton'
               ]);
            ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-5">
            <?= $form->field($model, 'currency_id')->dropDownList(ArrayHelper::map($currencies,'id' ,'name')); ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'rate'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-5">
            <?= $form->field($model, 'def_id')->dropDownList(ArrayHelper::map($types,'id' ,'name')); ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'date'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="table-responsive document-table">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th width="5%" style="text-align: center;vertical-align:middle"> # </th>
                        <th width="20%"> <?= Module::t('Product Name') ?> </th>
                        <th width="20%"> <?= Module::t('Type') ?> </th>
                        <th width="10%"> <?= Module::t('Quantity') ?> </th>
                        <th width="10%"> <?= Module::t('Price') ?> </th>
                        <th width="12%"> <?= Module::t('Tax(%)') ?> </th>
                        <th width="12%"> <?= Module::t('Stoppage(%)') ?> </th>
                        <th width="10%"> <?= Module::t('Collacted') ?> </th>

                    </tr>
                    </thead>
                    <tbody>
                    <?= $this->render('_itemSelfEmployment',['form' => $form,'modelItems' => $modelItems,'types' => $itemTypes]); ?>
                    <tr class="" style="text-align: center;vertical-align:middle">
                        <td>  <a class="add-item"><span class="btn btn-success glyphicon glyphicon-plus"></span></a> </td>
                        <td colspan="7"></td>
                    </tr>
                    <tr class="document-before-tax" style="text-align: right">
                        <td colspan="7"> <?= Module::t('Before-tax') ?> </td>
                        <td class="value" ></td>
                    </tr>
                    <tr class="document-stoppage" style="text-align: right">
                        <td colspan="7"> <?= Module::t('Stoppage(₺)') ?> </td>
                        <td class="value" ></td>
                    </tr>

                    <tr class="document-net-wage" style="text-align: right">
                        <td colspan="7"> <?= Module::t('Net-wage') ?> </td>
                        <td class="value" ></td>
                    </tr>

                    <tr class="document-tax" style="text-align: right">
                        <td colspan="7"> <?= Module::t('Tax(₺)') ?> </td>
                        <td class="value" ></td>
                    </tr>

                    <tr class="document-collacted" style="text-align: right">
                        <td colspan="7"> <?= Module::t('Collacted') ?> </td>
                        <td class="value" ></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <?= $form->field($model,'description')->textarea(); ?>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-1 col-md-9">
            <?= Html::submitButton($model->isNewRecord ? Module::t('Add') : Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>
</div>

</div>

<?php ActiveForm::end(); ?>
<?= $this->render('_companies', ['model' => $companiesModel, 'types' => $types,'typesGroup'=>$typesGroup]); ?>
<?php

$css = <<<CSS
    .row-item .form-group{
        margin-bottom: 0;
    }
CSS;

$js = <<<JS
    $(document).ready(function() {
      calcDocumentInit();
    });

    $(document).on('click','.add-item',function(e) {
        e.preventDefault();
        var parentTable = $(this).closest('table');
        var parentRow = $(this).closest('tr');
        var lastRow = parentTable.find('.row-item:last');
        var clonedRow = lastRow.clone();
        var lastID = lastRow.attr('data-id');
        var newID = parseInt(lastID)+1;
        var inputs = clonedRow.find('input');
        var ddlist = clonedRow.find('select');
        clonedRow.attr('data-id',newID);
        parentRow.before(clonedRow);
            
        ddlist.attr('name', ddlist.attr('name').replace(lastID,newID));
        ddlist.attr('id', ddlist.attr('id').replace(lastID,newID));
        ddlist.closest('.form-group').attr('class',ddlist.closest('.form-group').attr('class').replace(lastID,newID));
        
        $(inputs).each(function(index,item) {
            var item = $(item);
            item.val('');
            item.attr('name', item.attr('name').replace(lastID,newID));
            item.attr('id', item.attr('id').replace(lastID,newID));
            item.closest('.form-group').attr('class',item.closest('.form-group').attr('class').replace(lastID,newID));
        });
        
    });
    
    
    var calcDocumentItemChanged = function(rowItem){
        var itemTotal = rowItem.find('.item-total'),
            itemQuantity = rowItem.find('input[data-type="quantity"]'),
            itemPrice = rowItem.find('input[data-type="price"]'),
            parentTable = rowItem.closest('table'),
            documentSubTotal = parentTable.find('.document-subtotal .value'),
            documentTax = parentTable.find('.document-tax .value'),
            documentTotal = parentTable.find('.document-total .value');
        
            itemTotal.html(Number(itemQuantity.val())*Number(itemPrice.val()));
            
            
             
            var itemsTotal = parentTable.find('.item-total');
            var subTotalValue = 0;
            var totalTax = 0;
            itemsTotal.each(function(i,item) {
                item = $(item)
                
              
                var itemTotal = Number(item.html());
                var itemTax = item.closest('.row-item').find('input[data-type="tax"]');
                totalTax+= itemTotal * Number(itemTax.val()) / 100;
                subTotalValue+= itemTotal;
            });
            
            
            documentSubTotal.html(subTotalValue);
            documentTax.html(totalTax.toFixed(2));
            documentTotal.html(subTotalValue+totalTax);
            
            
    }
    
    var calcDocumentInit = function(){
        var tables = $('.document-table');
        
        tables.each(function(i,table) {
            table = $(table);
            var tableBeforeTax = table.find('.document-before-tax .value'),
                tableTax = table.find('.document-tax .value'),
                tableCollacted = table.find('.document-collacted .value'),
                tableNetWage = table.find('.document-net-wage .value'),
                tableStoppage = table.find('.document-stoppage .value'),
                subTotalValue = 0,
                totalTax = 0;
                itemStoppageValue=0;
            
            

                tableTaxes = table.find('.document-taxes .value'),
                tableTotal = table.find('.document-total .value'),
                subTotalValue = 0,
                totalTax = 0;

                tableTotal = table.find('.document-total .value'),
                subTotalValue = 0,
                totalTax = 0;


            var tableItems = table.find('.row-item');
            
            tableItems.each(function(ii,item) {
                item = $(item);
                var itemQuantity = item.find('input[data-type="quantity"]'),
                itemCollected = item.find('input[data-type="collected"]'),
                itemPrice = item.find('input[data-type="price"]'),
                itemTax = item.find('input[data-type="tax"]');
                itemStoppage = item.find('input[data-type="stoppage"]');
                var itemCollectedValue;
                var itemNetWageValue;
                var itemQuantityValue;
                var itemPriceValue;
                var itemBeforeTaxValue;
                
                
                
                if (itemQuantity.val()!=="" && itemPrice.val()!==""){
                    itemBeforeTaxValue = Number(itemQuantity.val()) * Number(itemPrice.val());
                    itemStoppageValue = (itemBeforeTaxValue * Number(itemStoppage.val()))/100;
                    itemNetWageValue = itemBeforeTaxValue - itemStoppageValue; 
                    totalTax+= itemBeforeTaxValue * Number(itemTax.val()) / 100;
                    itemCollectedValue = itemNetWageValue + totalTax;
                    itemCollected.val(itemCollectedValue);
                }else if(itemCollected.val()!=="" && itemPrice.val()!==""){
                    itemQuantityValue =(Number(itemCollected.val())*100)/(Number(itemPrice.val())*(100-Number(itemStoppage.val())+Number(itemTax.val())));
                    itemQuantity.val(itemQuantityValue);
                }else if(itemCollected.val()!=="" && itemQuantity.val()!==""){
                    itemPriceValue =(Number(itemCollected.val())*100)/(Number(itemQuantity.val())*(100-Number(itemStoppage.val())+Number(itemTax.val())));
                    itemPrice.val(itemPriceValue);
                }
                tableBeforeTax.html(Number(itemQuantity.val()) * Number(itemPrice.val()));
                tableStoppage.html(itemStoppageValue);
                tableNetWage.html(itemNetWageValue);
                tableTax.html(totalTax.toFixed(2));
                tableCollacted.html(itemCollectedValue);
                
            });
            
            
            
            
        });
    }
    
    $(document).on('change',['input[data-type="quantity"]'],function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="tax"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="price"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="total"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    }); 
    $(document).on('click',['.delete-item'],function() {
        console.log("inside");
      calcDocumentInit($(this).closest('.row-item'));
    });
    /*$('input[data-type="quantity"]').on('change',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $('input[data-type="tax"]').on('change',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $('input[data-type="price"]').on('change',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });*/

    $(document).on('click','.delete-item',function(e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });
     
     //  $(document).on("beforeSubmit", "#companiesForm", function () {
     // send data to actionSave by ajax request.
     // return false; // Cancel form submitting.
//});
    /* $('#modalButton').click(function () {
         $('#modalCompanies').modal('show');
         $('#modalCompanies').on('shown.bs.modal', function () { 
             $('#modalCompanies').modal('hide');
         });
     });*/
JS;

$this->registerCss($css, [], View::POS_HEAD);
$this->registerJs($js, View::POS_END);
