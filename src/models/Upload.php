<?php

namespace diginova\mhsb\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use diginova\mhsb\Module;

class Upload extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules(){
        return [
            [['file'],'required'],
            [['file'],'file','extensions'=>'csv','maxSize'=>1024 * 1024 * 5],
        ];
    }
   
    public function attributeLabels(){
        return [
            'file'=>'Select File',
        ];
    }
}
