<?php
namespace diginova\mhsb\controllers\web;

use diginova\mhsb\models\Items;
use Yii;
use DateTime;
use diginova\mhsb\Module;
use diginova\mhsb\models\Companies;
use diginova\mhsb\models\Definitions;
use diginova\mhsb\models\Documents;
use diginova\mhsb\models\Transactions;
use diginova\mhsb\models\TransactionsSearch;
use portalium\site\models\Setting;
use portalium\web\Controller as WebController;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


class ReportController extends WebController
{

    const DEF_TAXES = 'deferred_taxes';
    const DEF_TAX_CALC_YEAR = 'deferred_tax_calc_year';			

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),              
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['vat']);
    }

	public function actionVat()
    {
        $request = Yii::$app->request;
        $year = (is_null($request->post('year'))) ? Date('Y') : $request->post('year');
        $years = ArrayHelper::map(Documents::find()->select('YEAR(date) AS year')->orderBy(['date' => SORT_DESC])->distinct()->asArray()->all(),'year','year');
        unset($years['']);
        unset($years['0']);

        $dataTaxes = [];

        if(!$modelDeferredTaxes = Setting::findOne(['key' => self::DEF_TAXES]))
        {
            $modelDeferredTaxes = new Setting();
            $modelDeferredTaxes->key = self::DEF_TAXES;
            $modelDeferredTaxes->value = Json::encode([]);
            $modelDeferredTaxes->save();
        }

        $deferredTaxes = Json::decode($modelDeferredTaxes->value,true);
        $deferredTax = (!isset($deferredTaxes[$year - 1])) ? 0 : $deferredTaxes[$year - 1];

        for ($month = 1; $month <= 12; $month++)
        {
            $resultTax = Items::getTax($year, $month);
            $resultTax['tax_status_month'] = $resultTax['tax_status_month'] - $deferredTax;
            $deferredTax = $resultTax['tax_status_month'] >= 0 ? 0 : -1 * $resultTax['tax_status_month'];
            $resultTax['month'] = $month;
            $dataTaxes[$month] = $resultTax;
        }

        $deferredTaxes[$year] = $deferredTax;
        $modelDeferredTaxes->key = self::DEF_TAXES;
        $modelDeferredTaxes->value = Json::encode(array_intersect_key($deferredTaxes, $years));
        $modelDeferredTaxes->save();

        $data['year'] = $year;
        $data['years'] = $years;

        $data['provider'] = new ArrayDataProvider([
            'allModels' => $dataTaxes,
            'sort' => [
                'attributes' => ['month','tax_total_sales', 'tax_total_purchases', 'tax_status_month'],
            ],
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('_vat', $data);

    }

	public function actionExtract()
    {

	$firstDocument = Documents::find()->orderBy(['date' => SORT_ASC])->one();
	$dateDocument = new \DateTime($firstDocument->date);
	$startYear = (int) $dateDocument->format('Y');
	$currentYear = (int) date ('Y');
	$request = Yii::$app->request;
	$year = $request->post('year');	
	$datas = [];
	for($y=$startYear;$y<$currentYear;$y++){
             	for ($t = 1; $t <= 25; $t++) {
				$sql = "SELECT `d`.`date`,`d`.`no`,`def`.`name`,`i`.`type`,
						IFNULL(SUM( CASE WHEN  `d`.`type_group` = 'sales' THEN (`quantity` * `price` * `d`.`currency_value`) ELSE 0 END ), 0) AS `alacak` ,
						IFNULL(SUM( CASE WHEN  `d`.`type_group` = 'purchases' THEN (`quantity` * `price` * `d`.`currency_value`) ELSE 0 END ) , 0) AS  `ödeme` ,
						IFNULL(SUM( CASE WHEN  `d`.`type_group` = 'sales' THEN (`quantity` * `price` * `d`.`currency_value`) ELSE 0 END ), 0)-IFNULL(SUM( CASE WHEN  `d`.`type_group` = 'purchases' THEN (`quantity` * `price` * `d`.`currency_value`) ELSE 0 END ) , 0) as `bakiye`
						FROM `items` as `i`
						RIGHT JOIN `documents` as `d` ON `d`.`id` = `i`.`document_id` 
						RIGHT JOIN `definitions` `def` ON `d`.`type_id` = `def`.`id`
						WHERE `d`.`id`= $t;
						GROUP BY `d`.`date`,`d`.`no`,`def`.`name`,`i`.`type`
					
					"; 
			
			$results = yii::$app->db->createCommand($sql)->queryOne();
			$datas[$t] = $results;
			print_r ($results);
			
			
	}
	
        $data['providerExt'] = new ArrayDataProvider([                                          
            'allModels' => $datas[$t], 
            'sort' => [
                'attributes' => ['date', 'no' , 'name', 'company_id' , 'payment' , 'collecting' , 'status'], 
            ],
			'pagination' => [
                'pageSize' => 12,
            ],
        ]);
	
       return $this->render('manage', $datas[$t]);
	   
	 
	}	
}
}


