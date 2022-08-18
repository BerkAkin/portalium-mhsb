<?php

namespace diginova\mhsb\controllers\api;
use common\models\Post;
use diginova\mhsb\models\Documents;
use Yii;
use portalium\rest\ActiveController as RestActiveController;
use diginova\mhsb\Module;
use diginova\mhsb\models\Definitions;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;




   



class LabelController extends RestActiveController
{
    public $modelClass= 'diginova\mhsb\models\Definitions';

    public function actions(){
        $actions = parent::actions();
        unset($actions['index'],$actions['update'],$actions['delete'],$actions['create']);


        return $actions;
    }

    public function actionIndex()
    {
        $activeData = new ActiveDataProvider([
            'query' => Definitions::find()->asArray()->where(['type' => 'label'])
        ]);
        return $activeData;
    }

    public function actionUpdate($id)
    {

        $model = Definitions::findOne($id);

            if($model->load(Yii::$app->getRequest()->getBodyParams(),'')) {
                if($model->type == 'label'){
                if($model->save()){
                    return $model;}
                else{
                    return $this->modelError($model);}
            }else{
                return $this->error((array)Module::t("Permission Denied"));
            }

            }




    }

    public function actionDelete($id)
    {


        $model = Definitions::findOne($id);
        if($model->type == 'label'){
        $model->delete();

        }
        else{
            return $this->error((array)Module::t("Permission Denied"));
        }




}


    public function actionCreate()
    {
        $model = new Definitions();

    

        if($model->load(Yii::$app->getRequest()->getBodyParams(),'')) {
            if($model->type == 'label'){
                if($model->save()){
                    return $model;}
                else{
                    return $this->modelError($model);}
            }else{
                return $this->error((array)Module::t("Permission Denied"));
            }

        }

    }




}


