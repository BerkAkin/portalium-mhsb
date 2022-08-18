<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use diginova\metronic\widgets\Portlet;
use diginova\survey\Module;

$this->title 	= Module::t('accounting', 'Accounting');
$data['title'] 	= Html::encode($this->title);

$this->params['breadcrumbs'][] = ['label' => Module::t('accounting', 'Accounting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['pageTitle'] 	= Module::t('accounting', 'Accounting');
$this->params['pageDesc'] 	= Module::t('accounting', 'default view');

?>