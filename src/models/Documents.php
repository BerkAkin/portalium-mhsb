<?php

namespace diginova\mhsb\models;

use diginova\mhsb\Module;
use Yii;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "documents".
 *
 * @property int $
 * @property int $user_id
 * @property int $company_id
 * @property int $category_id
 * @property string $no
 * @property string $type
 * @property int $currency_id
 * @property double $rate
 * @property string $description
 * @property int $payment
 * @property string $date
 *
 * @property Categories $category
 * @property Companies $company
 * @property Definitions $currency
 * @property Items[] $items
 */
class Documents extends \yii\db\ActiveRecord
{

   
    
    /*public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mhsb_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'no', 'def_id', 'user_id'], 'required'],
            [['company_id', 'category_id', 'def_id', 'currency_id', 'payment', 'user_id'], 'integer'],
            [['rate'], 'number'],
            [['date'], 'safe'],
            [['no'], 'string', 'max' => 32],
            [['description'], 'string'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['def_id'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['def_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'user_id' => Module::t('UserID'),
            'company_id' => Module::t('Company'),
            'category_id' => Module::t('Category'),
            'no' => Module::t('No'),
            'def_id' => Module::t('Type'),
            'currency_id' => Module::t('Currency'),
            'rate' => Module::t('Exchange Rate'),
            'description' => Module::t('Description'),
            'payment' => Module::t('Payment'),
            'date' => Module::t('Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Definitions::className(), ['id' => 'def_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Definitions::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['document_id' => 'id']);
    }

    public static function getTax($year, $month)
    {
        $sql = "SELECT `tax_total_sales`,`tax_total_purchases`,(`tax_total_sales` - `tax_total_purchases`) as `tax_status_month`
                FROM (SELECT (SELECT IFNULL(SUM( `quantity` * `price` * `tax` * `d`.`rate` / 100 ), 0) AS `tax_total_sales` FROM `item` as `i`
                RIGHT JOIN `document` as `d` ON `d`.`id` = `i`.`document_id` WHERE MONTH(`date`) = $month AND YEAR(`date`) = $year
                AND `d`.`type` = 'sale') as `tax_total_sales`,(SELECT IFNULL(SUM( `quantity` * `price` * `tax` * `d`.`rate` / 100 ), 0) AS `tax_total_purchases` FROM `item` as `i`
                RIGHT JOIN `document` as `d` ON `d`.`id` = `i`.`document_id` WHERE MONTH(`date`) = $month AND YEAR(`date`) = $year
                AND `d`.`type` = 'purchase') as `tax_total_purchases`) as `table`";

        return yii::$app->db->createCommand($sql)->queryOne();
    }
}
