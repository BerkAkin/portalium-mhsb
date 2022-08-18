<?php
/**
 * Created by PhpStorm.
 * User: ibrahim
 * Date: 10/7/2018
 * Time: 16:57
 */
 

 
 
echo '<input class ="form-control" type="number" min="1900" max="2099" step="1" value="2016" />';


   $data['gridView'] = \yii\grid\GridView::widget([
    
	'dataProvider' => $provider,       
    'columns' => [                     
        [
           'attribute' => 'date',     
            'label' => 'Tarih'
        ],
		[
            'attribute' => 'no',
            'label' => 'Evrak No/İçerik'
        ],
		[
            'attribute' => 'name',
            'label' => 'Evrak Türü'
        ],
		[
            'attribute' => 'company_id',
            'label' => 'Firma Unvanı'
        ],
		[
            'attribute' => 'payment',
            'label' => 'Borç'
        ],
        [
            'attribute' => 'collecting',
            'label' => 'Alacak'
        ],
        [
            'attribute' => 'status',
            'label' => 'Bakiye'
        ],
    ],
   'layout' => '{items}',
]);

echo $this->render('extract',$data);
