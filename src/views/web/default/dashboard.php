<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use portalium\theme\widgets\Portlet;
use yii\bootstrap\Tabs;
use diginova\mhsb\Module;

$this->title = Module::t('Dashboard');
$data['title'] = Html::encode($this->title);

$this->params['pageTitle'] = Module::t( 'Dashboard');
$this->params['pageDesc'] = Module::t( '');

?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?= $totalCol ?>"><?= $totalCol ?></span> ₺
                </div>
                <div class="desc"><?= Module::t('Total Collecting') ?></div>
            </div>
            <!--
            <a class="more" href="javascript:;"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
            -->
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="dashboard-stat red">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?= $totalPay ?>"><?= $totalPay ?></span> ₺
                </div>
                <div class="desc"><?= Module::t('Total Payment') ?></div>
            </div>
            <!--
            <a class="more" href="javascript:;"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
            -->
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?= $totalProfit ?>"><?= $totalProfit ?></span> ₺
                </div>
                <div class="desc"><?= Module::t('Total Profit') ?></div>
            </div>
            <!--
            <a class="more" href="javascript:;"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
            -->
        </div>
    </div>
    <!--
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="<?= $totalDocuments ?>"><?= $totalDocuments ?></span>
                </div>
                <div class="desc"> Total Documents</div>
            </div>
            <a class="more" href="javascript:;"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    -->
</div>
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-share font-blue"></i>
                    <span class="caption-subject font-blue bold uppercase"><?= Module::t('Recent Activities') ?></span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                    <ul class="feeds">
                        <?php foreach ($activitiesData as $actity): ?>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm <?= $actity['model-color'] ?>">
                                                <i class="<?= $actity['model-icon'] ?>"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> <?= $actity['label'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> <?= $actity['date-label'] ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!--
                <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                        <a href="javascript:;">See All Records</a>
                        <i class="icon-arrow-right"></i>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-blue"></i>
                    <span class="caption-subject font-blue bold uppercase"><?= Module::t('Last Transactions') ?></span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible="0">
                    <ul class="feeds">
                        <?php foreach ($lastTransactions as $transaction): ?>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <?php if ($transaction->type->type == \diginova\mhsb\models\Definitions::TYPE_COL): ?>
                                                <div class="label label-sm label-success">
                                                    <i class="fa fa-line-chart"></i>
                                                </div>
                                            <?php else: ?>
                                                <div class="label label-sm label-danger">
                                                    <i class="fa fa-area-chart"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> <?php  print_r($transaction->company->name) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"><?php if ($transaction->type->type == \diginova\mhsb\models\Definitions::TYPE_PAY): ?>
                                            <?= '-' . $transaction->amount ?>
                                        <?php else: ?>
                                            <?= $transaction->amount ?>
                                        <?php endif; ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!--
                <div class="scroller-footer">
                    <div class="btn-arrow-link pull-right">
                        <a href="javascript:;">See All Records</a>
                        <i class="icon-arrow-right"></i>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
</div>

