<?php

use yii\helpers\Html;

use diginova\mhsb\Module;

$this->title = Module::t('Dashboard');
$data['title'] = Html::encode($this->title);

$this->params['pageTitle'] = Module::t( 'Dashboard');
$this->params['pageDesc'] = Module::t( '');

?>
