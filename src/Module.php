<?php

namespace diginova\mhsb;

class Module extends \portalium\base\Module
{
    public $apiRules = [
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => [
                'mhsb/mhsb',
                'mhsb/companies',
                'mhsb/banks',
                'mhsb/documents',
                'mhsb/items',
                'mhsb/definitions',
                'mhsb/labels'
            ]
        ],
    ];
    
    public static function moduleInit()
    {
        self::registerTranslation('mhsb','@diginova/mhsb/messages',[
            'mhsb' => 'mhsb.php',
        ]);
    }

    public static function t($message, array $params = [])
    {
        return parent::coreT('mhsb', $message, $params);
    }
}
