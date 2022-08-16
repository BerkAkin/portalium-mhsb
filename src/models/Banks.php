<?php


namespace diginova\mhsb\models;

use diginova\mhsb\Module;
use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property int $number
 * @property string $currency_code
 * @property int $opening_balance
 * @property int $current_balance
 * @property string $bank_name
 * @property int $bank_phone
 * @property string $bank_address
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Companies $company
 */
class Banks extends \yii\db\ActiveRecord
{
    const STATUS_ENABLE = '1';
    const STATUS_DISABLE = '0';
    const TYPE_CUR = 'currency';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mhsb_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'name', 'number', 'currency_code', 'opening_balance', 'current_balance', 'bank_name', 'bank_phone', 'bank_address', 'status'], 'required'],
            [['id', 'company_id', 'number', 'opening_balance', 'current_balance', 'bank_phone', 'status', 'currency_code'], 'integer'],
            [['bank_address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_ENABLE, self::STATUS_DISABLE]],
            [['name', 'bank_name'], 'string', 'max' => 64],
            [['currency_code'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['currency_code'], 'exist', 'skipOnError' => true, 'targetClass' => Definitions::className(), 'targetAttribute' => ['currency_code' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company',
            'name' => 'Name',
            'number' => 'Number',
            'currency_code' => 'Currency Code',
            'opening_balance' => 'Opening Balance',
            'current_balance' => 'Current Balance',
            'bank_name' => 'Bank',
            'bank_phone' => 'Phone',
            'bank_address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Definitions::className(), ['id' => 'currency_code']);
    }

    

    public function statusLabels($group = false)
    {
        $labels = [
            self::STATUS_ENABLE => Module::t('Enable'),
            self::STATUS_DISABLE => Module::t('Disable'),
        ];

        if ($group) {
            return isset($labels[$group]) ? $labels[$group] : $group;
        } else
            return $labels;
    }

    public static function getStatuses($group = false, $keys = false)
    {
        $groups = [
            self::STATUS_ENABLE => self::statusLabels(self::STATUS_ENABLE),
            self::STATUS_DISABLE => self::statusLabels(self::STATUS_DISABLE)
        ];

        if ($group)
            $groups = isset($groups[$group]) ? $groups[$group] : [];

        if ($keys)
            $groups = ($group) ? array_keys($groups) : $groups;

        return $groups;
    }
}


