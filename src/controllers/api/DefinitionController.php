<?php

namespace diginova\mhsb\controllers\api;
use Yii;
use diginova\mhsb\models\Definitions;
use yii\console\ExitCode;
use portalium\rest\ActiveController as RestActiveController;
use diginova\mhsb\Module;
use yii\data\ActiveDataProvider;


 
    class DefinitionController extends RestActiveController
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
                'query' => Definitions::find()->asArray()->where(['type' => 'sale']),
            ]);
            return $activeData;
        }
}
