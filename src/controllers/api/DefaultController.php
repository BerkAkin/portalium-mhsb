<?php

namespace diginova\mhsb\controllers\api;

use yii\web\Response;

class DefaultController extends \diginova\base\controllers\api\BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actionIndex()
    {
        return [
            'status' => 1, 
            'action' => 'index', 
            'controller' => 'default'
        ];
    }

}