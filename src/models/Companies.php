<?php

namespace diginova\mhsb\models;

use diginova\mhsb\Module;
use portalium\helpers\ObjectHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property string $email
 * @property int $type_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Definitions $type
 * @property Documents[] $documents
 * @property Transactions[] $transactions
 */
class Companies extends \yii\db\ActiveRecord
{


    const STATUS_ACTIVE = '1';
    const STATUS_PASSIVE = '0';

    const GROUP_CUSTOMER = 0;
    const GROUP_VENDOR = 1;
    const GROUP_CUSVEN = 2;


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
        return 'mhsb_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'domain', 'email', 'def_id', 'group','tax_id_number','tax_department','tax_country','address','status'], 'required'],
            [['def_id'], 'integer'],
            [['created_at','updated_at'], 'safe'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE,self::STATUS_PASSIVE]],
            ['group', 'in' ,'range'=> [self::GROUP_CUSTOMER,self::GROUP_CUSVEN,self::GROUP_VENDOR]],
            [['name'], 'string', 'max' => 128],
            [['domain', 'email', 'status'], 'string', 'max' => 32],
            [['tax_id_number'],'string','min' => 2,'max' => 13],
            [['tax_department'],'string','max' => 64],
            [['tax_country'],'string','max' => 2],
            [['address'],'string','max' => 500],
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
            'name' => Module::t('Name'),
            'domain' => Module::t('Domain'),
            'email' => Module::t('Email'),
            'def_id' => Module::t('Type'),
            'group' => Module::t('Group'),
            'tax_id_number' => Module::t('Tax Id Number'),
            'tax_department' => Module::t('Tax Department'),
            'tax_country' => Module::t('Tax Country'),
            'address' => Module::t('Address'),
            'status' => Module::t('Status'),
            'created_at' => Module::t('Created At'),
            'updated_at' => Module::t('Updated At'),
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
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::className(), ['company_id' => 'id']);
    }

    public function statusLabels($group = false)
    {
        $labels = [
            self::STATUS_ACTIVE => Module::t('Active'),
            self::STATUS_PASSIVE => Module::t('Passive'),
        ];

        if ($group) {
            return isset($labels[$group]) ? $labels[$group] : $group;
        } else
            return $labels;
    }

    public static function getStatuses($group = false, $keys = false)
    {
        $groups = [
            self::STATUS_ACTIVE => self::statusLabels(self::STATUS_ACTIVE),
            self::STATUS_PASSIVE => self::statusLabels(self::STATUS_PASSIVE)
        ];

        if ($group)
            $groups = isset($groups[$group]) ? $groups[$group] : [];

        if ($keys)
            $groups = ($group) ? array_keys($groups) : $groups;

        return $groups;
    }

    public function groupLabels($group = false)
    {
        $labels = [
            self::GROUP_CUSTOMER  => Module::t('Customer'),
            self::GROUP_VENDOR => Module::t('Vendor'),
            self::GROUP_CUSVEN => Module::t('Customer & Vendor'),
        ];

        if ($group) {
            return isset($labels[$group]) ? $labels[$group] : $group;
        } else
            return $labels;

    }

    public static function getGroups($group = false,$keys = false)
    {
        $groups = [
            self::GROUP_CUSTOMER  => self::groupLabels(self::GROUP_CUSTOMER),
            self::GROUP_VENDOR => self::groupLabels(self::GROUP_VENDOR),
            self::GROUP_CUSVEN => self::groupLabels(self::GROUP_CUSVEN)
        ];

        if ($group)
            $groups = isset($groups[$group]) ? $groups[$group] : [];

        if ($keys)
            $groups = ($group) ? array_keys($groups) : $groups;

        return $groups;
    }


}
