<?php

namespace diginova\mhsb\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use diginova\mhsb\Module;

/**
 * This is the model class for table "definitions".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 */
class Definitions extends \yii\db\ActiveRecord
{
    const GROUP_PAYCOL = 'paycol';
    const GROUP_EXPREV = 'exprev';
    const GROUP_DOC = 'document';
    const GROUP_CUR = 'currency';
    const GROUP_COM = 'company';
    const GROUP_CARD = 'cards';
    const GROUP_LAB = 'label';



    const TYPE_PAY = 'payment';
    const TYPE_COL = 'collecting';
    const TYPE_PAYCOL = 'paycol';
    const TYPE_CUR = 'currency';
    const TYPE_COM = 'company';
    const TYPE_EXP = 'expense';
    const TYPE_REV = 'revenue';
    const TYPE_STK = 'stock';
    const TYPE_SER = 'service';
    const TYPE_SAL = 'sale';
    const TYPE_PUR = 'purchase';
    const TYPE_EXPS = 'expenseslip';
    const TYPE_EXPEN = 'expensecompass';
    const TYPE_EMP = 'selfemployment';
    const TYPE_CARD = 'card';
    const TYPE_LAB = 'label';
 

    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mhsb_definition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 32],
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
            'code' => Module::t('Code'),
            'type' => Module::t('Type'),
        ];
    }


    public function typeLabels($type = false)
    {
        $labels = [
            self::TYPE_PAY => Module::t('Payment'),
            self::TYPE_COL => Module::t('Collecting'),
            self::TYPE_PAYCOL => Module::t('Payment & Collecting'),
            self::TYPE_CUR => Module::t('Currency'),
            self::TYPE_COM => Module::t('Company'),
            self::TYPE_EXP => Module::t('Expense'),
            self::TYPE_REV => Module::t('Revenue'),
            self::TYPE_STK => Module::t('Stock'),
            self::TYPE_SER => Module::t('Service'),
            self::TYPE_SAL => Module::t('sale'),
            self::TYPE_PUR => Module::t('purchase'),
            self::TYPE_EXPS => Module::t('expsenseslip'),
            self::TYPE_EXPEN => Module::t('Expense Compass'),
            self::TYPE_EMP => Module::t('selfemployment'),
            self::TYPE_CARD => Module::t('Card'),
            self::TYPE_LAB => Module::t('Label'),


        ];

        if ($type) {
            return isset($labels[$type]) ? $labels[$type] : $type;
        } else
            return $labels;
    }

    public function groupLabels($group = false)
    {
        $labels = [
            self::GROUP_COM => Module::t('Company Type'),
            self::GROUP_CUR => Module::t('Currency'),
            self::GROUP_DOC => Module::t('Document'),
            self::GROUP_EXPREV => Module::t('Expense / Revenue'),
            self::GROUP_PAYCOL => Module::t('Payment / Collecting'),
            self::GROUP_CARD => Module::t('Card'),
            self::GROUP_LAB => Module::t('Label'),
            self::GROUP_CARD => Module::t('Card')

        ];

        if ($group) {
            return isset($labels[$group]) ? $labels[$group] : $group;
        } else
            return $labels;
    }


    public static function getGroupTypes($group = false, $keys = false)
    {
        $groups = [
            self::GROUP_PAYCOL => [
                self::TYPE_PAY => self::typeLabels(self::TYPE_PAY),
                self::TYPE_COL => self::typeLabels(self::TYPE_COL),
                self::TYPE_PAYCOL => self::typeLabels(self::TYPE_PAYCOL)
            ],
            self::GROUP_COM => [
                self::TYPE_COM => self::typeLabels(self::TYPE_COM)
            ],
            self::GROUP_CUR => [
                self::TYPE_CUR => self::typeLabels(self::TYPE_CUR)
            ],
            self::GROUP_EXPREV => [
                self::TYPE_EXP => self::typeLabels(self::TYPE_EXP),
                self::TYPE_REV => self::typeLabels(self::TYPE_REV),
                self::TYPE_STK => self::typeLabels(self::TYPE_STK),
                self::TYPE_SER => self::typeLabels(self::TYPE_SER)
            ],
            self::GROUP_DOC => [
                self::TYPE_SAL => self::typeLabels(self::TYPE_SAL),
                self::TYPE_PUR => self::typeLabels(self::TYPE_PUR),
                self::TYPE_EXPS => self::typeLabels(self::TYPE_EXPS),
                self::TYPE_EMP => self::typeLabels(self::TYPE_EMP),

            ],
            self::GROUP_CARD => [
                self::TYPE_CARD => self::typeLabels(self::TYPE_CARD)
            ],
            self::GROUP_LAB => [
                self::TYPE_LAB => self::typeLabels(self::TYPE_LAB)
            ],

        ];

        if ($group)
            $groups = isset($groups[$group]) ? $groups[$group] : [];

        if ($keys)
            $groups = ($group) ? array_keys($groups) : $groups;

        return $groups;

    }
    public function getBank()
    {
        return $this->hasOne(Banks::className(), ['id' => 'company_id']);
    }
    public function getGroupByType($type){
        $groups = $this->getGroupTypes();

        foreach ($groups as $key => $group){
            if(key_exists($type,$group))
                return $key;
        }

        return false;
    }

    public function activityStatus(){
        $group = $this->getGroupByType($this->type);
        if(!$group)
            $groupName = $this->formName();
        else{
            $groupName = $this->groupLabels($group);
        }

        if($this->created_at == $this->updated_at){
            return Module::t('New {name} added',['name' => Module::t($groupName . ' record')]);
        }else{
            return Module::t('{name} updated',['name' => Module::t($groupName . ' record')]);
        }
    }

}
