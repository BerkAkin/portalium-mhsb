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
                        <th width="20%"> <?= Module::t('Definition') ?> </th>
                        <th width="16%"> <?= Module::t('Quantity') ?> </th>
                        <th width="16%"> <?= Module::t('Price') ?> </th>
                        <th width="16%"> <?= Module::t('Income Tax(%)') ?> </th>
                        <th width="16%"> <?= Module::t('Tax(%)') ?> </th>
                        <th width="16%"> <?= Module::t('Total') ?> </th>

                    </tr>
                    </thead>
                    <tbody>
                    <?= $this->render('_item-Expense-Slip',['form' => $form,'modelItems' => $modelItems,'types' => $itemTypes]); ?>
                    <tr class="" style="text-align: center;vertical-align:middle">
                        <td>  <a class="add-item"><span class="btn btn-success glyphicon glyphicon-plus"></span></a> </td>
                        <td colspan="6"></td>
                    </tr>
              
                   

                    <tr class="document-total" style="text-align: right">
                        <td colspan="6"> <?= Module::t('Total') ?> </td>
                        <td class="value" ></td>

                        <tr class="document-income" style="text-align: right">
                        <td colspan="6"> <?= Module::t('Income Tax(%)') ?> </td>
                        <td class="value" ></td>
                    </tr>

                        <tr class="document-tax" style="text-align: right">
                        <td colspan="6"> <?= Module::t('Tax(%)') ?> </td>
                        <td class="value" ></td>
                    </tr>

                        <tr class="document-cut" style="text-align: right">
                        <td colspan="6"> <?= Module::t('Total Cut') ?> </td>
                        <td class="value" ></td>

                        <tr class="document-net" style="text-align: right">
                        <td colspan="6"> <?= Module::t('Net Amount To Pay') ?> </td>
                        <td class="value" ></td>
                    </tr>

                  
                    </tbody>



                    
                </table>
            </div>
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
    #BottomDetails{
       margin-left:69%;

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
    /*
    var calculate= function (rowItem){
        var quantity = rowItem.find('input[data-type="quantity"]'),
            price = rowItem.find('input[data-type="price"]'),
            total_d = rowItem.find('input[data-type="total_d"]')
        
        let cal: number[] = [0,0];
        let calResult: number[] = [0,0,0];
        this.items.forEach(function (value:Item) {
        console.log(value.is_included_tax);
        if(!value.is_included_tax) {
            cal[0] += total_d.price*total_d.quantity;
            cal[1] += cal[0]*(total_d.tax/100);
        }  else {
            cal[0] += (100*total_d.quantity*total_d.price)/ (+total_d.tax+100);
            cal[1] += (total_d.price*total_d.quantity) - cal[0];
        }
        calResult[0] += cal[0];
        calResult[1] += cal[1];
        cal[0] = 0;cal[1] = 0;
        });
        calResult[2] = calResult[0] + calResult[1];
        return calResult;
  }
            
      
    */


    
    var calcDocumentItemChanged = function(rowItem){
        var itemTotal = rowItem.find('.item-total'),
            itemQuantity = rowItem.find('input[data-type="quantity"]'),
            itemIncomeTax = rowItem.find('input[data-type="income_tax"]'),
            itemPrice = rowItem.find('input[data-type="price"]'),
            itemTotal = rowItem.find('input[data-type="total"]'),//Deneme Deneme Deneme Deneme
            
            parentTable = rowItem.closest('table'),
            documentSubTotal = parentTable.find('.document-subtotal .value'),
            documentTax = parentTable.find('.document-tax .value'),
            documentTotal = parentTable.find('.document-total .value'),
            documentTotal = parentTable.find('.document-total .value');
            documentNet = parentTable.find('.document-net .value');

           
        
            itemTotal.html(Number(itemQuantity.val())*Number(itemPrice.val()));
            
            
            var itemTotal_dValue = Number(itemQuantity.val())*Number(itemPrice.val());
                
            itemTotal_d.html(itemTotal_dValue);
             
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
    //<th width="27%"> <?= Module::t('Total') ?> </th> /  Deneme Deneme Deneme Deneme
    /*
    var calcAuto = function (rowItem){
        var itemTotal = rowItem.find('.item-total'),
            itemQuantity = rowItem.find('input[data-type="quantity"]'),
            itemPrice = rowItem.find('input[data-type="price"]'),
            documentTax = parentTable.find('.document-tax .value'),
            itemTotal_d = rowItem.find('input[data-type="total_d"]')
            
            
            
            if (itemQuantity!==null && itemPrice!==null && itemTotal===null){
                itemTotal=itemQuantity*(itemPrice+(itemPrice*documentTax/100));
            }else if(itemTotal!==null && itemPrice!==null && itemQuantity===null){
                itemQuantity=itemTotal/itemPrice;
                
            }else if(itemTotal!==null && itemQuantity!==null && itemPrice===null){
                itemPrice=itemTotal/itemQuantity;
            }
                
            
            
    };
    */
    var calcDocumentInit = function(){
        var tables = $('.document-table');
        
        tables.each(function(i,table) {
            table = $(table);
            var tableSubTotal = table.find('.document-subtotal .value'),
                tableTax = table.find('.document-tax .value'),

                tableTotal = table.find('.document-total .value'),
                subTotalValue = 0,
                totalTax = 0;
            
            

                tableTaxes = table.find('.document-taxes .value'),
                tableTotal = table.find('.document-total .value'),
                tableNet = table.find('.document-net .value'),
                tableCut = table.find('.document-cut .value'),
                tableIncome = table.find('.document-income .value'),
                subTotalValue = 0,
                totalTax = 0;

                tableTotal = table.find('.document-total .value'),
                subTotalValue = 0,
                totalTax = 0;


            var tableItems = table.find('.row-item');
            
            tableItems.each(function(ii,item) {
                item = $(item);
                var itemTotal = item.find('.item-total'),
                itemQuantity = item.find('input[data-type="quantity"]'),
                itemTotal = item.find('input[data-type="total"]'),
                itemPrice = item.find('input[data-type="price"]'),
                itemTax = item.find('input[data-type="tax"]');
                itemIncomeTax = item.find('input[data-type="income_tax"]');
                var itemTotalValue;
                var itemQuantityValue;
                var itemPriceValue;
                var total_kdv;
                var total_cut;
                
                
                if (itemQuantity.val()!=="" && itemPrice.val()!==""){
                    itemTotalValue = Number(itemQuantity.val()) * Number(itemPrice.val());
                    totalTax+= (itemTotalValue * Number(itemTax.val()) / 100);


                    



                    IncomeTax = (itemTotalValue * Number(itemIncomeTax.val()) / 100);
                    total_kdv=itemTotalValue+totalTax;
                    itemTotal.val(total_kdv);


                }else if(itemTotal.val()!=="" && itemPrice.val()!==""){
                    itemQuantityValue =(Number(itemTotal.val())*100)/((itemPrice.val()*itemTax.val())+(100*itemPrice.val()));
                    itemQuantity.val(itemQuantityValue);
                }else if(itemTotal.val()!=="" && itemQuantity.val()!==""){
                    itemPriceValue =(Number(itemTotal.val())*100)/((itemQuantity.val()*itemTax.val())+(100*itemQuantity.val()));
                    itemPrice.val(itemPriceValue);
                }
                total_cut=totalTax + IncomeTax;

                tableSubTotal.html(Number(itemQuantity.val()) * Number(itemPrice.val()));
                tableTax.html(totalTax.toFixed(2));
                tableTotal.html(itemTotalValue);  //Eger total vergi ile birlikte gosterilecek ise buraya eklenmelidir
               
                tableIncome.html(IncomeTax);
                 tableCut.html(total_cut); // + Gelir Vergisi eklenecektir
                 tableNet.html(itemTotalValue + total_cut);
                
            });
            
            
            
            
        });
    }
    
    $(document).on('change',['input[data-type="quantity"]'],function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="tax"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="income_tax"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });
    $(document).on('change','input[data-type="price"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });//Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme 
    $(document).on('change','input[data-type="total"]',function() {
      calcDocumentItemChanged($(this).closest('.row-item'));
    });//Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme Deneme 
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
