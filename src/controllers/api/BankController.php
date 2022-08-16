<?php

namespace diginova\mhsb\controllers\api;
use Yii;
use portalium\rest\ActiveController as RestActiveController;
use diginova\mhsb\Module;
use diginova\mhsb\models\Banks;


class BankController extends RestActiveController
{
    public $modelClass = 'diginova\mhsb\models\Banks';

}


?>