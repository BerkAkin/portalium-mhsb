<?php

namespace diginova\mhsb\models;

use diginova\mhsb\Module;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $company_id
 * @property int $type_id
 * @property string $amount
 * @property int $currency_id
 * @property double $rate
 * @property string $date
 * @property string $created_at
 *
 * @property Companies $company
 * @property Definitions $currency
 * @property Definitions $type
 */
class Transactions extends \yii\db\ActiveRecord
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
        return 'mhsb_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'type_id',  'amount', 'currency_id'], 'required'],
            [['company_id', 'type_id', 'currency_id'], 'integer'],
            [['amount', 'rate'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'company_id' => Module::t('Company'),
            'type_id' => Module::t('Type'),
            'amount' => Module::t('Amount'),
            'currency_id' => Module::t('Currency'),
            'rate' => Module::t('Exchange Rate'),
            'date' => Module::t('Date'),
            'created_at' => Module::t('Created At'),
        ];
    }

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
    public function getCurrency()
    {
        return $this->hasOne(Definitions::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Definitions::className(), ['id' => 'type_id']);
    }
}
