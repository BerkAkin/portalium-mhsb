<?php

namespace diginova\mhsb\controllers\web;

use Yii;
use portalium\web\Controller as WebController;
use diginova\mhsb\models\Upload;
use yii\web\UploadedFile;
use diginova\mhsb\Module;
use yii\db\ActiveRecord;


class UploadController extends WebController
{
    public function actionUpload()
    {
        $model = new Upload();

        if($model->load(Yii::$app->request->post())){
        $file = UploadedFile::getInstance($model,'file');
        $filename = 'Data.'.$file->extension;
        $upload = $file->saveAs('uploads/'.$filename);
        if($upload){
            define('CSV_PATH','uploads/');
            $csv_file = CSV_PATH . $filename;
            $filecsv = file($csv_file);
            foreach($filecsv as $data){
                $modelnew = new Upload;
                $result = explode(";",$data);
                $floatValue = $result[0];
                $textValue = $result[1];
                $modelnew->floatValue = $floatValue;
                $modelnew->textValue = $textValue;
                $modelnew->save(false);
            }
            unlink('uploads/'.$filename);
            return $this->redirect(['upload/upload']);
        }
    }else{
        return $this->render('upload',['model'=>$model]);
    }
}
        }
    
    