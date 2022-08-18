<?php

namespace diginova\mhsb\controllers\api;
use Yii;
use portalium\rest\ActiveController as RestActiveController;
use diginova\mhsb\Module;
use diginova\mhsb\models\Companies;
use yii\data\ActiveDataProvider;

class CompanyController extends RestActiveController
{
    public $modelClass = 'diginova\mhsb\models\Definitions';

    public function actions(){
        $actions = parent::actions();
        unset($actions['index']);

        return $actions;
    }

    public function actionIndex()
    {

        $activeData = new ActiveDataProvider([
            'query' => Companies::find()->asArray(),
        ]);

        return $activeData;
    }

}

