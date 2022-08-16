<?php

namespace diginova\mhsb\models;

use diginova\mhsb\Module;
use DateTime;
use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property int $document_id
 * @property int $type
 * @property int $quantity
 * @property string $price
 * @property int $tax
 * @property int $is_included_tax
 * @property int $total
 * @property Definitions $type0
 * @property Documents $document
 */
class Items extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const IS_INCLUDED_TAX_ACTIVE = '1';
    const IS_INCLUDED_TAX_PASSIVE = '0';

    public static function tableName()
    {
        return 'mhsb_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','document_id', 'def_id'], 'required'],
            [['document_id', 'def_id', 'quantity', 'tax', 'is_included_tax', 'total', 'net_wage', 'stoppage', 'collected'], 'integer'],
            [['price'], 'number'],
            ['is_included_tax', 'in', 'range' => [self::IS_INCLUDED_TAX_ACTIVE,self::IS_INCLUDED_TAX_PASSIVE]],
            [['name'], 'string'],
            [['def_id'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['def_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documents::className(), 'targetAttribute' => ['document_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'name' => Module::t('Name'),
            'document_id' => Module::t('Document'),
            'def_id' => Module::t('Type'),
            'quantity' => Module::t('Quantity'),
            'price' => Module::t('Price'),
            'tax' => Module::t('Tax'),
            'total' =>Module::t('Total'),
            'is_included_tax' => Module::t('Is included tax?'),
        ];
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
    public function getDocument()
    {
        return $this->hasOne(Documents::className(), ['id' => 'document_id']);
    }

    public function statusLabels()
    {
        return [
            self::IS_INCLUDED_TAX_ACTIVE => Module::t('Active'),
            self::IS_INCLUDED_TAX_PASSIVE => Module::t('Passive'),
        ];
    }

    public static function getStatuses()
    {
        return [
            self::IS_INCLUDED_TAX_ACTIVE => self::statusLabels(self::IS_INCLUDED_TAX_ACTIVE),
            self::IS_INCLUDED_TAX_PASSIVE => self::statusLabels(self::IS_INCLUDED_TAX_PASSIVE)
        ];
    }
    public static function getTax($year,$month)
    {
        $tax_sales = self::getTotal(Definitions::TYPE_REV,$year,$month);
        $tax_purchases = self::getTotal(Definitions::TYPE_EXP,$year,$month);

        $resultTax['tax_total_sales'] = $tax_sales['total'];
        $resultTax['tax_total_purchases'] = $tax_purchases['total'];
        $resultTax['tax_status_month'] = ($tax_sales['total'] - $tax_purchases['total']);

        return $resultTax;
    }

    private function getTotal($type,$year,$month)
    {
        return self::find()
            ->select(["document_id", "items.def_id", "SUM(quantity * price * tax * rate / 100) AS total"])
            ->joinWith('document')
            ->joinWith('type')
            ->where(['=', 'YEAR(documents.date)', $year])
            ->andWhere(['=', 'MONTH(documents.date)', $month])
            ->andWhere(['=', 'definition.type', $type])
            ->asArray()
            ->one();
    }
}
